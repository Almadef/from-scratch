<?php

namespace Application\Controllers;

use Application\Core\Controller as Controller;
use Application\Models\UserModel as UserModel;
use Application\Mappers\UserMapper as UserMapper;
use Application\Modules\Validator\Core as Core;
use Application\Modules\Validator\Forms\LoginForm as LoginForm;
use Application\Modules\Validator\Forms\SignupForm as SignupForm;

/**
 * Class AuthController
 */
class AuthController extends Controller
{
    /**
     * @var UserModel
     */
    private $userModel;

    /**
     * @var UserMapper
     */
    private $userMapper;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->userMapper = new UserMapper();
    }


    /**
     *
     */
    public function actionSignup()
    {
        $error = '';

        $script['errorMessageTemplate']=Core::getMessageTemplate();
        $script['signupForm']=SignupForm::getValidatorSpecification();
        $data['script'] = json_encode($script, JSON_UNESCAPED_UNICODE);

        if (isset($_POST['login'])) {
            $resValid = Core::isValidForm([
                'login' => $_POST['login'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'repeat_password' => $_POST['repeat_password']
            ],
                SignupForm::class);
            $data['error']['div'] = $resValid['errDiv'];
            if ($resValid['suc']) {
                $this->userModel->setLogin($_POST['login']);
                $this->userModel->setEmail($_POST['email']);
                $this->userModel->setPassword($this->userModel->createPassword($_POST['password']));

                $resultAvailableLogin = $this->userMapper->availableLogin($this->userModel->getLogin());
                if ($resultAvailableLogin['error'] !== '') {
                    $error = $resultAvailableLogin['error'];
                }

                $resultAvailableEmail = $this->userMapper->availableEmail($this->userModel->getEmail());
                if ($resultAvailableEmail['error'] !== '') {
                    $error = $resultAvailableEmail['error'];
                }

                if ($error === '') {
                    $this->userModel->setId($this->userMapper->insertUser($this->userModel));
                    $_SESSION['id'] = $this->userModel->getId();
                    $_SESSION['login'] = $this->userModel->getLogin();
                    $_SESSION['email'] = $this->userModel->getEmail();
                    header("Location: /");
                    exit;
                }
            } else {
                $error = $resValid['errMsg'];
            }
        }
        $data['error']['msg'] = $error;
        (isset($_POST['login'])) ? $data['login'] = $_POST['login'] : $data['login'] = '';
        (isset($_POST['email'])) ? $data['email'] = $_POST['email'] : $data['email'] = '';
        $this->view->generate('auth/signup.php', 'Регистрация', $data);
    }


    /**
     *
     */
    public function actionLogin()
    {
        $error = '';

        $script['errorMessageTemplate']=Core::getMessageTemplate();
        $script['loginForm']=LoginForm::getValidatorSpecification();
        $data['script'] = json_encode($script, JSON_UNESCAPED_UNICODE);

        if (isset($_POST['login'])) {
            $resValid = Core::isValidForm(['login' => $_POST['login'], 'password' => $_POST['password']],
                LoginForm::class);
            $data['error']['div'] = $resValid['errDiv'];
            if ($resValid['suc']) {
                $this->userModel->setLogin($_POST['login']);
                $this->userModel->setPassword($_POST['password']);

                $user = $this->userMapper->selectUserWhereLogin($this->userModel->getLogin());
                if ($user === false) {
                    $error = 'Неверно введен login или password. Попробуйте набрать еще раз.';
                } else {
                    $resultVerifyUserPassword = $this->userModel->verifyUserPassword($user->getPassword());
                    if ($resultVerifyUserPassword['error'] !== '') {
                        $error = $resultVerifyUserPassword['error'];
                    }
                }

                if ($error === '') {
                    $_SESSION['id'] = $user->getId();
                    $_SESSION['login'] = $user->getLogin();
                    $_SESSION['email'] = $user->getEmail();
                    header("Location: /");
                    exit;
                }
            } else {
                $error = $resValid['errMsg'];
            }
        }
        $data['error']['msg'] = $error;
        (isset($_POST['login'])) ? $data['login'] = $_POST['login'] : $data['login'] = '';
        $this->view->generate('auth/login.php', 'Вход', $data);
    }

    /**
     *
     */
    public function actionLogout()
    {
        unset($_SESSION['id']);
        unset($_SESSION['login']);
        unset($_SESSION['email']);
        session_destroy();
        header('Location: /');
        exit;
    }
}