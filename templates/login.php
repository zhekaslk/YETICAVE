<main>
    <nav class="nav">
        <ul class="nav__list container">
            <? foreach ($category as $value): ?>
                <li class="nav__item">
                    <a href="all-lots.php?category=<?= $value["id"]; ?>"><?= $value["name"]; ?></a>
                </li>
            <? endforeach; ?>
        </ul>
    </nav>
    <form class="form container <?= empty($errors) ? "" : "form--invalid" ?>" action="/login.php" method="post" > <!-- form--invalid -->
        <h2>Вход</h2>
        <? if (isset($errors["auth"])): ?>
            <span class="form__error form__error--bottom"><?= $errors["auth"]; ?></span>
        <? endif; ?>
        <? if (isset($errors["email"]) || isset($errors["password"])): ?>
            <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <? endif; ?>
        <div class="form__item <?= isset($errors["email"])  ? "form__item--invalid" : "" ?>"> <!-- form__item--invalid -->
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= isset($auth['email']) ? $auth['email'] : ''?>" >
            <span class="form__error"><?= $errors['email']; ?></span>
        </div>
        <div class="form__item form__item--last <?= isset($errors["password"]) ? "form__item--invalid" : "" ?>">
            <label for="password">Пароль*</label>
            <input id="password" type="text" name="password" placeholder="Введите пароль" >
            <span class="form__error"><?= $errors['password']; ?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>