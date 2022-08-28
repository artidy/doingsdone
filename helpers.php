<?php
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
