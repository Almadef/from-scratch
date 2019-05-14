<?php

namespace Application\Controllers;

use Application\Core\Controller as Controller;
use Application\Exceptions\Exception401 as Exception401;
use Application\Models\UserModel as UserModel;
use Application\Models\AlbumModel as AlbumModel;
use Application\Mappers\AlbumMapper as AlbumMapper;
use Application\Mappers\PerformerMapper as PerformerMapper;
use Application\Modules\Validator\Core as Core;
use Application\Modules\Validator\Forms\AddAlbumForm as AddAlbumForm;
use Application\Modules\Validator\Forms\UpdateAlbumForm as UpdateAlbumForm;

/**
 * Class AlbumSettingsController
 */
class AlbumSettingsController extends Controller
{
    /**
     * @var UserModel
     */
    private $userModel;
    /**
     * @var AlbumModel
     */
    private $albumModel;
    /**
     * @var AlbumMapper
     */
    private $albumMapper;
    /**
     * @var PerformerMapper
     */
    private $performerMapper;

    /**
     * CabinetController constructor.
     */
    public function __construct()
    {
        if (!isset($_SESSION['id'])) {
            throw new Exception401();
        }
        parent::__construct();

        $this->userModel = new UserModel();
        $this->userModel->setId($_SESSION['id']);
        $this->albumModel = new AlbumModel();

        $this->albumMapper = new AlbumMapper();
        $this->performerMapper = new PerformerMapper();
    }

    /**
     *
     */
    public function actionUserAlbums()
    {
        $script['errorMessageTemplate']=Core::getMessageTemplate();
        $script['addAlbumModal']=AddAlbumForm::getValidatorSpecification();
        $script['updateAlbumModal']=UpdateAlbumForm::getValidatorSpecification();
        $data['script'] = json_encode($script, JSON_UNESCAPED_UNICODE);

        $data['albums'] = $this->albumMapper->selectAllUserAlbums($this->userModel->getId());
        $data['performers'] = $this->performerMapper->selectAllPerformers();
        if (count($data['albums']) !== 0) {
            $data['albums'] = $this->albumModel->coversExists($data['albums']);
        }
        $this->view->generate('/albumSettings/userAlbums.php', 'Мои альбомы', $data);
    }

    public function actionAjaxAllPerformers()
    {
        $data = $this->performerMapper->selectAllPerformers();
        foreach ($data as $row) {
            $arr[] = array('id' => $row->getId(), 'title' => $row->getName());
        }
        echo json_encode($arr);
        return;
    }

    public function actionAjaxSelectUserAlbum()
    {
        $this->albumModel->setId($_POST['id']);
        $selectedUserAlbum['album'] = $this->albumMapper->selectAlbumById($this->albumModel->getId());
        $selectedUserAlbum['performers'] = $this->performerMapper->selectAllPerformers();
        $selectedUserAlbum['idSelectPerformers'] = $this->performerMapper->selectIdPerformersAlbums($this->albumModel->getId());
        $arr = array('title' => $selectedUserAlbum['album']->getTitle());
        $arr['performers'] = array();
        foreach ($selectedUserAlbum['performers'] as $row) {
            if (in_array($row->getId(), $selectedUserAlbum['idSelectPerformers'])) {
                $arr['performers'][] = array('id' => $row->getId(), 'title' => $row->getName(), 'selected' => true);
            } else {
                $arr['performers'][] = array('id' => $row->getId(), 'title' => $row->getName(), 'selected' => false);
            }
        }
        echo json_encode($arr);
        return;
    }

    public function actionAjaxCreateAlbum()
    {
        $error = '';
        if (isset($_POST['title'])) {
            $resValid = Core::isValidForm([
                'addTitleAlbum' => $_POST['title'],
                'addPerformersAlbum' => isset($_POST['performers']) ? $_POST['performers'] : array()
            ], AddAlbumForm::class);
            $data['error']['div'] = $resValid['errDiv'];
            if ($resValid['suc']) {
                $this->albumModel->setTitle($_POST['title']);
                $this->albumModel->setPerformers(isset($_POST['performers']) ? $_POST['performers'] : array());
                $result = $this->albumMapper->insertAlbum($this->albumModel, $this->userModel->getId());
                $error = $this->checkErrors($result['error']);
            } else {
                $error = $resValid['errMsg'];
            }
        }
        echo $error;
    }

    /**
     *
     */
    public function actionAjaxUpdateAlbum()
    {
        $error = '';
        $this->albumModel->setId($_POST['id']);
        if (isset($_POST['title'])) {
            $resValid = Core::isValidForm([
                'updateTitleAlbum' => $_POST['title'],
                'updatePerformersAlbum' => isset($_POST['performers']) ? $_POST['performers'] : array()
            ], UpdateAlbumForm::class);
            $data['error']['div'] = $resValid['errDiv'];
            if ($resValid['suc']) {
                $this->albumModel->setTitle($_POST['title']);
                $this->albumModel->setPerformers(isset($_POST['performers']) ? $_POST['performers'] : array());
                $result = $this->albumMapper->updateAlbum($this->albumModel);
                $error = $this->checkErrors($result['error']);
            } else {
                $error = $resValid['errMsg'];
            }
        }
        echo $error;
    }

    /**
     *
     */
    public function actionCoverAlbum()
    {
        if (isset($_FILES['picture'])) {
            $this->albumModel = $this->albumMapper->selectAlbumById($_POST['id']);
            $result = $this->albumModel->uploadCover();
            if ($result['error'] === '') {
                $result = $this->albumMapper->saveAlbumCover($result['nameOut'], $this->albumModel->getId());
            }
            echo $this->checkErrors($result['error']);
        }
        return;
    }

    /**
     *
     */
    public function actionDeleteAlbum()
    {
        $this->albumMapper->deleteAlbum($_POST['id']);
        header("Location: /albumSettings/userAlbums");
    }

    /**
     * @param $error
     * @return string
     */
    private function checkErrors($error): string
    {
        if ($error === '') {
            return '';
        }
        return $error;
    }
}