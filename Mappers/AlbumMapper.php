<?php

namespace Application\Mappers;

use Application\Core\Mapper as Mapper;
use Application\Mappers\PerformerMapper as PerformerMapper;

/**
 * Class AlbumMapper
 * @package Application\Mappers
 */
class AlbumMapper extends Mapper
{
    /**
     * @var \Application\Mappers\PerformerMapper
     */
    private $performerMapper;

    /**
     * AlbumMapper constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->performerMapper = new PerformerMapper();
    }


    /**
     * @param int $startIndex
     * @param int $countView
     * @return array
     */
    public function selectAllAlbumsPagination(int $startIndex, int $countView): array
    {
        $stmt = $this->db->prepare('SELECT * FROM `album` ORDER BY rating DESC LIMIT :startIndex, :countView');
        $stmt->bindParam(':startIndex', $startIndex, \PDO::PARAM_INT);
        $stmt->bindParam(':countView', $countView, \PDO::PARAM_INT);
        $stmt->execute();
        $albums = $stmt->fetchAll(\PDO::FETCH_CLASS, "\Application\Models\AlbumModel");
        foreach ($albums as &$album) {
            $album->setPerformers($this->performerMapper->selectPerformersAlbum($album->getId()));
        }
        return $albums;
    }


    /**
     * @param $idAlbums
     * @param int $startIndex
     * @param int $countView
     * @return array
     */
    public function selectAllAlbumsPerformerPagination($idAlbums, int $startIndex, int $countView): array
    {
        $arrValue = array();
        $sql = 'SELECT album.* FROM `album` WHERE id IN (';
        foreach ($idAlbums as $id) {
            $sql .= '?,';
            $arrValue[] = $id;
        }
        $sql = substr($sql, 0, -1);
        $sql .= ') ORDER BY rating DESC LIMIT ?, ?';

        $stmt = $this->db->prepare($sql);
        $parameter = 1;
        foreach ($arrValue as $value) {
            $stmt->bindValue($parameter, $value, \PDO::PARAM_STR);
            $parameter = $parameter + 1;
        }
        $stmt->bindValue($parameter, $startIndex, \PDO::PARAM_INT);
        $parameter = $parameter + 1;
        $stmt->bindValue($parameter, $countView, \PDO::PARAM_INT);
        $stmt->execute();
        $albums = $stmt->fetchAll(\PDO::FETCH_CLASS, "\Application\Models\AlbumModel");

        foreach ($albums as &$album) {
            $album->setPerformers($this->performerMapper->selectPerformersAlbum($album->getId()));
        }
        return $albums;
    }

    /**
     * @return array
     */
    public function selectCountAllAlbums(): int
    {
        $stmt = $this->db->query("SELECT count(*) FROM `album`");
        return $stmt->fetch(\PDO::FETCH_COLUMN);
    }

    /**
     * @param int $userId
     * @return array
     */
    public function selectAllUserAlbums(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT album.* FROM `album` where user_id = :id_user');
        $stmt->bindParam(':id_user', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $albums = $stmt->fetchAll(\PDO::FETCH_CLASS, "\Application\Models\AlbumModel");

        foreach ($albums as &$album) {
            $album->setPerformers($this->performerMapper->selectPerformersAlbum($album->getId()));
        }

        return $albums;
    }

    /**
     * @param int $albumId
     * @return \Application\Models\AlbumModel
     */
    public function selectAlbumById(int $albumId): \Application\Models\AlbumModel
    {
        $stmt = $this->db->prepare('SELECT album.* FROM `album` where id = :id');
        $stmt->bindParam(':id', $albumId, \PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, "\Application\Models\AlbumModel");
        return $stmt->fetch();
    }

    /**
     * @param int $userId
     * @param \Application\Models\AlbumModel $album
     * @return array
     */
    public function insertAlbum(\Application\Models\AlbumModel $album, int $userId): array
    {
        if (($album->getTitle() === '') || (count($album->getPerformers()) === 0)) {
            return ['error' => 'Не заполнены все поля'];
        }

        $this->db->beginTransaction();
        $sql = 'INSERT INTO `album` (`title`, `user_id`) VALUES (?,?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$album->getTitle(), $userId]);

        $idAlbum = $this->db->lastInsertId();
        $arrValue = array();
        $sql = "INSERT INTO `album_performer` (`album_id`,`performer_id`) VALUES";
        foreach ($album->getPerformers() as $performer) {
            $sql .= ' (?, ?),';
            $arrValue = array_merge($arrValue, [$idAlbum, $performer]);
        }
        $sql = substr($sql, 0, -1);
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute($arrValue)) {
            $this->db->commit();
            return ['error' => ''];
        } else {
            $this->db->rollback();
            return ['error' => 'Не удалось добавить альбом в БД'];
        }
    }

