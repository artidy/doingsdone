<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template(string $name, array $data = []): string {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    return ob_get_clean();
}

/**
 * Возвращает общее количество задач для переданого проекта
 * @param string $project_id Идентификатор проекта
 * @param array $tasks Массив со всеми задачами
 * @return int Общее количество задач по проекту
 */
function getProjectTaskCount(string $project_id, array $tasks): int {
    return count(array_keys(array_column($tasks, "project_id"), $project_id));
}

/**
 * Вычисляет остаток часов до дедлайна
 * @param string|null $deadline Дата дедлайна
 * @return int|null Количество часов до дедлайна
 */
function getRestOfTime(string | null $deadline): int | null {
    $deadline_timestamp = strtotime($deadline);

    if (!$deadline_timestamp) {
        return null;
    }

    $current_date = date_create();
    $current_date_timestamp = date_timestamp_get($current_date);

    return floor(($deadline_timestamp - $current_date_timestamp) / 3600);
}

/**
 * Функция-адаптер для проекта
 * @param array $project Данные по проекту пользователя из базы
 * @return array Отформатированный проект для вывода на сайт
 */
function normalizeProject(array $project): array
{
    if ($project === []) {
        return $project;
    }

    return [
        "id" => (string) $project["id"],
        "title" => $project["title"],
    ];
}

/**
 * Функция-адаптер для массива проектов
 * @param array $projects Массив данных по проекту пользователя из базы
 * @return array Отформатированный массив проектов для вывода на сайт
 */
function normalizeProjects(array $projects): array
{
    $result = [];

    foreach ($projects as $project) {
        $result[] = normalizeProject($project);
    }

    return $result;
}

/**
 * Функция-адаптер для задачи
 * @param array $task Данные по задаче пользователя из базы
 * @return array Отформатированная задача для вывода на сайт
 */
function normalizeTask(array $task): array
{
    if ($task === []) {
        return $task;
    }

    $deadline = $task["deadline"] ? date_format(date_create($task["deadline"]),"d.m.Y") : null;

    return [
        "id" => (string) $task["id"],
        "title" => $task["title"],
        "is_completed" => (bool) $task["status"],
        "deadline" => $deadline,
        "project" => $task["project"],
        "project_id" => (string) $task["project_id"],
        "file_path" => $task["file_path"],
    ];
}

/**
 * Функция-адаптер для массива задач
 * @param array $tasks Массив данных по задачам пользователя из базы
 * @return array Отформатированный массив задач для вывода на сайт
 */
function normalizeTasks(array $tasks): array
{
    $result = [];

    foreach ($tasks as $task) {
        $result[] = normalizeTask($task);
    }

    return $result;
}

/**
 * Функция-адаптер для пользователя
 * @param array $user Данные пользователя из базы данных
 * @return array Данные пользователя для работы на сайте
 */
function normalizeUser(array $user): array
{
    if ($user === []) {
        return $user;
    }

    return [
        "id" => (string) $user["id"],
        "email" => $user["email"],
        "name" => $user["name"],
        "registered_at" => $user["registered_at"],
    ];
}

/**
 * Функция для проверки заполнения поля
 * @param string $field_name Идентификатор поля
 * @return string Описание ошибки
 */
function checkFilling(string $field_name): string
{
    $error_message = "";
    if (isset($_POST[$field_name]) && trim($_POST[$field_name]) === "") {
        $error_message = "Это поле должно быть заполнено.";
    }

    return $error_message;
}

/**
 * Функция для проверки заполнения длины поля
 * @param string $text Контент текстового поля
 * @param int $length Максимальная длина поля
 * @return string Описание ошибки
 */
function checkLength(string $text, int $length): string
{
    $error_message = "";
    if (mb_strlen($text, "UTF-8") > $length) {
        $error_message = "Длина этого поля не может превышать " . $length . " символов";
    }

    return $error_message;
}

/**
 * Функция добавления ошибки заполнения формы
 * @param array $errors Массив с ошибками
 * @param string $error_message Описание ошибки
 * @param string $field_name Идентификатор поля
 * @return array Массив с ошибками
 */
function addError(array $errors, string $error_message, string $field_name): array
{
    if ($error_message !== "") {
        $errors[$field_name][] = $error_message;
    }

    return $errors;
}

/**
 * Функция для получения полного пути хранения файла
 * @param string $full_path Полный путь до директории
 * @param string $file_name Имя файла
 * @return string Полный путь для сохранения файла
 */
function getFilePath(string $full_path, string $file_name): string
{
    return $full_path . basename($file_name);
}

/**
 * Функция для загрузки файла переданного с компьютера
 * @param string $tmp_path Временный путь хранения файла
 * @param string $full_path Полный путь до директории
 * @param string $file_name Имя файла
 * @return void Ничего не возвращает
 */
function downloadFile(string $tmp_path, string $full_path, string $file_name): void
{
    if ($tmp_path !== "") {
        $file_path = getFilePath($full_path, $file_name);
        move_uploaded_file($tmp_path, $file_path);
    }
}

