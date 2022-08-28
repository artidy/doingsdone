<?php
/**
 * Сценарий главной страницы
 * @var $connect mysqli - подключение к базе данных
 */

require_once("config.php");
require_once("db.php");
require_once("helpers.php");

$user_id = 2;
$project_id = (string) (filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_SPECIAL_CHARS) ?? null);
$projects = normalizeProjects(getUserProjects($connect, $user_id));

if ($project_id && !in_array($project_id, array_column($projects, "id"))) {
    http_response_code(404);
    die();
}

$show_complete_tasks = rand(0, 1);

$tasks_content = include_template("tasks.php", [
    "projects" => $projects,
    "tasks" => normalizeTasks(getUserTasks($connect, $user_id)),
    "show_complete_tasks" => $show_complete_tasks,
    "project_id" => $project_id,
]);

$main_template = include_template("main.php", [
    "projects" => $projects,
    "tasks" => normalizeTasks(getUserTasks($connect, $user_id)),
    "project_id" => $project_id,
    "main_content" => $tasks_content,
]);

$layout_template = include_template("layout.php", [
    "title" => "Дела в порядке",
    "template" => $main_template,
]);

print($layout_template);
