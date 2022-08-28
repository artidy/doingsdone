<?php
/**
 * Функция для получения массива задач пользователя
 * @param mysqli $connect
 * @param int $user_id
 * @return array
 */
function getUserTasks(mysqli $connect, int $user_id): array
{
    $query = "SELECT
       tasks.id,
       tasks.title,
       status,
       deadline,
       file_path,
       project_id,
       projects.title as project
    FROM tasks
        INNER JOIN projects on tasks.project_id = projects.id
    WHERE tasks.author_id = ?";

    return fetchData(prepareResult($connect, $query, "i", [$user_id]));
}

/**
 * Функция для добавления задачи
 * @param mysqli $connect
 * @param array $task
 * @return string
 */
function addTask(mysqli $connect, array $task): string
{
    $query = "INSERT INTO tasks (
            title,
            deadline,
            file_path,
            project_id,
            author_id
        ) VALUES (
            ?,
            ?,
            ?,
            ?,
            ?
        )";

    preparePostResult($connect, $query, "sssii", $task);

    return getInsertId($connect);
}
