<?php

$connect = mysqli_connect("localhost", "root", "root", "doingsdone");
if ($connect === false) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

mysqli_set_charset($connect, "utf8");
