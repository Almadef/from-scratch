<div class="text-center">
    <h3>Создать альбом</h3>
    <form enctype="multipart/form-data" method="POST" class="centered-form" id="albumForm" novalidate>
        <div id="errorMsg"><?php if (($data['error']['msg'] !== '')&&($data['error']['div'] == 'errorMsg')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" align="left">
                    <?= $data['error']['msg'] ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            <?php endif ?></div>
        <p>
            <label for="title">Название</label>
            <input type="text" class="form-control" id="title" placeholder="Название"  name="title" maxlength="50" />
        </p>
        <p>
            <label for="performers">Исполнители</label>
            <select size="7" multiple name="performers[]" class="form-control" id="performers">
                <?php
                foreach ($data['performers'] as $row) {
                    echo '<option value="' . $row->getId() . '">' . $row->getName() . '</option>';
                }
                ?>
            </select>
        </p>
        <input type="submit" class="btn btn-success" value="Создать">
    </form>
    <p><a href="/albumSettings/userAlbums" class="btn btn-secondary">Назад</a></p>
</div>
<script>
    $('form').validate(JSON.parse('<?= $data['script']?>'));
</script>