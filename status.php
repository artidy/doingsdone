<?php
/**
 * Сценарий для смены статуса проекта
 * @var $connect mysqli - подключение к базе данных
 */
session_start();
require_once("config.php");
require_once("db.php");
require_once("helpers.php");

$user = getUserAuthentication();
if (count($user) === 0) {
    redirectTo("/");
}

$referer = $_SERVER["HTTP_REFERER"];
$task_id = $_POST["task_id"] ?? "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $task_id !== "") {
    $task_status = (bool) $_POST["status"] ?? false;

    toggleTaskStatus($connect, $task_status, $user["id"], $task_id);
}

redirectTo($referer);
