<?php
/**
 * Сценарий для добавления задач
 * @var $connect mysqli - подключение к базе данных
 */

require_once("config.php");
require_once("db.php");
require_once("helpers.php");

$user_id = 2;
$projects = normalizeProjects(getUserProjects($connect, $user_id));

$main_template = include_template("main.php", [
    "projects" => $projects,
    "tasks" => normalizeTasks(getUserTasks($connect, $user_id)),
    "show_complete_tasks" => rand(0, 1),
    "project_id" => 0,
]);

$layout_template = include_template("layout.php", [
    "title" => "Дела в порядке",
    "template" => $main_template,
]);

print($layout_template);
