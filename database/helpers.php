<?php

/**
 * Функция для проверки результат запроса
 * @param $result
 * @param $connect
 * @param $err_message
 * @return void
 */
function checkResult($result, $connect, $err_message): void
{
    if ($result === false) {
        die($err_message . ": " . mysqli_error($connect));
    }
}

/**
 * Функция для получения массива из результата запроса
 * @param $result
 * @return array
 */
function fetchData($result): array
{
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Функция для получения ассоциативного массива из результата запроса
 * @param $result
 * @return array
 */
function fetchAssocData($result): array
{
    $result = mysqli_fetch_assoc($result);
    return $result ?? [];
}

/**
 * Функция для подготовки текста get запроса в MySQL
 * @param $connect
 * @param $query
 * @param string $types
 * @param array $params
 * @return mysqli_result
 */
function prepareResult($connect, $query, string $types = "", array $params = []): mysqli_result
{

    $stmt = mysqli_prepare($connect, $query);
    checkResult($stmt, $connect, "Ошибка подготовки запроса");

    if ($types !== "") {
        $result = mysqli_stmt_bind_param($stmt, $types, ...$params);
        checkResult($result, $connect, "Ошибка установки параметров");
    }

    $result = mysqli_stmt_execute($stmt);
    checkResult($result, $connect, "Ошибка выполнения запроса");

    $result = mysqli_stmt_get_result($stmt);
    checkResult($result, $connect, "Ошибка получения данных");

    return $result;
}

/**
 * Функция для подготовки текста post запроса в MySQL
 * @param $connect
 * @param $query
 * @param $types
 * @param $params
 * @return bool
 */
function preparePostResult($connect, $query, $types, $params): bool
{

    $stmt = mysqli_prepare($connect, $query);
    checkResult($stmt, $connect, "Ошибка подготовки запроса");

    $result = mysqli_stmt_bind_param($stmt, $types, ...$params);
    checkResult($result, $connect, "Ошибка установки параметров");

    $result = mysqli_stmt_execute($stmt);
    checkResult($result, $connect, "Ошибка выполнения запроса");

    return $result;
}

/**
 * Функция для возврата идентификатора последнего добавленного значения в базу
 * @param $connect
 * @return string
 */
function getInsertId($connect): string
{
    return (string) mysqli_insert_id($connect);
}
