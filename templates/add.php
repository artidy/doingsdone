<?php
/**
 * Шаблон добавления задачи
 *
 * @var $projects array<array{id:string, title:string}> - массив проектов
 * @var $result array - данные формы
 */

$errors = $result["errors"];
?>
<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form" action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
    <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>
        <input
            class="form__input <?=isset($errors["name"]) ? "form__input--error" : ""?>"
            type="text"
            name="name"
            id="name"
            value="<?=$result["title"]?>"
            placeholder="Введите название"
        >
        <?=include_template("error.php", [
            "field_name" => "name",
            "errors" => $errors,
        ]);?>
    </div>

    <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>

        <select
            class="form__input form__input--select <?=isset($errors["project"]) ? "form__input--error" : ""?>"
            name="project"
            id="project"
        >
            <?php foreach ($projects as $project): ?>
                <option
                    value="<?=$project["id"]?>"
                    <?=$project["id"] === $result["project_id"] ? "selected" : "" ?>
                >
                    <?=$project["title"]?>
                </option>
            <?php endforeach; ?>
        </select>
        <?=include_template("error.php", [
            "field_name" => "project",
            "errors" => $errors,
        ]);?>
    </div>

    <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>
        <input
            class="form__input form__input--date <?=isset($errors["date"]) ? "form__input--error" : ""?>"
            type="text"
            name="date"
            id="date"
            value=""
            placeholder="Введите дату в формате ГГГГ-ММ-ДД"
        >
        <?=include_template("error.php", [
            "field_name" => "date",
            "errors" => $errors,
        ]);?>
    </div>

    <div class="form__row">
        <label class="form__label" for="file">Файл</label>

        <div class="form__input-file <?=isset($errors["file"]) ? "form__input--error" : ""?>">
            <input
                class="visually-hidden"
                type="file"
                name="file"
                id="file"
                value=""
            >

            <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
            </label>
        </div>
        <?=include_template("error.php", [
            "field_name" => "file",
            "errors" => $errors,
        ]);?>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
