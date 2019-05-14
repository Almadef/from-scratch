<?php

namespace Application\Core;

/**
 * Class View
 * @package Application\Core
 */
class View
{
    /**
     * @param $content_view
     * @param $titleForm
     * @param null $data
     * @param string $template_view
     */
    public function generate($content_view, $titleForm, $data = null, $template_view = 'template.php')
    {
        (isset($_SESSION['login'])) ? $login=$_SESSION['login'] : $login='';
        include VIEW_PATH_FOR_PHP . $template_view;
    }
}
