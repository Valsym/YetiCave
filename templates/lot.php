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
    [$lot_title, $lot_desc, $cat_name, $start_price,
    $img, $time_finish, $step] = $lot; ?>
    <h2><?=$lot_title ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?=$img ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?=$cat_name ?></span></p>
            <p class="lot-item__description"><?=$lot_desc ?></p>
        </div>
        <?php
        [$hours, $minutes] = getDtRange($time_finish);
        $finish = $hours < 1 ? 'timer--finishing' : '';
        ?>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <div class="lot-item__timer timer timer--finishing">
                    <?= "$hours:$minutes" ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?=$start_price ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?=$start_price+$step ?> р</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

