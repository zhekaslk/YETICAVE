<main>
    <nav class="nav">
        <ul class="nav__list container">
            <? foreach ($category as $value) { ?>
                <li class="nav__item">
                    <a href="all-lots.html"><?=$value["name"]; ?></a>
                </li>
            <? } ?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <? if (!$_GET['search'] OR !$lot) { ?>
            <h2>По вашему запросу ничего не найдено</h2>
        </section>
            <? }
            else { ?>
                <h2>Результаты поиска по запросу «<span><?=$_GET['search']; ?></span>»</h2>
        <ul class="lots__list">
            <? foreach ($lot as $value) { ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=$value['img'];?>" width="350" height="260" alt="Сноуборд">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=$value['category'];?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?=$value['id']; ?>"><?=$value['name'];?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?=format_sum($value['price']);?></span>
                            </div>
                            <div class="lot__timer timer">
                                <?=lot_timer(); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <? } ?>
        </ul>
        </section>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
            <li class="pagination-item pagination-item-active"><a>1</a></li>
            <li class="pagination-item"><a href="#">2</a></li>
            <li class="pagination-item"><a href="#">3</a></li>
            <li class="pagination-item"><a href="#">4</a></li>
            <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
        </ul>
            <? } ?>
    </div>
</main>

