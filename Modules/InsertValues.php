<?php

namespace Application\Modules;


/**
 * Class InsertValues
 * @package Application\Modules
 */
class InsertValues
{
    /**
     * @param \PDO $db
     * @param string $tableTitle
     * @param int $postCount
     * @param bool $view
     * @return bool
     */
    public function setValue(\PDO $db, $tableTitle, int $postCount, $view = false)
    {
        try {
            if ($view) {
                echo '<p>В таблице `' . $tableTitle . '` было ' . $this->getCountTable($db, $tableTitle) . ' записей';
            }
            switch ($tableTitle) {
                case 'performer':
                    $this->randomPerformer($db, $postCount);
                    break;
                case 'user':
                    $this->randomUser($db, $postCount);
                    break;
                case 'album':
                    $this->randomAlbum($db, $postCount);
                    break;
                case 'album_performer':
                    $this->randomAlbumPerformer($db, $postCount);
                    break;
                default:
                    echo 'Ошибка выбора таблицы. Данное имя не найдено.';
            }
            if ($view) {
                echo ', а стало ' . $this->getCountTable($db,
                        $tableTitle) . ' записей (больше на ' . $postCount . ')</p>';
            }
        } catch (\Throwable $e) {
            echo $e;
        }
        return true;
    }

    //Заполняем таблицу `performer`

    /**
     * @param \PDO $db
     * @param int $quantity
     * @return bool
     */
    private function randomPerformer(\PDO $db, $quantity = 20)
    {
        try {
            $db->beginTransaction();
            $sql = "INSERT INTO `performer` (`name`) VALUES";
            $arrValue = array();
            for ($i = 1; $i <= $quantity; $i++) {
                $sql .= ' (?),';
                $name = $this->generateRandomString();
                $arrValue = array_merge($arrValue, [$name]);
            }
            $sql = substr($sql, 0, -1);
            $stmt = $db->prepare($sql);
            $stmt->execute($arrValue);
            $db->commit();
        } catch (\Throwable $e) {
            echo $e;
        }
        return true;
    }

    //Заполняем таблицу `user`

    /**
     * @param \PDO $db
     * @param int $quantity
     * @return bool
     */
    private function randomUser(\PDO $db, $quantity = 20)
    {
        try {
            $db->beginTransaction();
            $sql = "INSERT INTO `user` (`name`,`password`) VALUES";
            $arrValue = array();
            for ($i = 1; $i <= $quantity; $i++) {
                $sql .= ' (?, ?),';
                $name = $this->generateRandomString();
                $password = $this->generateRandomString();
                $arrValue = array_merge($arrValue, [$name, $password]);
            }
            $sql = substr($sql, 0, -1);
            $stmt = $db->prepare($sql);
            $stmt->execute($arrValue);
            $db->commit();
        } catch (\Throwable $e) {
            echo $e;
        }
        return true;
    }

    //Заполняем таблицу `album`

    /**
     * @param \PDO $db
     * @param int $quantity
     * @return bool
     */
    private function randomAlbum(\PDO $db, $quantity = 20)
    {
        try {
            $countUser = $db->query('SELECT COUNT(1) FROM `user`');
            $countUser = $countUser->fetch();
            $sql = "INSERT INTO `album` (`title`,`user_id`) VALUES";
            $arrValue = array();
            for ($i = 0; $i < $quantity; $i++) {
                $sql .= ' (?, ?),';
                $title = $this->generateRandomString();
                $user_id = mt_rand(1, $countUser[0]);
                $arrValue = array_merge($arrValue, [$title, $user_id]);
            }
            $sql = substr($sql, 0, -1);
            $stmt = $db->prepare($sql);
            $stmt->execute($arrValue);
        } catch (\Throwable $e) {
            echo $e;
        }
        return true;
    }

    //Заполняем таблицу `album_performer`

    /**
     * @param \PDO $db
     * @param int $quantity
     * @return bool
     */
    private function randomAlbumPerformer(\PDO $db, $quantity = 20)
    {
        try {
            $countPerformer = $db->query('SELECT COUNT(1) FROM `performer`');
            $countPerformer = $countPerformer->fetch();
            $countAlbum = $db->query('SELECT COUNT(1) FROM `album`');
            $countAlbum = $countAlbum->fetch();
            $sql = "INSERT INTO `album_performer` (`album_id`,`performer_id`) VALUES";
            $arrValue = array();
            for ($i = 0; $i < $quantity; $i++) {
                $sql .= ' (?, ?),';
                $album_id = mt_rand(1, $countAlbum[0]);
                $performer_id = mt_rand(1, $countPerformer[0]);
                $arrValue = array_merge($arrValue, [$album_id, $performer_id]);
            }
            $sql = substr($sql, 0, -1);
            $stmt = $db->prepare($sql);
            $stmt->execute($arrValue);
        } catch (\Throwable $e) {
            echo $e;
        }
        return true;
    }

    //Узнаем колличество кортэжей в таблице

    /**
     * @param \PDO $db
     * @param $title
     * @return integer
     */
    private function getCountTable(\PDO $db, $title)
    {
        $count = $db->query('SELECT COUNT(1) FROM ' . $title);
        $count = $count->fetch();
        return $count[0];
    }

    //Генерируем строку случайных символов

    /**
     * @param int $length
     * @param int $set
     * @return string
     */
    private function generateRandomString($length = 10, $set = 1)
    {
        switch ($set) {
            case 1:
                $characters = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case 2:
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            default:
                $characters = 'abcdefghijklmnopqrstuvwxyz';
                break;
        }
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}



