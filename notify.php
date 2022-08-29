<?php
/**
 * Сценарий отправки уведомлений
 * @var $connect mysqli - подключение к базе данных
 */

require_once "vendor/autoload.php";
require_once "config.php";
require_once "db.php";

$transport = new Swift_SmtpTransport("smtp.mailtrap.io", 2525);
$transport->setUsername("2f9a68295f0fcb");
$transport->setPassword("ce330b45e27fd2");

$mailer = new Swift_Mailer($transport);

$deadline = date_format(date_create("today"),"Y-m-d");
$tasks = getTasksByDeadline($connect, $deadline);
$current_author_id = -1;
$title = "Уведомление от сервиса «Дела в порядке»";
$messages = [];

foreach ($tasks as $task) {
    if ($current_author_id !== $task["author_id"]) {
        $name = $task["name"];
        $messages[$task["author_id"]]["name"] = $name;
        $messages[$task["author_id"]]["title"] = "Уважаемый, $name.\n\n";
        $messages[$task["author_id"]]["email"] = $task["email"];
        $messages[$task["author_id"]]["message"] = "";
        $current_author_id = $task["author_id"];
    }

    $task_title = "«" . $task["title"] . "»";
    $task_deadline = $task["deadline"] ? date_format(date_create($task["deadline"]),"d.m.Y") : "";
    $prev_message = $messages[$task["author_id"]]["message"];
    $messages[$task["author_id"]]["message"] = $prev_message .
        "У вас запланирована задача $task_title на $task_deadline.\n";
}

foreach ($messages as $task_message) {
    $message = new Swift_Message();
    $message->setSubject($title);
    $message->setFrom(["keks@phpdemo.ru" => "Doings done"]);
    $message->setTo([$task_message["email"] => $task_message["name"]]);
    $message->setBody($task_message["title"] . $task_message["message"], "text/plain");

    $result = $mailer->send($message);

    if ($result) {
        print("Рассылка успешно отправлена.");

        continue;
    }

    print("Произошла ошибка отправки.");
}
