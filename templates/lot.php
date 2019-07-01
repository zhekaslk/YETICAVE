<section class="lot-item container">
    <h2><?= $lot['name']?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src=<?= $lot['img']?> width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot['category']?></span></p>
            <p class="lot-item__description"><?= $lot['message']?></p>
        </div>
        <div class="lot-item__right">
            <? if (isset($_SESSION["user"]) AND $_SESSION["user"]["id"] != $lot["id_author"]) {  ?>
            <div class="lot-item__state">
                <div class="lot-item__timer timer">
                    <?=$lot["end"]; ?>
                </div>
                <div class="lot-item__  cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?=format_sum($lot['price']); ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?=format_sum($lot['price'] + $lot['step']); ?></span>
                    </div>
                </div>
                <form class="lot-item__form <? empty($errors) ? "" : print "form--invalid" ?> action="/lot.php?lot_id=<?=$_GET["lot_id"];?>" method="post" >
                    <p class="lot-item__form-item">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="number" name="cost" placeholder=<?= $lot['price'] + $lot['step']?>>
                        <span><? isset($errors['cost']) ? print $errors['cost'] : "";?></span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            </div>
            <? } ?>
            <div class="history">
                <h3>История ставок (<span><?=count($rates); ?></span>)</h3>
                <table class="history__list">
                    <? foreach ($rates as $rate) { ?>
                    <tr class="history__item">
                        <td class="history__name"><?=$rate["name"]?></td>
                        <td class="history__price"><?=format_sum($rate["bet"])?></td>
                        <td class="history__time"><?=$rate["date_add"]?></td>
                    </tr>
                    <? } ?>
                </table>
            </div>
        </div>
    </div>
</section>