intalasi
- instal xampp

1. instalasi database

CREATE DATABASE student_management;

USE student_management;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    age INT NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'guest') DEFAULT 'guest'
);

2. taruh di file htdocs
3. visit link : http://localhost/student_management
4. register : http://localhost/student_management/register.php
5. login : http://localhost/student_management/login.php