    /**
     * @param \Application\Models\AlbumModel $album
     * @return array
     */
    public function updateAlbum(\Application\Models\AlbumModel $album): array
    {
        if (($album->getTitle() === '') || (count($album->getPerformers()) === 0)) {
            return ['error' => 'Не заполнены все поля'];
        }

        $this->db->beginTransaction();
        $sql = 'UPDATE `album` SET title=? where id=?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$album->getTitle(), $album->getId()]);

        $sql = "DELETE FROM `album_performer` WHERE album_id = :id";
        $stmt = $this->db->prepare($sql);
        $albumId = $album->getId();
        $stmt->bindParam(':id', $albumId, \PDO::PARAM_INT);
        $stmt->execute();

        $arrValue = array();
        $sql = "INSERT INTO `album_performer` (`album_id`,`performer_id`) VALUES";
        foreach ($album->getPerformers() as $performer) {
            $sql .= ' (?, ?),';
            $arrValue = array_merge($arrValue, [$album->getId(), $performer]);
        }
        $sql = substr($sql, 0, -1);
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute($arrValue) === false) {
            $this->db->rollback();
            return ['error' => 'Не удалось добавить альбом в БД'];
        } else {
            $this->db->commit();
            return ['error' => ''];
        }
    }

    /**
     * @param int $idAlbum
     * @return array
     */
    public function deleteAlbum(int $idAlbum): array
    {
        $stmt = $this->db->prepare('SELECT cover FROM album WHERE `id` = :id');
        $stmt->bindParam(':id', $idAlbum, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $coverPath = COVER_PATH_FOR_PHP . $row['cover'];
        $this->db->beginTransaction();
        $stmt = $this->db->prepare('DELETE FROM album_performer WHERE `album_id` = :album_id');
        $stmt->bindParam(':album_id', $idAlbum, \PDO::PARAM_INT);
        $stmt->execute();
        $stmt = $this->db->prepare('DELETE FROM album WHERE `id` = :id');
        $stmt->bindParam(':id', $idAlbum, \PDO::PARAM_INT);
        if ($stmt->execute() !== false) {
            $this->db->commit();
            if(substr($coverPath, -1)!=='/'){
                unlink($coverPath);
            }
            return ['error' => ''];
        } else {
            $this->db->rollback();
            return ['error' => 'Не удалось удалить альбом'];
        }
    }


    /**
     * @param string $name
     * @param int $albumId
     * @return array
     */
    public function saveAlbumCover(string $name, int $albumId): array
    {
        $stmt = $this->db->prepare('SELECT album.* FROM `album` where id = :id');
        $stmt->bindParam(':id', $albumId, \PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, "\Application\Models\AlbumModel");
        $album = $stmt->fetch();
        $coverPath = COVER_PATH_FOR_PHP . $album->getCover();
        $this->db->beginTransaction();
        $sql = 'UPDATE `album` SET cover=? where id=?';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$name, $albumId]) !== false) {
            $this->db->commit();
            if(substr($coverPath, -1)!=='/'){
                unlink($coverPath);
            }
            return ['error' => ''];
        } else {
            $this->db->rollback();
            return ['error' => 'Не удалось сохранить обложку в БД'];
        }
    }
}
