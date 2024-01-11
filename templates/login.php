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

<form class="form container" action="../login.php" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <?php $class_invalid = isset($error['email']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item <?= $class_invalid ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $login['email'] ?? ''; ?>">
        <span class="form__error"><?= $error['email'] ?></span>
    </div>
    <?php $class_invalid = isset($error['password']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item form__item--last <?= $class_invalid ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль"
               value="<?= $login['password'] ?? ''; ?>">
        <span class="form__error"><?= $error['password'] ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>