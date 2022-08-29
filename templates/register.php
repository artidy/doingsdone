<?php
/**
 * Шаблон регистрации
 * @var $result array - Данные формы регистрации
 */

$errors = $result["errors"];
?>
<section class="content__side">
    <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

    <a class="button button--transparent content__side-button" href="form-authorization.html">Войти</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Регистрация аккаунта</h2>

    <form class="form" action="register" method="post" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>
            <input
                class="form__input <?= isset($errors["email"]) ? "form__input--error" : "" ?>"
                type="text"
                name="email"
                id="email"
                value="<?= $result["email"]; ?>"
                placeholder="Введите e-mail"
            >
            <?= include_template("error.php", [
                "field_name" => "email",
                "errors" => $errors,
            ]); ?>
        </div>
        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>
            <input
                class="form__input <?= isset($errors["password"]) ? "form__input--error" : "" ?>"
                type="password"
                name="password"
                id="password"
                value="<?= $result["password"]; ?>"
                placeholder="Введите пароль"
            >
            <?= include_template("error.php", [
                "field_name" => "password",
                "errors" => $errors,
            ]); ?>
        </div>
        <div class="form__row">
            <label class="form__label" for="name">Имя <sup>*</sup></label>
            <input
                class="form__input <?= isset($errors["name"]) ? "form__input--error" : "" ?>"
                type="text"
                name="name"
                id="name"
                value="<?= $result["name"]; ?>"
                placeholder="Введите имя"
            >
            <?= include_template("error.php", [
                "field_name" => "name",
                "errors" => $errors,
            ]); ?>
        </div>

        <div class="form__row form__row--controls">
            <?php if (count($errors) > 0): ?>
                <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
            <?php endif; ?>

            <input class="button" type="submit" name="" value="Зарегистрироваться">
        </div>
    </form>
</main>
