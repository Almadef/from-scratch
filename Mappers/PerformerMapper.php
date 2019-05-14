<?php

namespace Application\Mappers;

use Application\Core\Mapper as Mapper;

/**
 * Class PerformerMapper
 * @package Application\Mappers
 */
class PerformerMapper extends Mapper
{
    /**
     * @param int $performerId
     * @return \Application\Models\PerformerModel
     */
    public function selectPerdormerById(int $performerId): \Application\Models\PerformerModel
    {
        $stmt = $this->db->prepare('SELECT performer.* FROM `performer` where id = :id');
        $stmt->bindParam(':id', $performerId, \PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, "\Application\Models\PerformerModel");
        return $stmt->fetch();
    }
    /**
     * @return array
     */
    public function selectAllPerformers(): array
    {
        $stmt = $this->db->query('select * from `performer`');
        return $stmt->fetchAll(\PDO::FETCH_CLASS, "\Application\Models\PerformerModel");
    }

    /**
     * @param int $albumId
     * @return array
     */
    public function selectPerformersAlbum(int $albumId): array
    {
        $idPerformersAlbums = $this->selectIdPerformersAlbums($albumId);
        $arrValue = array();
        $sql = 'SELECT performer.* FROM `performer` WHERE id IN (';
        foreach ($idPerformersAlbums as $id) {
            $sql .= '?,';
            $arrValue[] = $id;
        }
        $sql = substr($sql, 0, -1);
        $sql .= ')';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($arrValue);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, "\Application\Models\PerformerModel");
    }

    /**
     * @param int $albumId
     * @return array
     */
    public function selectIdPerformersAlbums(int $albumId): array
    {
        $sql = 'SELECT album_performer.performer_id FROM `album_performer` WHERE album_id = :album_id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':album_id', $albumId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @param int $performerId
     * @return array
     */
    public function selectIdAlbumsPerformer(int $performerId): array
    {
        $sql = 'SELECT album_performer.album_id FROM `album_performer` WHERE performer_id = :performer_id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':performer_id', $performerId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
