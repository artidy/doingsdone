<?php
/**
 * Шаблон главной страницы сайта
 *
 * @var $projects array<array{id:string, title:string}> - массив проектов
 * @var $tasks array<array{id:string, title:string, deadline:string,
 *     project:string, is_complited:bool, project_id:string, file_path:string}> - массив задач по проектам
 * @var $project_id string - идентификатор выбранного проекта
 * @var $main_content string - контент для основной страницы
 * @var $project_id_param string - строка запроса для проектов
 * @var $completed_param string - строка запроса для отображения завершенных проектов
 * @var $filter_param string - строка запроса фильтров
 * @var $search_param string - строка запроса поиска
 */
?>
<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $project):
                $current_project_id = $project["id"];
            ?>
                <li class="main-navigation__list-item
                        <?=$project_id === $current_project_id ? "main-navigation__list-item--active" : "" ?>"
                >
                    <a
                        class="main-navigation__list-item-link"
                        href="<?= getQueryParams(["project_id=$current_project_id", $completed_param, $filter_param, $search_param]); ?>"
                    >
                        <?=htmlspecialchars($project["title"]); ?>
                    </a>
                    <span class="main-navigation__list-item-count">
                        <?=getProjectTaskCount($current_project_id, $tasks); ?>
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
