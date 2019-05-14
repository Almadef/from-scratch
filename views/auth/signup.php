<div class="text-center">
    <form class="centered-form" method="POST" id="signupForm" novalidate>
        <h1 class="h3 mb-3 font-weight-normal">Регистрация</h1>
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
        <label for="email" class="sr-only">Email</label>
        <input type="email" id="email" class="form-control" name="email" placeholder="Email"
               value="<?= $data['email'] !== '' ?  $data['email'] : ""; ?>" required>
        <label for="password" class="sr-only">Пароль</label>
        <input type="password" id="password" class="form-control" name="password" placeholder="Пароль" required>
        <label for="repeat_password" class="sr-only">Повторите пароль</label>
        <input type="password" id="repeat_password" class="form-control" name="repeat_password"
               placeholder="Повторите пароль" required>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Зарегистрироваться">
    </form>
</div>
<script>
    $('form').validate(JSON.parse('<?= $data['script']?>'));
</script>