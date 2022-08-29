<?php
/**
 * Сценарий гостевой страницы
 * @var $connect mysqli - подключение к базе данных
 * @var $title string - заголовок проекта
 */
session_start();
require_once("config.php");
require_once("helpers.php");

if (count(getUserAuthentication()) !== 0) {
    redirectTo("/");
}

$guest_content = include_template("guest.php", [
    "title" => $title,
]);

$layout_template = include_template("layout.php", [
    "title" => $title,
    "template" => $guest_content,
    "background_class" => "body-background",
]);

print($layout_template);
