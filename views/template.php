<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?= $titleForm ?></title>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/validate/validate.js"></script>
    <script src="/js/modal.js" defer></script>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">musicAlbums</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07"
                aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExample07">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Главная</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if ($login != '') { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownProfile"
                           data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false"><?= $_SESSION['login'] ?></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownProfile">
                            <a class="dropdown-item" href="/albumSettings/userAlbums">Мои альбомы</a>
                            <a class="dropdown-item" href="/userSettings/changeUser">Настройки</a>
                            <a class="dropdown-item" href="/auth/logout">Выйти</a>
                        </div>
                    </li>
                <?php } else { ?>
                    <li class="nav-item"><a class="nav-link" href="/auth/signup">Регистрация</a></li>
                    <li class="nav-item"><a class="nav-link" href="/auth/login">Вход</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<?php
include VIEW_PATH_FOR_PHP . $content_view;
?>
</body>
</html>