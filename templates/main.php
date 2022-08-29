<?php
/**
 * Шаблон главной страницы сайта
 *
 * @var $projects array<array{id:string, title:string}> - массив проектов
 * @var $tasks array<array{id:string, title:string, deadline:string,
 *     project:string, is_complited:bool, project_id:string, file_path:string}> - массив задач по проектам
 * @var $project_id string - идентификатор выбранного проекта
 * @var $main_content string - контент для основной страницы
 */
?>
<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $project): ?>
                <li class="main-navigation__list-item
                        <?=$project_id === $project["id"] ? "main-navigation__list-item--active" : "" ?>"
                >
                    <a
                        class="main-navigation__list-item-link"
                        href="/?project_id=<?=$project["id"]?>"
                    >
                        <?=htmlspecialchars($project["title"]); ?>
                    </a>
                    <span class="main-navigation__list-item-count">
                        <?=getProjectTaskCount($project["id"], $tasks); ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button"
       href="project" target="project_add">Добавить проект</a>
</section>

<main class="content__main">
    <?=$main_content ?>
</main>
