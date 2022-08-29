<?php
/**
 * Сценарий для добавления проекта
 * @var $connect mysqli - подключение к базе данных
 * @var $title string - заголовок проекта
 */
session_start();
require_once("config.php");
require_once("db.php");
require_once("helpers.php");

$user = getUserAuthentication();
if (count($user) === 0) {
    redirectTo("/");
}

$result = [
    "title" => "",
    "errors" => [],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = addTextContent("name", $result, "title", true, 125);
    $title = $result["title"];

    if (count(getUserProjectsByTitle($connect, $user["id"], $title)) > 0) {
        $result["errors"] = addError(
            $result["errors"],
            "Уже существует проект с именем $title",
            "name"
        );
    }

    if (count($result["errors"]) === 0) {
        $new_project_id = addUserProject(
            $connect,
            [
                $result["title"],
                $user["id"]
            ]
        );

        redirectTo("/");
    }
}

$projects = normalizeProjects(getUserProjects($connect, $user["id"]));

$project_content = include_template("project.php", [
    "result" => $result,
]);

$main_template = include_template("main.php", [
    "projects" => $projects,
    "tasks" => normalizeTasks(getUserTasks($connect, $user["id"])),
    "project_id" => null,
    "main_content" => $project_content,
]);

$layout_template = include_template("layout.php", [
    "title" => $title,
    "template" => $main_template,
    "user" => $user,
]);

print($layout_template);
