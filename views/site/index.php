<div class="album py-5">
    <div class="container">
        <div class="row equal">
            <?php foreach ($data['albums'] as $row) : ?>
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3 col-xl-3">
                    <div class="card mb-3 box-shadow album-index">
                        <img class="card-img-top img-fluid"
                             src="<?= COVER_PATH . ($row->getCover() == '' ? 'no-img.jpg' : $row->getCover()) ?>"
                             alt="Обложка альбома <?= $row->getTitle() ?>" width="100" height="100">
                        <div class="card-body">
                            Название: <?= $row->getTitle() ?> </br>
                            Исполнители:
                            <?php foreach ($row->getPerformers() as $rowPerformers): ?>
                                <a href="/site/searchAlbumsPerformer?idPerformer=<?= $rowPerformers->getId()?>">
                                    <?= $rowPerformers->getName()?>
                                </a>
                            <?php endforeach; ?>
                            <br>
                            Рейтинг: <?= $row->getRating() ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php if($data['lastPage']!=1): ?>
        <div class="container">
            <div class="row justify-content-md-center">
                <nav class="col-md-auto">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link"
                               href="/site/index?page=<?= ($data['pageNum'] - 1 > 0) ? $data['pageNum'] - 1 : '1'; ?>"
                               aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Назад</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $data['lastPage']; $i++) { ?>
                            <li class="page-item <?= ($i == $data['pageNum']) ? 'active' : '' ?>"><a class="page-link"
                                                                                                     href="/site/index?page=<?= $i; ?>"><?= $i; ?></a>
                            </li>
                        <?php } ?>
                        <li class="page-item">
                            <a class="page-link"
                               href="/site/index?page=<?= ($data['pageNum'] + 1 <= $data['lastPage']) ? $data['pageNum'] + 1 : $data['lastPage']; ?>"
                               aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Дальше</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    <?php endif; ?>
</div>