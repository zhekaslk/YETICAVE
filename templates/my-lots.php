<main>
    <nav class="nav">
        <ul class="nav__list container">
            <? foreach ($category as $value): ?>
                <li class="nav__item">
                    <a href="all-lots.php?category=<?= $value["id"]; ?>"><?= $value["name"]; ?></a>
                </li>
            <? endforeach; ?>
        </ul>
    </nav>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <? foreach ($lot as $item): ?>
            <tr class="rates__item <?= $item["rate_style"]; ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= $item["img"]; ?>" width="54" height="40" alt="Сноуборд">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="lot.php?lot_id=<?= $item['id_lot']; ?>"><?= $item["name"]; ?></a></h3>
                        <? if ($item["state"] == 1): ?>
                            <p><?= $item["contact"]; ?></p>
                        <? endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?= $category[$item["id_category"]-1]["name"]; ?>
                </td>
                <td class="rates__timer">
                    <div class="timer <?= $item["timer_style"]; ?>"><?= $item["timer_status"]; ?></div>
                </td>
                <td class="rates__price">
                    <?= format_sum($item["bet"]); ?>
                </td>
                <td class="rates__time">
                    <?= $item["date"]; ?>
                </td>
            </tr>
            <? endforeach; ?>
        </table>
    </section>
</main>