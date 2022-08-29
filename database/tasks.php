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
 * Функция для получения массива задач пользователя по поисковому запросу
 * @param mysqli $connect
 * @param int $user_id
 * @param string $search
 * @return array
 */
function getUserTasksSearch(mysqli $connect, int $user_id, string $search): array
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
    WHERE
        tasks.author_id = ? AND
        MATCH(tasks.title) AGAINST(? IN BOOLEAN MODE)";

    return fetchData(prepareResult($connect, $query, "is", [$user_id, "$search*"]));
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

/**
 * Функция для смены статуса задачи
 * @param mysqli $connect
 * @param bool $status
 * @param string $author_id
 * @param string $task_id
 * @return string
 */
function toggleTaskStatus(mysqli $connect, bool $status, string $author_id, string $task_id): string
{
    $query = "UPDATE tasks SET status=? WHERE author_id=? AND id=?";

    preparePostResult($connect, $query, "iii", [$status, $author_id, $task_id]);

    return getInsertId($connect);
}