/**
 * Функция для обработки файла загруженного с компьютера
 * @param string $web_name Имя поля хранения файла в запросе
 * @param string $field Имя поля для сохранения в базу
 * @param array $result Результат обработки файла
 * @param string $uploads_dir Директория сохранения файла
 * @return array Результат обработки файла
 */
function addFile(string $web_name, string $field, array $result, string $uploads_dir): array
{
    if (!isset($_FILES[$web_name]) || $_FILES[$web_name]["error"] !== 0) {
        return $result;
    }

    $file = $_FILES[$web_name];

    $result[$field] = $uploads_dir . $file["name"];
    $result["file_name"] = $file["name"];
    $result["tmp_path"] = $file["tmp_name"];

    return $result;
}

/**
 * Функция для обработки текста
 * @param string $web_name Индентификатор поля в POST запросе
 * @param array $result Результат проверки полей
 * @param string $field Идентификатор поля в базе
 * @param bool $required_empty_field Включить проверку незаполненного значения
 * @param int $length Максимальная длина содержимого поля
 * @return array Результат проверки полей
 */
function addTextContent(string $web_name, array $result, string $field,
    bool $required_empty_field, int $length = 1000): array
{
    if ($required_empty_field) {
        $result["errors"] = addError(
            $result["errors"],
            checkFilling($web_name),
            $web_name
        );
    }

    $result["errors"] = addError(
        $result["errors"],
        checkLength($_POST[$web_name], $length),
        $web_name
    );

    $result[$field] = $_POST[$web_name] ?? "";

    return $result;
}

/**
 * Функция проверки существования проекта
 * @param string $search_value Значение поиска проекта
 * @param array $projects Массив существующих проектов
 * @param string $project_column Имя поля проекта
 * @return bool Результат проверки проекта
 */
function isExistProject(string $search_value, array $projects, string $project_column = "id"): bool {
    return in_array($search_value, array_column($projects, $project_column));
}

/**
 * Функция для проверки и добавления даты
 * @param string $web_name Индентификатор поля в POST запросе
 * @param array $result Результат проверки полей
 * @param string $field Имя поля в базе данных
 * @return array Результат проверки полей
 */
function addDate(string $web_name, array $result, string $field): array {
    $post_date = $_POST[$web_name];

    if (!$post_date) {
        return $result;
    }

    $pattern = '/^[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])$/';

    if (!preg_match($pattern, $post_date)) {
        $result["errors"] = addError(
            $result["errors"],
            "Неверный формат даты",
            $web_name
        );
    }

    $current_date_timestamp = date_create("today")->getTimestamp();
    $date_timestamp = strtotime($post_date);

    if ($current_date_timestamp > $date_timestamp) {
        $result["errors"] = addError(
            $result["errors"],
            "Нельзя указывать дату меньше текущей",
            $web_name
        );
    }

    $result[$field] = $_POST[$web_name] ?? null;

    return $result;
}

/**
 * Функция для перенаправления на другую страницу
 * @param string $page Страница перенаправления
 * @return void
 */
#[NoReturn] function redirectTo(string $page): void
{
    header("Location: $page");
    exit();
}

/**
 * Функция для получения текущего авторизованного пользователя
 * @return array Данные авторизованного пользователя
 */
function getUserAuthentication(): array
{
    return $_SESSION['user'] ?? [];
}

/**
 * Функция для обработки email
 * @param string $field Идентификатор поля в базе данных
 * @param string $web_name Идентификатор поля на форме
 * @param array $result Результат проверки формы
 * @param mysqli $connect Подключение к базе данных
 * @return array Результат проверки формы
 */
function addEmail(string $field, string $web_name, array $result, mysqli $connect): array
{
    $result["errors"] = addError($result["errors"], checkFilling($web_name), $web_name);
    if ($_POST[$web_name] === "") {
        return $result;
    }

    $result[$field] = $_POST[$web_name];

    $result["errors"] = addError($result["errors"], checkLength($result[$field], 320), $web_name);

    $email = filter_var($result[$field], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $result["errors"] = addError($result["errors"], "Неверно заполнен email", $web_name);
        return $result;
    }

    $user = getUserByEmail($connect, $email);
    if (count($user) > 0) {
        $result["errors"] = addError($result["errors"], "Пользователь с таким email уже существует", $web_name);
        return $result;
    }

    return $result;
}

/**
 * Функция для получения хеша пароля
 * @param string $password Пароль для которого необходимо получить хэш
 * @return string Хэш пароля
 */
function getHashPassword(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Функция для обработки пароля
 * @param string $field Идентификатор поля в базе данных
 * @param string $web_name Идентификатор поля на форме
 * @param array $result Результат проверки формы
 * @return array Результат проверки формы
 */
function addPassword(string $field, string $web_name, array $result): array
{
    $result["errors"] = addError($result["errors"], checkFilling($web_name), $web_name);

    if (count($result["errors"]) > 0) {
        return $result;
    }

    $result[$field] = getHashPassword($_POST[$web_name]);

    return $result;
}
