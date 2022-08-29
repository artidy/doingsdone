<?php
/**
 * Сценарий для добавления задач
 * @var $connect mysqli - подключение к базе данных
 * @var $title string - заголовок проекта
 */
session_start();
require_once("config.php");
require_once("db.php");
require_once("helpers.php");

$user = getUserAuthentication();
if (count($user) === 0) {
    redirectTo("/register");
}

$user_id = 2;
$projects = normalizeProjects(getUserProjects($connect, $user_id));
$errors = [];
$result = [
    "title" => "",
    "deadline" => null,
    "file_path" => null,
    "project_id" => "",
    "author_id" => $user_id,
    "errors" => [],
    "tmp_path" => "",
    "file_name" => "",
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploads_dir = '/uploads/';
    $full_path = __DIR__ . $uploads_dir;
    $project_id = $_POST["project"];

    $result = addTextContent("name", $result, "title", true, 125);
    $result = addTextContent("project", $result, "project_id", true);
    $result = addFile("file", "file_path", $result, $uploads_dir);
    $result = addDate("date", $result, "deadline");

    if (!isExistProject($project_id, $projects)) {
        $result["errors"] = addError(
            $result["errors"],
            "Проекта с идентификатором $project_id не существует",
            "project"
        );
    }

    $errors = $result["errors"];
    if (count($errors) === 0) {
        downloadFile($result["tmp_path"], $full_path, $result["file_name"]);

        $new_post_id = addTask(
            $connect,
            [
                $result["title"],
                $result["deadline"],
                $result["file_path"],
                $result["project_id"],
                $result["author_id"]
            ]
        );

        redirectTo("/");
    }
}

$add_content = include_template("add.php", [
    "projects" => $projects,
    "result" => $result,
]);

$main_template = include_template("main.php", [
    "projects" => $projects,
    "tasks" => normalizeTasks(getUserTasks($connect, $user_id)),
    "project_id" => null,
    "main_content" => $add_content,
]);

$layout_template = include_template("layout.php", [
    "title" => $title,
    "template" => $main_template,
]);

print($layout_template);
