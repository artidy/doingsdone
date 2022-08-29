<?php
/**
 * Функция для получения пользователя по email
 * @param mysqli $connect
 * @param string $email
 * @return array
 */
function getUserByEmail(mysqli $connect, string $email): array
{
    $query = "SELECT
        id,
        registered_at,
        email,
        name,
        password
    FROM users
    WHERE email = ?";

    return fetchAssocData(prepareResult($connect, $query, "s", [$email]));
}

/**
 * Функция для добавления пользователя
 * @param mysqli $connect
 * @param array $user
 * @return string
 */
function addUser(mysqli $connect, array $user): string
{
    $query = "INSERT INTO users (
            email,
            name,
            password
        ) VALUES (
            ?,
            ?,
            ?
        )";

    preparePostResult($connect, $query, "sss", $user);

    return getInsertId($connect);
}
