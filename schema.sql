DROP
DATABASE IF EXISTS doingsdone;

CREATE
DATABASE doingsdone
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE
  doingsdone;

CREATE TABLE users
(
  id            INT AUTO_INCREMENT PRIMARY KEY,
  email         VARCHAR(320) UNIQUE NOT NULL,
  name          VARCHAR(128) NOT NULL,
  password      VARCHAR(256) NOT NULL,
  registered_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects
(
  id            INT AUTO_INCREMENT PRIMARY KEY,
  title         VARCHAR(125) NOT NULL,
  author_id     INT NOT NULL,
  FOREIGN KEY (author_id) REFERENCES users (id)
);
/*
  Внешний ключ author_id нужен для контроля целостности данных,
    чтобы нельзя было удалить пользователя, если у него есть проекты.
*/

CREATE TABLE tasks
(
  id             INT AUTO_INCREMENT PRIMARY KEY,
  title          VARCHAR(125) NOT NULL,
  status         BOOLEAN DEFAULT FALSE,
  deadline       DATE,
  file           VARCHAR(125),
  project_id     INT NOT NULL,
  author_id      INT NOT NULL,
  FOREIGN KEY (project_id) REFERENCES projects (id),
  FOREIGN KEY (author_id) REFERENCES users (id)
);

/*
  Внешний ключ project_id нужен для контроля целостности данных,
    чтобы нельзя было удалить проект, если у него есть задачи.
  Внешний ключ author_id нужен для контроля целостности данных,
    чтобы нельзя было удалить пользователя, если у него есть задачи.
*/
