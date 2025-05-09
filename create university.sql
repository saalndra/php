CREATE DATABASE university;

USE university;

CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    surname VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    faculty VARCHAR(100) NOT NULL,
    birthdate DATE NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    degree VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL
);
