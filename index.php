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

$show_complete_tasks = (bool) (filter_input(INPUT_GET, 'show_completed', FILTER_SANITIZE_SPECIAL_CHARS) ?? false);
$filter = (string) (filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_SPECIAL_CHARS) ?? "");
$search = (string) (filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS) ?? "");
$tasks = $search === "" ?
    normalizeTasks(getUserTasks($connect, $user["id"])) :
    normalizeTasks(getUserTasksSearch($connect, $user["id"], $search));

switch ($filter) {
    case "today":
        $tasks = array_filter($tasks, function ($task) {
            return isCurrentDate($task["deadline"]);
        });
        break;
    case "tomorrow":
        $tasks = array_filter($tasks, function ($task) {
            return isTomorrowDate($task["deadline"]);
        });
        break;
    case "overdue":
        $tasks = array_filter($tasks, function ($task) {
            return isOverdueDate($task["deadline"]);
        });
        break;
}

$project_id_param = $project_id === "" ? "" : "project_id=$project_id";
$completed_param = $show_complete_tasks ? "show_completed=1" : "";
$filter_param = $filter === "" ? "" : "filter=$filter";
$search_param = $search === "" ? "" : "search=$search";

$tasks_content = include_template("tasks.php", [
    "projects" => $projects,
    "tasks" => $tasks,
    "show_complete_tasks" => $show_complete_tasks,
    "project_id" => $project_id,
    "search" => $search,
    "filter" => $filter,
    "project_id_param" => $project_id_param,
    "completed_param" => $completed_param,
    "filter_param" => $filter_param,
    "search_param" => $search_param,
]);

$main_template = include_template("main.php", [
    "projects" => $projects,
    "tasks" => $tasks,
    "project_id" => $project_id,
    "main_content" => $tasks_content,
    "project_id_param" => $project_id_param,
    "completed_param" => $completed_param,
    "filter_param" => $filter_param,
    "search_param" => $search_param,
]);

$layout_template = include_template("layout.php", [
    "title" => $title,
    "template" => $main_template,
    "user" => $user,
]);

print($layout_template);
