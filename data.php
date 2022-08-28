<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$projects = [
    "Входящие",
    "Учеба",
    "Работа",
    "Домашние дела",
    "Авто"
];
$tasks = [
    [
        "title" => "Собеседование в IT компании",
        "deadline" => "01.12.2019",
        "project" => "Работа",
        "is_completed" => false
    ],
    [
        "title" => "Выполнить тестовое задание",
        "deadline" => "25.12.2019",
        "project" => "Работа",
        "is_completed" => false
    ],
    [
        "title" => "Сделать задание первого раздела",
        "deadline" => "21.12.2019",
        "project" => "Учеба",
        "is_completed" => true
    ],
    [
        "title" => "Встреча с другом",
        "deadline" => "22.12.2019",
        "project" => "Входящие",
        "is_completed" => false
    ],
    [
        "title" => "Купить корм для кота",
        "deadline" => null,
        "project" => "Домашние дела",
        "is_completed" => false
    ],
    [
        "title" => "Заказать пиццу",
        "deadline" => null,
        "project" => "Домашние дела",
        "is_completed" => false
    ]
];
