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
<div class="container">
    <section class="lots">
        <?php
        if (0 === count($goods)) { ?>
        <h2>Ничего не найдено по вашему запросу «<span><?=$search ?></span>»</h2>
        <?php } else { ?>
        <h2>Результаты поиска по запросу «<span><?=$search ?></span>»</h2>
        <ul class="lots__list">
            <?php
            foreach ($goods as [$lot_id, $title, $cat_name, $start_price, $img, $date_finish]) { ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=$img ?>" width="350" height="260" alt="<?=$title ?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=$cat_name ?></span>
                    <h3 class="lot__title"><a class="text-link" href="/lot.php?<?=$lot_id ?>"><?=$title ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=$start_price ?><b class="rub">р</b></span>
                        </div>
                        <?php
                        [$hours, $minutes] = get_dt_range($date_finish);
                        $finish = $hours < 1 ? 'timer--finishing' : '';
                        ?>
                        <div class="lot__timer timer">
                            <?= "$hours:$minutes" ?><!-- 16:54:12 -->
                        </div>
                    </div>
                </div>
            </li>
            <?php } ?>
        </ul>
        <?php } ?>
    </section>
    <?php
    if($page_count > 1) : ?>
    <ul class="pagination-list">
        <?php
        $prev = $curr_page - 1;
        $next = $curr_page + 1;
        if ($curr_page >= 2) :
        ?>
        <li class="pagination-item pagination-item-prev"><a href="/search.php?search=<?=$search ?>&page=<?=$prev ?>">Назад</a></li>
        <?php
        endif;
        $active = 'pagination-item-active';
        if($page_count > $curr_page)
            foreach ($pages as $page) : ?>
            <li class="pagination-item <?=((int)$page === (int)$curr_page) ? $active : ''; ?>">
                <a href="/search.php?search=<?=$search ?>&page=<?=$page ?>"><?=$page ?></a></li>
            <?php endforeach;
        if ($curr_page < $page_count) : ?>
        <li class="pagination-item pagination-item-next"><a href="/search.php?search=<?=$search?> &page=<?=$next ?>">Вперед</a></li>
        <?php endif; ?>
    </ul>
    <?php endif; ?>
</div>