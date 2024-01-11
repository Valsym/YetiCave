<nav class="nav">
    <ul class="nav__list container">
        <?php
        foreach ($cats as $cat) { ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= $cat['name'] ?></a>
            </li>
        <?php } ?>
    </ul>
</nav>