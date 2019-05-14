<?php

namespace Application\Modules;

/**
 * Class UploadIMG
 */
class UploadFile
{
    /**
     * @param $file
     * @param $tmp_path
     * @param int $type
     * @param null $rotate
     * @param int $quality
     * @return string $file['name']
     */
    public function resize($file, $tmp_path, $type = 1, $rotate = null, $quality = 100)
    {
        $sizeAlbum = 400;
        // Cоздаём исходное изображение на основе исходного файла
        if ($file['type'] == 'image/jpeg') {
            $source = imagecreatefromjpeg($file['tmp_name']);
        } elseif ($file['type'] == 'image/png') {
            $source = imagecreatefrompng($file['tmp_name']);
        } elseif ($file['type'] == 'image/gif') {
            $source = imagecreatefromgif($file['tmp_name']);
        } else {
            return false;
        }

        // Поворачиваем изображение
        if ($rotate != null) {
            $src = imagerotate($source, $rotate, 0);
        } else {
            $src = $source;
        }

        // Определяем ширину и высоту изображения
        $w_src = imagesx($src);
        $h_src = imagesy($src);

        // В зависимости от типа (обложка или большое изображение) устанавливаем ограничение по ширине.
        if ($type == 1) {

            $w_dest = $sizeAlbum;
            $h_dest = $sizeAlbum;

            // Создаём пустую картинку
            $dest = imagecreatetruecolor($w_dest, $h_dest);

            // Копируем старое изображение в новое с изменением параметров
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

            // Вывод картинки и очистка памяти
            imagejpeg($dest, $tmp_path . $file['name'], $quality);
            imagedestroy($dest);
            imagedestroy($src);

            return $file['name'];
        } elseif ($type == 2) {
            // Вывод картинки и очистка памяти
            imagejpeg($src, $tmp_path . $file['name'], $quality);
            imagedestroy($src);

            return $file['name'];
        }
    }

    /**
     * @param $type
     * @return string
     */
    public function newNameImg($type)
    {
        return $name = md5(microtime() . rand(0, 9999)) . '.' . explode("/", $type)[1];
    }
}



