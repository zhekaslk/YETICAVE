<main>
    <nav class="nav">
        <ul class="nav__list container">
            <li class="nav__item">
                <a href="all-lots.html">Доски и лыжи</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Крепления</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Ботинки</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Одежда</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Инструменты</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Разное</a>
            </li>
        </ul>
    </nav>
    <form enctype="multipart/form-data" class="form form--add-lot  container <? empty($errors) ? print "" : print "form--invalid" ?> action="/add.php" method="post" > <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <? isset($errors["lot-name"]) ? print "form__item--invalid" : print "" ?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование</label>
                <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<? isset($add_lot['lot-name']) ? print $add_lot['lot-name'] : print ''?>">
                <span class="form__error"> <?=$errors['lot-name']  ?></span>
            </div>
            <div class="form__item <? isset($errors["category"]) ? print "form__item--invalid" : print "" ?>"">
                <label for="category">Категория</label>
                <select id="category" name="category"  >
                    <option <? if (isset($add_lot["category"])) { print $add_lot["category"]; }?> value="" selected disabled hidden>Выберите категорию</option>
                    <? foreach ($category as $value) { ?>
                       <option><?=$value; ?> </option>
                     <? } ?>
                </select>
                <span class="form__error"><?=$errors['category']; ?></span>
            </div>
        </div>
        <div class="form__item form__item--wide <? isset($errors["message"]) ? print "form__item--invalid" : print "" ?>"">
            <label for="message">Описание</label>
            <textarea id="message" name="message" placeholder="Напишите описание лота"><? isset($add_lot['message']) ? print $add_lot['message'] : print ''?></textarea>
            <span class="form__error"><?=$errors['message']; ?></span>
        </div>
        <div class="form__item form__item--file  <? isset($errors["file"]) ? print "form__item--invalid" : print "" ?>""> <!-- form__item--uploaded -->
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="lot-img"  id="photo2" value="">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
            <span class="form__error"><?=$errors['file']; ?></span>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small <? isset($errors["lot-rate"]) ? print "form__item--invalid" : print "" ?>"">
                <label for="lot-rate">Начальная цена</label>
                <input id="lot-rate"  name="lot-rate" placeholder="0"  value="<? isset($add_lot['lot-rate']) ? print $add_lot['lot-rate'] : print ''?>">
                <span class="form__error"><?=$errors['lot-rate']; ?></span>
            </div>
            <div class="form__item form__item--small <? isset($errors["lot-step"]) ? print "form__item--invalid" : print "" ?>"">
                <label for="lot-step">Шаг ставки</label>
                <input id="lot-step"  name="lot-step" placeholder="0" value="<? isset($add_lot['lot-step']) ? print $add_lot['lot-step'] : print ''?>" >
                <span class="form__error"><?=$errors['lot-step']; ?></span>
            </div>
            <div class="form__item <? isset($errors["lot-date"]) ? print "form__item--invalid" : print "" ?>"">
                <label for="lot-date">Дата окончания торгов</label>
                <input class="form__input-date" id="lot-date" type="date" name="lot-date" value="<?=$add_lot['lot-date']; ?>" >
                <span class="form__error"><?=$errors['lot-date']; ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
