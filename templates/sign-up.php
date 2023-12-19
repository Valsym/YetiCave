<nav class="nav">
    <ul class="nav__list container">
        <?php
        foreach ($cats as $cat) { ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?=$cat['name'] ?></a>
            </li>
        <?php } ?>
    </ul>
</nav>
<form class="form container form--invalid" action="../sign-up.php" method="post" autocomplete="off"> <!-- form
    --invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <?php $class_invalid = isset($error['email']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item <?=$class_invalid ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=$sign_up['email'] ?? ''; ?>">
        <span class="form__error"><?=$error['email'] ?></span>
    </div>
    <?php $class_invalid = isset($error['password']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item <?=$class_invalid ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?=$sign_up['password'] ?? ''; ?>">
        <span class="form__error"><?=$error['password'] ?></span>
    </div>
    <?php $class_invalid = isset($error['name']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item <?=$class_invalid ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=$sign_up['name'] ?? ''; ?>">
        <span class="form__error"><?=$error['name'] ?></span>
    </div>
    <?php $class_invalid = isset($error['message']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item <?=$class_invalid ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?=$sign_up['message'] ?? ''; ?></textarea>
        <span class="form__error"><?=$error['message'] ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>