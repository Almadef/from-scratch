<?php

namespace Application\Controllers;

use Application\Core\Controller as Controller;
use Application\Mappers\AlbumMapper as AlbumMapper;
use Application\Mappers\PerformerMapper as PerformerMapper;
use Application\Models\AlbumModel as AlbumModel;

/**
 * Class SiteController
 */
class SiteController extends Controller
{
    /**
     * @var AlbumMapper
     */
    private $albumMapper;

    /**
     * @var PerformerMapper
     */
    private $performerMapper;

    /**
     * @var AlbumModel
     */
    private $albumModel;

    /**
     * SiteController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->albumMapper = new AlbumMapper();
        $this->performerMapper = new PerformerMapper();
        $this->albumModel = new AlbumModel();
    }


    /**
     *
     */
    public function actionIndex()
    {
        $countView = 8;
        if(isset($_GET['page'])){
            $pageNum = (int)$_GET['page'];
        }else{
            $pageNum = 1;
        }
        $startIndex = ($pageNum-1)*$countView;

        $data['pageNum'] = $pageNum;
        $countAllAlbums=$this->albumMapper->selectCountAllAlbums();
        $data['lastPage'] = ceil($countAllAlbums/$countView);
        $data['albums'] = $this->albumMapper->selectAllAlbumsPagination($startIndex, $countView);
        $data['albums'] = $this->albumModel->coversExists($data['albums']);

        $this->view->generate('site/index.php', 'musicAlbums', $data);
    }

    /**
     *
     */
    public function actionSearchAlbumsPerformer()
    {
        if(!isset($_GET['idPerformer'])){
            throw new Exception404();
        }
        $idAlbums = $this->performerMapper->selectIdAlbumsPerformer($_GET['idPerformer']);

        $countView = 8;
        if(isset($_GET['page'])){
            $pageNum = (int)$_GET['page'];
        }else{
            $pageNum = 1;
        }
        $startIndex = ($pageNum-1)*$countView;

        $data['pageNum'] = $pageNum;
        $countAlbumsPerformer=count($idAlbums);
        $data['lastPage'] = ceil($countAlbumsPerformer/$countView);
        $data['performer'] = $this->performerMapper->selectPerdormerById($_GET['idPerformer']);
        $data['albums'] = $this->albumMapper->selectAllAlbumsPerformerPagination($idAlbums, $startIndex, $countView);
        $data['albums'] = $this->albumModel->coversExists($data['albums']);

        $this->view->generate('site/searchAlbumsPerformer.php', $data['performer']->getName(), $data);
    }
}