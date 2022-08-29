<?php
/**
 * Шаблон добавления проекта
 *
 * @var $result array - данные формы
 */

$errors = $result["errors"];
?>
<h2 class="content__main-heading">Добавление проекта</h2>

<form class="form"  action="project" method="post" autocomplete="off">
    <div class="form__row">
        <label class="form__label" for="project_name">Название <sup>*</sup></label>

        <input
            class="form__input <?= isset($errors["name"]) ? "form__input--error" : ""; ?>"
            type="text"
            name="name"
            id="project_name"
            value="<?= $result["title"]; ?>"
            placeholder="Введите название проекта"
        >

        <?= include_template("error.php", [
            "field_name" => "name",
            "errors" => $errors,
        ]); ?>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
