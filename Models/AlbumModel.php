<?php

namespace Application\Models;

use Application\Core\Model as Model;
use Application\Modules\UploadFile as UploadFile;

/**
 * Class AlbumModel
 * @package Application\Models
 */
class AlbumModel extends Model
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $title;
    /**
     * @var
     */
    private $cover;
    /**
     * @var
     */
    private $rating;
    /**
     * @var
     */
    private $user_id;
    /**
     * @var
     */
    private $performers;

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }


    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $cover
     */
    public function setCover(string $cover)
    {
        $this->id = $cover;
    }

    /**
     * @return string
     */
    public function getCover(): string
    {
        return $this->cover;
    }

    /**
     * @param int $rating
     */
    public function setRating(int $rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;
    }


    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param $performers
     */
    public function setPerformers(array $performers)
    {
        $this->performers = $performers;
    }


    /**
     * @return mixed
     */
    public function getPerformers(): array
    {
        return $this->performers;
    }

    /**
     * @param array $albums
     * @return array
     */
    public function coversExists(array $albums): array
    {
        foreach ($albums as &$album) {
            $album->coverExists($album);
        }
        return $albums;
    }

    /**
     * @param AlbumModel $album
     * @return AlbumModel
     */
    public function coverExists(\Application\Models\AlbumModel $album): \Application\Models\AlbumModel
    {
        if (!(file_exists(COVER_PATH_FOR_PHP . $album->cover))) {
            $album->cover = '';
        }
        return $album;
    }

    /**
     * @return array
     */
    public function uploadCover(): array
    {
        $insertImg = new UploadFile();
        // Пути загрузки файлов
        $path = COVER_PATH_FOR_PHP;
        $tmp_path = $path . 'tmp/';
        // Массив допустимых значений типа файла
        $types = array('image/gif', 'image/png', 'image/jpeg');
        // Максимальный размер файла
        $size = 1024000 * 10;

        // Обработка запроса
        if (isset($_FILES['picture'])) {
            // Проверяем тип файла
            if (!in_array($_FILES['picture']['type'], $types)) {
                return ['error' => 'Запрещённый тип файла.'];
            }

            // Проверяем размер файла
            if ($_FILES['picture']['size'] > $size) {
                return ['error' => 'Слишком большой размер файла.'];
            }

            $name = $insertImg->resize($_FILES['picture'], $tmp_path);
            $nameOut = $insertImg->newNameImg($_FILES['picture']['type']);

            // Загрузка файла и вывод сообщения
            if (!@copy($tmp_path . $name, $path . $nameOut)) {
                // Удаляем временный файл
                unlink($tmp_path . $name);
                return ['error' => 'Не удалось сохранить обложку на сервере'];
            } else {
                // Удаляем временный файл
                unlink($tmp_path . $name);
                return ['error' => '', 'nameOut' => $nameOut];
            }
        }
    }
}
