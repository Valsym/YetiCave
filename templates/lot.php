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
<section class="lot-item container">
    <?php
    [
        $lot_id,
        $lot_title,
        $lot_desc,
        $cat_name,
        $start_price,
        $img,
        $time_finish,
        $step,
        $author_id
    ] = $lot; ?>
    <h2><?= $lot_title ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $img ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= $cat_name ?></span></p>
            <p class="lot-item__description"><?= $lot_desc ?></p>
        </div>
        <?php
        [$hours, $minutes] = get_dt_range($time_finish);
        $finish = $hours < 1 ? 'timer--finishing' : '';
        ?>
        <?php if ($is_auth) { ?>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer <?= $finish ?>">
                        <?= "$hours:$minutes" ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= format_num($curr_price) ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= format_num($curr_price + $step) ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="/lot.php?lot=<?= $lot_id ?>" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="12 000">
                            <span class="form__error"><?= $error ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
                <?php
                if (($count = count($history_bets)) > 0) :
                    ?>
                    <div class="history">
                        <h3>История ставок (<span><?= $count ?></span>)</h3>
                        <?php foreach ($history_bets as $history) : ?>
                            <table class="history__list">
                                <tr class="history__item">
                                    <td class="history__name"><?= $history['user_name'] ?? ''; ?></td>
                                    <td class="history__price"><?= format_num($history['price_bet']) ?? ''; ?></td>
                                    <td class="history__time"><?= $history['date_bet'] ?? ''; ?></td>
                                </tr>
                            </table>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php } ?>
    </div>
</section>

