<?php
/**
 * Шаблон регистрации
 * @var $result array - Данные формы авторизации
 */

$errors = $result["errors"];
?>
<section class="content__side">
    <p class="content__side-info">Если у вас нет аккаунта, зарегистрируйтесь на сайте</p>

    <a class="button button--transparent content__side-button" href="register">Зарегистрироваться</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Вход на сайт</h2>

    <form class="form" action="auth" method="post" autocomplete="off">
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

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Войти">
        </div>
    </form>
</main>
