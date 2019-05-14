<div class="text-center">
    <p>
    <div class="pb-3">
        Логин: <?= $data['user']->getLogin() ?>
            <form class="centered-form" method="POST" id="updateLoginForm" novalidate>
                <div id="errorUpdateLoginMsg"><?php if (($data['error']['updateLoginMsg'] !== '') && ($data['error']['div'] == 'errorUpdateLoginMsg')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" align="left">
                            <?= $data['error']['updateLoginMsg'] ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    <?php endif ?></div>
                <label for="newLogin" class="sr-only">Новый логин</label>
                <input type="text" id="newLogin" class="form-control" name="newLogin" placeholder="Новый логин"
                       value="" max="50" required>
                <label for="password" class="sr-only">Пароль</label>
                <input type="password" id="password" class="form-control" name="password" placeholder="Пароль"
                       max="50" required>
                <input type="submit" class="btn btn-success" value="Изменить">
            </form>
    </div>

    <div class="pb-3">
        Email: <?= $data['user']->getEmail() ?>
            <form class="centered-form" method="POST" id="updateEmailForm" novalidate>
                <div id="errorUpdateEmailMsg"><?php if (($data['error']['updateEmailMsg'] !== '') && ($data['error']['div'] == 'errorUpdateEmailMsg')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" align="left">
                            <?= $data['error']['updateEmailMsg'] ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    <?php endif ?></div>
                <label for="newEmail" class="sr-only">Новый email</label>
                <input type="email" id="newEmail" class="form-control" name="newEmail" placeholder="Новый email"
                       value="" max="50" required>
                <label for="password" class="sr-only">Пароль</label>
                <input type="password" id="password" class="form-control" name="password" placeholder="Пароль"
                       max="50" required>
                <input type="submit" class="btn btn-success" value="Изменить">
            </form>
    </div>

    <div class="pb-3">
        Изменить пароль
            <form class="centered-form" method="POST" id="updatePasswordForm" novalidate>
                <div id="errorUpdatePasswordMsg"><?php if (($data['error']['updatePasswordMsg'] !== '') && ($data['error']['div'] == 'errorUpdatePasswordMsg')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" align="left">
                            <?= $data['error']['updatePasswordMsg'] ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    <?php endif ?></div>
                <label for="newPassword" class="sr-only">Новый пароль</label>
                <input type="password" id="newPassword" class="form-control" name="newPassword"
                       placeholder="Новый пароль" max="50" required>
                <label for="repeatNewPassword" class="sr-only">Повторите новый пароль</label>
                <input type="password" id="repeatNewPassword" class="form-control" name="repeatNewPassword"
                       placeholder="Повторите новый пароль" max="50" required>
                <label for="oldPassword" class="sr-only">Старый пароль</label>
                <input type="password" id="oldPassword" class="form-control" name="oldPassword"
                       placeholder="Старый пароль" max="50" required>
                <input type="submit" class="btn btn-success" value="Изменить">
            </form>
    </div>
    </p>
</div>
<script>
    $('form').validate(JSON.parse('<?= $data['script']?>'));
</script>
