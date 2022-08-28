<?php
/**
 * Сценарий главной страницы
 * @var $connect mysqli - подключение к базе данных
 * @var $tasks array<array{title:string, deadline:string, project:string, is_complited:bool}> - массив задач по проектам
 * @var $show_complete_tasks bool - статус отображения выполненных задач
 */

require_once("config.php");
require_once("db.php");
require_once("helpers.php");

$user_id = 2;

$main_template = include_template("main.php", [
    "projects" => normalizeProjects(getUserProjects($connect, $user_id)),
    "tasks" => normalizeTasks(getUserTasks($connect, $user_id)),
    "show_complete_tasks" => rand(0, 1),
]);

$layout_template = include_template("layout.php", [
    "title" => "Дела в порядке",
    "template" => $main_template,
]);

print($layout_template);
