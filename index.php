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
require_once("data.php");

$main_template = include_template("main.php", [
    "projects" => getUserProjects($connect, 2),
    "tasks" => $tasks,
    "show_complete_tasks" => $show_complete_tasks,
]);

$layout_template = include_template("layout.php", [
    "title" => "Дела в порядке",
    "template" => $main_template,
]);

print($layout_template);
