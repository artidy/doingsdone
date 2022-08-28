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
