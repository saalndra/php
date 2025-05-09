CREATE DATABASE university;

USE university;

-- Створення нових таблиць для нормалізації
CREATE TABLE faculties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE degrees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Оновлення таблиці teachers для зберігання зовнішніх ключів
CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    surname VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    faculty_id INT,
    birthdate DATE NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    degree_id INT,
    position_id INT,
    FOREIGN KEY (faculty_id) REFERENCES faculties(id),
    FOREIGN KEY (degree_id) REFERENCES degrees(id),
    FOREIGN KEY (position_id) REFERENCES positions(id)
);
