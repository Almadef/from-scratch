<?php

require_once 'autoloader.php';
include __DIR__ . '/../Settings/Path.php';

$loader = new Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace(
    'Application',
    __DIR__ . '/../'
);

set_error_handler("\Application\Controllers\ErrorController::errorHandler");
set_exception_handler('\Application\Controllers\ErrorController::exceptionHandler');

\Application\Core\Route::start();
