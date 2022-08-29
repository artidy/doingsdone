<?php
/**
 * Шаблон списка задач
 *
 * @var $projects array<array{id:string, title:string}> - массив проектов
 * @var $tasks array<array{id:string, title:string, deadline:string,
 *     project:string, is_complited:bool, project_id:string, file_path:string}> - массив задач по проектам
 * @var $show_complete_tasks bool - статус отображения выполненных задач
 * @var $project_id string - идентификатор выбранного проекта
 * @var $search string - поисковой запрос
 * @var $filter string - текущий фильтр задач
 * @var $project_id_param string - строка запроса для проектов
 * @var $completed_param string - строка запроса для отображения завершенных проектов
 * @var $filter_param string - строка запроса фильтров
 * @var $search_param string - строка запроса поиска
 */
?>
<h2 class="content__main-heading">Список задач</h2>

<form
    class="search-form"
    action="/"
    method="get"
    autocomplete="off"
>
    <input class="visually-hidden" type="text" name="project_id" value="<?= $project_id; ?>">
    <input class="visually-hidden" type="text" name="show_completed" value="<?= $show_complete_tasks; ?>">
    <input class="visually-hidden" type="text" name="filter" value="<?= $filter; ?>">
    <input
        class="search-form__input"
        type="text"
        id="search"
        name="search"
        value="<?= $search; ?>"
        placeholder="Поиск по задачам"
    >

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="<?= getQueryParams([$project_id_param, $completed_param, $search_param]); ?>"
           class="tasks-switch__item <?= $filter === "" ? "tasks-switch__item--active" : ""; ?>">
            Все задачи
        </a>
        <a href="<?= getQueryParams([$project_id_param, $completed_param, "filter=today", $search_param]); ?>"
           class="tasks-switch__item <?= $filter === "today" ? "tasks-switch__item--active" : ""; ?>">
            Повестка дня
        </a>
        <a href="<?= getQueryParams([$project_id_param, $completed_param, "filter=tomorrow", $search_param]); ?>"
           class="tasks-switch__item <?= $filter === "tomorrow" ? "tasks-switch__item--active" : ""; ?>">
            Завтра
        </a>
        <a href="<?= getQueryParams([$project_id_param, $completed_param, "filter=overdue", $search_param]); ?>"
           class="tasks-switch__item <?= $filter === "overdue" ? "tasks-switch__item--active" : ""; ?>">
            Просроченные
        </a>
    </nav>

    <label class="checkbox">
        <input
            class="checkbox__input visually-hidden show_completed"
            type="checkbox"
            <?=$show_complete_tasks ? "checked" : ""; ?>
        >
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php if (count($tasks) === 0 && $search !== ""): ?>
        Ничего не найдено по вашему запросу
    <?php endif;

    if (count($tasks) === 0 && $search === ""): ?>
    Нет ни одной задачи
    <?php endif;

    foreach ($tasks as $task):
        if ($task["is_completed"] && !$show_complete_tasks ||
            $project_id !== "" && $task["project_id"] !== $project_id) {
            continue;
        }

        $restOfTime = getRestOfTime($task["deadline"]);
        ?>
        <tr
            class="tasks__item task
                    <?=$task["is_completed"] ? "task--completed" : ""; ?>
                    <?=!$task["is_completed"] && $restOfTime && $restOfTime <= 24 ? "task--important" : ""; ?>"
        >
            <td class="task__select">
                <form action="status" method="post">
                    <label class="checkbox task__checkbox">
                        <input
                            class="checkbox__input visually-hidden"
                            type="checkbox"
                            name="status"
                            onchange="submit()"
                            <?=$task["is_completed"] ? "checked" : ""; ?>
                        >
                        <span class="checkbox__text"><?=htmlspecialchars($task["title"]); ?></span>
                    </label>
                    <input
                        class="visually-hidden"
                        type="text"
                        name="task_id"
                        value="<?= $task["id"]; ?>"
                    >
                </form>
            </td>
            <td class="task__file">
                <?php if ($task["file_path"]): ?>
                    <a class="download-link" href="<?=$task["file_path"]?>" download></a>
                <?php endif; ?>
            </td>
            <td class="task__date"><?=htmlspecialchars($task["deadline"]); ?></td>

            <td class="task__controls">
            </td>
        </tr>
    <?php endforeach; ?>
</table>
