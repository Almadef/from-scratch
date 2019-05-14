<?php

namespace Application\Controllers;

use Application\Core\Controller as Controller;

/**
 * Class ErrorController
 * @package Application\Controllers
 */
class ErrorController extends Controller
{
    /**
     * @param \Throwable $exception
     */
    public static function exceptionHandler(\Throwable $exception)
    {
        $url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        switch (get_class($exception)) {
            case \Application\Exceptions\Exception401::class:
                $exceptionMessage = '401 Url: ' . $url;
                $code = 401;
                break;
            case \Application\Exceptions\Exception403::class:
                $exceptionMessage = '403 Url: ' . $url;
                $code = 403;
                break;
            case \Application\Exceptions\Exception404::class:
                $exceptionMessage = '404 Url: ' . $url;
                $code = 404;
                break;
            default:
                $exceptionMessage = $exception->getMessage();
                $code = 500;
                break;
        }
        self::logErrorException(0, $exception->getFile(), $exception->getLine(), $exceptionMessage);
        self::viewError($code);
        die();
    }


    /**
     * @param $severity
     * @param $message
     * @param $file
     * @param $line
     * @throws \ErrorException
     */
    public static function errorHandler($severity, $message, $file, $line)
    {
        if ($severity != E_WARNING) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        } else {
            self::logErrorException(1, $file, $line, $message);
        }
    }


    /**
     * @param $numVar
     * @param $file
     * @param $line
     * @param $message
     */
    private static function logErrorException($numVar, $file, $line, $message)
    {
        $filePath = __DIR__ . '/../docker/log/php/error_log.txt';
        $strVar = '';
        switch ($numVar) {
            case 0:
                $strVar = 'Exceptions';
                break;
            case 1:
                $strVar = 'Error';
                break;
        }
        $current = $strVar . '. Time: ' . date('d-m-Y H:i:s',
                time()) . ' File: ' . $file . ' Line: ' . $line . ' Message: ' . $message . PHP_EOL;
        file_put_contents($filePath, $current, FILE_APPEND);
    }

    /**
     * @param $code
     */
    private static function viewError($code)
    {
        http_response_code($code);
        $controller = new Controller();
        $controller->view->generate('error/' . $code . '.php', 'Error');
    }
}