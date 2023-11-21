<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--заполните этот список из массива категорий-->
        <?php
        foreach ($cats as $key => $catName) { ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><?= $catName ?></a>
            </li>
        <?php } ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->
        <?php
        foreach ($lots as [$lotName, $catName, $price, $img, $timeFinish]) { ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=$img ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= $catName ?></span>
                    <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?= htmlspecialchars($lotName) ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount"><?= $price ?></span>
                            <span class="lot__cost"><?= numberSum(htmlspecialchars($price)) ?></span>
                        </div>
                        <?php
                        [$hours, $minutes] = getDtRange($timeFinish);
                        $finish = $hours < 1 ? 'timer--finishing' : '';
                        ?>
                        <div class="lot__timer timer <?= $finish ?>">
                            <?= "$hours:$minutes" ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php } ?>
    </ul>
</section>