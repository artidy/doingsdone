<?php
/**
* Функция для получения массива проектов пользователя
* @param mysqli $connect
* @param int $user_id
* @return array
 */
function getUserProjects(mysqli $connect, int $user_id): array
{
    $query = "SELECT
       id,
       title
    FROM projects
    WHERE author_id = ?";

    return fetchData(prepareResult($connect, $query, "i", [$user_id]));
}
