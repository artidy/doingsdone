USE
  doingsdone;

-- Добавление тестовых пользователей
INSERT INTO users (email, name, password)
VALUES ('test@test.com', 'Лариса', MD5('123456')),
       ('test1@test.com', 'Виктор', MD5('321456')),
       ('test2@test.com', 'Владик', MD5('654321'));

-- Добавление тестовых данных по проектам
INSERT INTO projects (title, author_id)
VALUES ('Входящие', 2),
       ('Учеба', 3),
       ('Работа', 2),
       ('Домашние дела', 1),
       ('Авто', 3);

-- Добавление тестовых данных по задачам
INSERT INTO tasks (title, status, deadline, project_id, author_id)
VALUES ('Собеседование в IT компании', false, '2022-08-29', 3, 2),
       ('Выполнить тестовое задание', false, '2019-12-25', 3, 2),
       ('Сделать задание первого раздела', true, '2019-12-21', 2, 3),
       ('Встреча с другом', false, '2022-08-30', 1, 2),
       ('Купить корм для кота', false, null, 4, 1),
       ('Заказать пиццу', false, null, 4, 1);
