<?php
/**
 * @var $connect mysqli - подключение к базе данных
 */
session_start();
require_once("config.php");
require_once("db.php");
require_once("helpers.php");

if (count(getUserAuthentication()) > 0) {
    redirectTo("/");
}

$errors = [];
$result = [
    "email" => "",
    "name" => "",
    "password" => "",
    "errors" => [],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reg_fields = [
        "email" => "email",
        "name" => "name",
        "password" => "password",
    ];

    foreach ($reg_fields as $field => $web_name) {
        switch ($field) {
            case "email":
                $result = addEmail($field, $web_name, $result, $connect);
                break;
            case "name":
                $result = addTextContent($web_name, $result, $field, true, 128);
                break;
            case "password":
                $result = addPassword($field, $web_name, $result);
                break;
        }
    }

    $errors = $result["errors"];

    if (count($errors) === 0) {
        $new_post_id = addUser(
            $connect,
            [
                $result["email"],
                $result["name"],
                $result["password"],
            ]
        );

        redirectTo("/");
    }
}

$register_content = include_template("register.php", [
    "result" => $result,
]);

$layout_content = include_template("layout.php", [
    "title" => "Дела в порядке",
    "template" => $register_content,
]);

print($layout_content);
