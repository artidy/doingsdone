<?php
/**
 * Шаблон добавления задачи
 *
 * @var $projects array<array{id:string, title:string}> - массив проектов
 * @var $tasks array<array{id:string, title:string, deadline:string,
 *     project:string, is_complited:bool, project_id:string, file_path:string}> - массив задач по проектам
 * @var $show_complete_tasks bool - статус отображения выполненных задач
 * @var $project_id string - идентификатор выбранного проекта
 */
?>
<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form" action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
    <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>

        <input class="form__input" type="text" name="name" id="name" value="" placeholder="Введите название">
    </div>

    <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>

        <select class="form__input form__input--select" name="project" id="project">
            <?php foreach ($projects as $project): ?>
                <option value=<?=$project["id"]?>><?=$project["title"]?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>

        <input class="form__input form__input--date" type="text" name="date" id="date" value="" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
    </div>

    <div class="form__row">
        <label class="form__label" for="file">Файл</label>

        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="file" id="file" value="">

            <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
            </label>
        </div>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
