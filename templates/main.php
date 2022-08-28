<?php
/**
 * Шаблон главной страницы сайта
 *
 * @var $projects array<string> - массив проектов
 * @var $tasks array<array{title:string, deadline:string, project:string, is_complited:bool}> - массив задач по проектам
 * @var $show_complete_tasks bool - статус отображения выполненных задач
 */
?>
<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $project): ?>
                <li class="main-navigation__list-item">
                    <a class="main-navigation__list-item-link" href="#"><?=htmlspecialchars($project); ?></a>
                    <span class="main-navigation__list-item-count"><?=getProjectTaskCount($project, $tasks); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button"
       href="pages/form-project.html" target="project_add">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post" autocomplete="off">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
            <a href="/" class="tasks-switch__item">Повестка дня</a>
            <a href="/" class="tasks-switch__item">Завтра</a>
            <a href="/" class="tasks-switch__item">Просроченные</a>
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
        <?php foreach ($tasks as $task):
            if ($task["is_completed"] && !$show_complete_tasks) {
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
                    <label class="checkbox task__checkbox">
                        <input
                            class="checkbox__input visually-hidden"
                            type="checkbox"
                            <?=$task["is_completed"] ? "checked" : ""; ?>
                        >
                        <span class="checkbox__text"><?=htmlspecialchars($task["title"]); ?></span>
                    </label>
                </td>
                <td class="task__date"><?=htmlspecialchars($task["deadline"]); ?></td>

                <td class="task__controls">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
