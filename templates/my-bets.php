<nav class="nav">
    <ul class="nav__list container">
        <?php
        foreach ($cats as $cat_came) { ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= $cat_came['name'] ?></a>
            </li>
        <?php } ?>
    </ul>
</nav>
<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($bets as $bet) {
            $win_class = $bet['winner_id'] ? 'rates__item--win' : '';
            $contacts = $bet['contacts'] ?? '';
            ?>
            <tr class="rates__item <?= $win_class ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="../uploads/rate<?= $bet['id'] ?>.jpg" width="54" height="40" alt="Сноуборд">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="/lot.php?lot=<?= $bet['id'] ?>"><?= $bet['title'] ?></a></h3>
                        <?php if ($contacts) : ?>
                            <p><?= $contacts ?></p>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?= $bet['cat_name'] ?>
                </td>
                <td class="rates__timer">
                    <?php
                    [$hours, $minutes] = get_dt_range($bet['date_finish']);
                    $finish = $hours < 24 ? 'timer--finishing' : '';
                    if ($win_class) : ?>
                        <div class="timer timer--win">Ставка выиграла</div>
                    <?php else : ?>
                        <div class="timer <?= $finish ?>"><?= "$hours:$minutes" ?></div>
                    <?php endif; ?>
                </td>
                <td class="rates__price">
                    <?= format_num($bet['price_bet']) ?>
                </td>
                <td class="rates__time">
                    <?= $bet['date_bet'] ?><!--5 минут назад-->
                </td>
            </tr>
        <?php } ?>
    </table>
</section>