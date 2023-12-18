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
<form class="form form--add-lot container form--invalid" action="../add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <?php $class_invalid = isset($error['lot-name']) ? 'form__item--invalid' : ''; ?>
        <div class="form__item <?=$class_invalid ?>"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=$lot['lot-name'] ?? ''; ?>">
            <span class="form__error"><?=$error['lot-name'] ?></span>
        </div>
        <?php $class_invalid = isset($error['category']) ? 'form__item--invalid' : ''; ?>
        <div class="form__item <?=$class_invalid ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <option>Выберите категорию</option>
                <?php foreach ($cats as $cat) { ?>
                <option value="<?=$cat['id'] ?>"><?=$cat['name'] ?></option>
                <?php } ?>
            </select>
            <span class="form__error"><?=$error['category']; ?></span>
        </div>
    </div>
    <?php $class_invalid = isset($error['message']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item form__item--wide <?=$class_invalid ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?=$lot['message'] ?? ''; ?></textarea>
        <span class="form__error"><?=$error['message'] ?></span>
    </div>
    <?php $class_invalid = isset($error['lot_img']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item form__item--file <?=$class_invalid ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="lot-img" value="" name="lot_img">
            <label for="lot-img">
                Добавить
            </label>
        </div>
        <span class="form__error"><?=$error['lot_img'] ?></span>
    </div>
    <div class="form__container-three">
        <?php $class_invalid = isset($error['lot-rate']) ? 'form__item--invalid' : ''; ?>
        <div class="form__item form__item--small <?=$class_invalid ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=$lot['lot-rate'] ?? ''; ?>">
            <span class="form__error"><?=$error['lot-rate'] ?></span>
        </div>
        <?php $class_invalid = isset($error['lot-step']) ? 'form__item--invalid' : ''; ?>
        <div class="form__item form__item--small <?=$class_invalid ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=$lot['lot-step'] ?? ''; ?>">
            <span class="form__error"><?=$error['lot-step'] ?></span>
        </div>
        <?php $class_invalid = isset($error['lot-date']) ? 'form__item--invalid' : ''; ?>
        <div class="form__item <?=$class_invalid ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=$lot['lot-date'] ?? ''; ?>">
            <span class="form__error"><?=$error['lot-date'] ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>