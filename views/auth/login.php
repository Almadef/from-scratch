<div class="text-center">
    <form class="centered-form" method="POST" id="loginForm" novalidate>
        <h1 class="h3 mb-3 font-weight-normal">Вход</h1>
        <div id="errorMsg"><?php if (($data['error']['msg'] !== '')&&($data['error']['div'] == 'errorMsg')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" align="left">
                    <?= $data['error']['msg'] ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            <?php endif ?></div>
        <label for="login" class="sr-only">Логин</label>
        <input type="text" id="login" class="form-control" name="login" placeholder="Логин"
               value="<?= $data['login'] !== '' ?  $data['login'] : ""; ?>" required autofocus>
        <label for="password" class="sr-only">Пароль</label>
        <input type="password" id="password" class="form-control" name="password" placeholder="Пароль" required>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Войти">
    </form>
</div>
<script>
    $('form').validate(JSON.parse('<?= $data['script']?>'));
</script>