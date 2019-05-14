<div class="container">
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-6">
                    <h2>Управление альбомами</h2>
                </div>
                <div class="col-sm-6">
                    <a href="#addAlbumModal" class="btn btn-success" data-toggle="modal"><span>Создать альбом</span></a>
                </div>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Обложка</th>
                <th>Название</th>
                <th>Рейтинг</th>
                <th>Исполнители</th>
                <th>Действие</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['albums'] as $row) :
                $coverPath = COVER_PATH . ($row->getCover() == '' ? 'no-img.jpg' : $row->getCover());
                $coverAlt = 'Обложка альбома ' . $row->getTitle(); ?>
                <tr>
                    <td><img
                                src="<?= $coverPath ?>"
                                alt="<?= $coverAlt ?>" width="100" height="100"></td>
                    <td><?= $row->getTitle() ?></td>
                    <td><?= $row->getRating() ?></td>
                    <td>
                        <?php foreach ($row->getPerformers() as $rowPerformers) {
                            echo $rowPerformers->getName() . '<br>';
                        } ?>
                    </td>
                    <td>
                        <p><a href="#" data-toggle="modal" data-target="#coverAlbumModal"
                              data-whatever="<?= $row->getId() . '_' . $coverPath . '_' . $coverAlt ?>"
                              class="btn btn-primary">Обложка</a></p>
                        <p><a href="#" data-toggle="modal" data-target="#updateAlbumModal"
                              data-whatever="<?= $row->getId() ?>" class="btn btn-warning">Изменить</a></p>
                        <form action="/albumSettings/deleteAlbum" method="POST"><input type="hidden" name="id"
                                                                                       value="<?= $row->getId() ?>"/>
                            <input type="submit" class="btn btn-danger" value="Удалить"></form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal HTML -->
<div id="addAlbumModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Создать альбом</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div id="errorMsgAdd">
                </div>
                <div class="form-group">
                    <label for="addTitleAlbum">Название</label>
                    <input type="text" id="addTitleAlbum" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="addPerformersAlbum">Исполнители</label>
                    <select size="7" id="addPerformersAlbum" multiple class="form-control">

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Отмена">
                <input type="submit" id="createBtn" class="btn btn-success" value="Создать">
            </div>
        </div>
    </div>
</div>

<!-- Update Modal HTML -->
<div id="updateAlbumModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Изменить альбом</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div id="errorMsgUpadte">
                </div>
                <input type="hidden" id="updateIdAlbum" value="">
                <div class="form-group">
                    <label for="updateTitleAlbum">Название</label>
                    <input type="text" id="updateTitleAlbum" class="form-control"
                           required>
                </div>
                <div class="form-group">
                    <label for="updatePerformersAlbum">Исполнители</label>
                    <select size="7" id="updatePerformersAlbum" multiple class="form-control">

                    </select>
                </div>
                <div id="updateTextError" class="text-danger">

                </div>
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Отмена">
                <input type="submit" id="updateBtn" class="btn btn-success" value="Изменить">
            </div>
        </div>
    </div>
</div>

<!-- Cover Modal HTML -->
<div id="coverAlbumModal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="/albumSettings/coverAlbum" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title">Изменить обложку</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <h5>Текущая обложка</h5>
                    <img id="coverPathAlbum" src="" alt="" width="200" height="200">
                    <h5>Загрузить новую</h5>
                    <input type="hidden" id='coverIdAlbum' name="id" value="">
                    <p><input type="file" name="picture"></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Отмена">
                    <input type="submit" class="btn btn-success" value="Загрузить">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('form').validate(JSON.parse('<?= $data['script']?>'));
</script>