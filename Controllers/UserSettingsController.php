<?php

namespace Application\Controllers;

use Application\Core\Controller as Controller;
use Application\Exceptions\Exception401 as Exception401;
use Application\Models\UserModel as UserModel;
use Application\Mappers\UserMapper as UserMapper;
use Application\Modules\Validator\Core as Core;
use Application\Modules\Validator\Forms\UpdateLoginForm as UpdateLoginForm;
use Application\Modules\Validator\Forms\UpdateEmailForm as UpdateEmailForm;
use Application\Modules\Validator\Forms\UpdatePasswordForm as UpdatePasswordForm;

/**
 * Class UserSettingsController
 */
class UserSettingsController extends Controller
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
     * UserSettingsController constructor.
     */
    public function __construct()
    {
        if (!isset($_SESSION['id'])) {
            throw new Exception401();
        }
        parent::__construct();
        $this->userModel = new UserModel();
        $this->userMapper = new UserMapper();
    }

    /**
     *
     */
    public function actionChangeUser()
    {
        $data = array();
        $data['error']['updateLoginMsg'] = '';
        $data['error']['updateEmailMsg'] = '';
        $data['error']['updatePasswordMsg'] = '';

        $script['errorMessageTemplate']=Core::getMessageTemplate();
        $script['updateLoginForm']=UpdateLoginForm::getValidatorSpecification();
        $script['updateEmailForm']=UpdateEmailForm::getValidatorSpecification();
        $script['updatePasswordForm']=UpdatePasswordForm::getValidatorSpecification();
        $data['script'] = json_encode($script, JSON_UNESCAPED_UNICODE);

        if (isset($_POST['newLogin'])) {
            $resValid = Core::isValidForm(['newLogin' => $_POST['newLogin'], 'password' => $_POST['password']],
                UpdateLoginForm::class);
            $data['error']['div'] = $resValid['errDiv'];

            if ($resValid['suc']) {
                $result = $this->updateLogin();
                $result = $result['error'];
            } else {
                $result = $resValid['errMsg'];
            }
            $data['error']['updateLoginMsg'] = $result;


        } elseif (isset($_POST['newEmail'])) {
            $resValid = Core::isValidForm(['newEmail' => $_POST['newEmail'], 'password' => $_POST['password']],
                UpdateEmailForm::class);
            $data['error']['div'] = $resValid['errDiv'];

            if ($resValid['suc']) {
                $result = $this->updateEmail();
                $result = $result['error'];
            } else {
                $result = $resValid['errMsg'];
            }
            $data['error']['updateEmailMsg'] = $result;

        } elseif (isset($_POST['newPassword'])) {
            $resValid = Core::isValidForm([
                'newPassword' => $_POST['newPassword'],
                'repeatNewPassword' => $_POST['repeatNewPassword'],
                'oldPassword' => $_POST['oldPassword']
            ],
                UpdatePasswordForm::class);
            $data['error']['div'] = $resValid['errDiv'];

            if ($resValid['suc']) {
                $result = $this->updatePassword();
                $result = $result['error'];
            } else {
                $result = $resValid['errMsg'];
            }
            $data['error']['updatePasswordMsg'] = $result;
        }

        $data['user'] = $this->userMapper->selectUserWhereId($_SESSION['id']);
        $this->view->generate('/userSettings/changeUser.php', 'Настройки', $data);
    }

    /**
     * @return array
     */
    private function updateLogin(): array
    {
        $result['error'] = '';

        if ($_POST['newLogin'] === $_SESSION['login']) {
            $result['error'] = 'Новый login не должен совпадать со старым';
            return $result;
        }

        $resultAvailableLogin = $this->userMapper->availableLogin($_POST['newLogin']);
        if ($resultAvailableLogin['error'] !== '') {
            $result['error'] = $resultAvailableLogin['error'];
            return $result;
        }

        $resultCheckPassword = $this->userMapper->checkPassword($_POST['password'], $_SESSION['id']);
        if ($resultCheckPassword['error'] !== '') {
            $result['error'] = $resultCheckPassword['error'];
            return $result;
        }

        if ($result['error'] === '') {
            $result = $this->userMapper->updateUserLogin($_POST['newLogin'], $_SESSION['id']);
        }

        if ($result['error'] === '') {
            $_SESSION['login'] = $_POST['newLogin'];
        }

        return $result;
    }

    /**
     * @return array
     */
    private function updateEmail(): array
    {
        $result['error'] = '';
        if ($_POST['newEmail'] === $_SESSION['email']) {
            $result['error'] = 'Новый email не должен совпадать со старым';
            return $result;
        }

        $resultAvailableEmail = $this->userMapper->availableEmail($_POST['newEmail']);
        if ($resultAvailableEmail['error'] !== '') {
            $result['error'] = $resultAvailableEmail['error'];
            return $result;
        }

        $resultCheckPassword = $this->userMapper->checkPassword($_POST['password'], $_SESSION['id']);
        if ($resultCheckPassword['error'] !== '') {
            $result['error'] = $resultCheckPassword['error'];
            return $result;
        }

        if ($result['error'] === '') {
            $result = $this->userMapper->updateUserEmail($_POST['newEmail'], $_SESSION['id']);
        }

        if ($result['error'] === '') {
            $_SESSION['email'] = $_POST['newEmail'];
        }

        return $result;
    }

    /**
     * @return array
     */
    private function updatePassword(): array
    {
        $result['error'] = '';
        if ($_POST['newPassword'] !== $_POST['repeatNewPassword']) {
            $result['error'] = 'Повторно password введен неверно';
            return $result;
        }

        if ($_POST['newPassword'] == $_SESSION['login']) {
            $result['error'] = 'Password не должен совпадать с login';
            return $result;
        }

        $resultCheckPassword = $this->userMapper->checkPassword($_POST['oldPassword'], $_SESSION['id']);
        if ($resultCheckPassword['error'] !== '') {
            $result['error'] = $resultCheckPassword['error'];
            return $result;
        }

        if ($result['error'] === '') {
            $result = $this->userMapper->updateUserPassword($this->userModel->createPassword($_POST['newPassword']),
                $_SESSION['id']);
        }

        return $result;
    }
}