<main>
    <nav class="nav">
        <ul class="nav__list container">
            <? foreach ($category as $value): ?>
                <li class="nav__item">
                    <a href=/category/<?= $value["name_eng"]; ?>><?= $value["name"]; ?></a>
                </li>
            <? endforeach; ?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <h2><?= $message; ?></h2>
            <? if (isset($lot)): ?>
            <ul class="lots__list">
                <? foreach ($lot as $value): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $value['img']; ?>" width="350" height="260" alt="Сноуборд">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $value['category']; ?></span>
                        <h3 class="lot__title"><a class="text-link" href="../lot/lot.php"><?= $value['name']; ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= format_sum($value['price']); ?></span>
                            </div>
                            <div class="lot__timer timer <?= $value["timer_style"]; ?>">
                                <?= $value["timer_status"]; ?>
                            </div>
                        </div>
                    </div>
                <? endforeach; ?>
            </ul>
        </section>
            <? else: ?>
                </section>
            <? endif; ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
            <li class="pagination-item pagination-item-active"><a>1</a></li>
            <li class="pagination-item"><a href="#">2</a></li>
            <li class="pagination-item"><a href="#">3</a></li>
            <li class="pagination-item"><a href="#">4</a></li>
            <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
        </ul>
    </div>
</main>