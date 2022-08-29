<?php
/**
 * Сценарий главной страницы
 * @var $connect mysqli - подключение к базе данных
 * @var $title string - заголовок проекта
 */
session_start();
require_once("config.php");
require_once("db.php");
require_once("helpers.php");

$user = getUserAuthentication();
if (count($user) === 0) {
    redirectTo("/guest");
}

$project_id = (string) (filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_SPECIAL_CHARS) ?? null);
$projects = normalizeProjects(getUserProjects($connect, $user["id"]));

if ($project_id && !isExistProject($project_id, $projects)) {
    http_response_code(404);
    die();
}

$show_complete_tasks = rand(0, 1);
$search = (string) (filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS) ?? "");
$tasks = $search === "" ?
    normalizeTasks(getUserTasks($connect, $user["id"])) :
    normalizeTasks(getUserTasksSearch($connect, $user["id"], $search));

$tasks_content = include_template("tasks.php", [
    "projects" => $projects,
    "tasks" => $tasks,
    "show_complete_tasks" => $show_complete_tasks,
    "project_id" => $project_id,
    "search" => $search,
]);

$main_template = include_template("main.php", [
    "projects" => $projects,
    "tasks" => $tasks,
    "project_id" => $project_id,
    "main_content" => $tasks_content,
]);

$layout_template = include_template("layout.php", [
    "title" => $title,
    "template" => $main_template,
    "user" => $user,
]);

print($layout_template);
