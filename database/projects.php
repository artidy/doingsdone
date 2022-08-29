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

/**
 * Функция для получения массива проектов пользователя по заголовку
 * @param mysqli $connect
 * @param int $user_id
 * @param string $title
 * @return array
 */
function getUserProjectsByTitle(mysqli $connect, int $user_id, string $title): array
{
    $query = "SELECT
       id,
       title
    FROM projects
    WHERE author_id = ? AND title = ?";

    return fetchData(prepareResult($connect, $query, "is", [$user_id, $title]));
}

/**
 * Функция для добавления проекта
 * @param mysqli $connect
 * @param array $project
 * @return string
 */
function addUserProject(mysqli $connect, array $project): string
{
    $query = "INSERT INTO projects (title, author_id) VALUES (?,?)";

    preparePostResult($connect, $query, "si", $project);

    return getInsertId($connect);
}
