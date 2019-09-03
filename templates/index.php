<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <? foreach ($category as $value): ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="all-lots.php?category=<?= $value["id"]; ?>"><?= $value["name"]; ?></a>
        <? endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>

    <? foreach ($product as $value): ?>
        <ul class="lots__list">
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $value['img']; ?>" width="350" height="260" alt="Сноуборд">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= $value['category']; ?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?= $value['id']; ?>"><?= $value['name']; ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= format_sum($value['price']); ?> </span>
                        </div>
                        <div class="lot__timer timer">
                            <?= lot_timer($value["timediff"]); ?>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <? endforeach; ?>
</section>