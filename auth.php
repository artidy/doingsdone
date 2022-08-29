<?php
/**
* Сценарий для авторизации
* @var $connect mysqli - подключение к базе данных
* @var $title string - заголовок проекта
*/

session_start();
require_once("config.php");
require_once("db.php");
require_once("helpers.php");

$result = [
    "email" => "",
    "password" => "",
    "errors" => [],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reg_fields = [
        "email" => "email",
        "password" => "password",
    ];

    foreach ($reg_fields as $field => $web_name) {
        switch ($field) {
            case "email":
                $result = addTextContent($web_name, $result, $field, true, 320);
                break;
            case "password":
                $result = addTextContent($web_name, $result, $field, true);
                break;
        }
    }

    $user = getUserByEmail($connect, $result["email"]);

    if (count($result["errors"]) === 0 && count($user) === 0) {
        $result["errors"] = addError(
            $result["errors"],
            "Пользователь с таким email не найден",
            "email"
        );
    }

    if (count($result["errors"]) === 0 && !password_verify($result["password"], $user["password"])) {
        $result["errors"] = addError(
            $result["errors"],
            "Неверный пароль пользователя",
            "password"
        );
    }

    if (count($result["errors"]) === 0) {
        $_SESSION["user"] = normalizeUser($user);
    }
}

if (count(getUserAuthentication()) > 0) {
    redirectTo("/");
}

$auth_content = include_template("auth.php", [
    "result" => $result,
]);

$layout_content = include_template("layout.php", [
    "title" => $title,
    "template" => $auth_content,
]);

print($layout_content);
