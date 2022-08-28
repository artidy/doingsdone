<?php
/**
 * Сценарий главной страницы
 * @var $projects array<string> - массив проектов
 * @var $tasks array<array{title:string, deadline:string, project:string, is_complited:bool}> - массив задач по проектам
 * @var $show_complete_tasks bool - статус отображения выполненных задач
 */

require_once("config.php");
require_once("db.php");
require_once("helpers.php");
require_once("data.php");

$main_template = include_template("main.php", [
    "projects" => $projects,
    "tasks" => $tasks,
    "show_complete_tasks" => $show_complete_tasks,
]);

$layout_template = include_template("layout.php", [
    "title" => "Дела в порядке",
    "template" => $main_template,
]);

print($layout_template);
