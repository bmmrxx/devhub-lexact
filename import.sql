CREATE DATABASE IF NOT EXISTS devhub_lexact_db;

USE devhub_lexact_db;

CREATE TABLE
    IF NOT EXISTS user (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM ('admin', 'mentor', 'intern'),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE
    IF NOT EXISTS code_snippet (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        title VARCHAR(255),
        code TEXT,
        language VARCHAR(50),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES user (id)
    );

CREATE TABLE
    IF NOT EXISTS note (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        title VARCHAR(255),
        content TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES user (id)
    );

CREATE TABLE
    IF NOT EXISTS intern_upload (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        content TEXT,
        date DATE,
        category ENUM ('daily', 'school', 'questions'),
        FOREIGN KEY (user_id) REFERENCES user (id)
    );

CREATE TABLE
    IF NOT EXISTS file (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        project_id VARCHAR(255) UNIQUE,
        name VARCHAR(255),
        uploaded_at VARCHAR(50),
        FOREIGN KEY (user_id) REFERENCES user (id)
    );

CREATE TABLE
    IF NOT EXISTS visibility_file_user (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        file_id INT,
        FOREIGN KEY (user_id) REFERENCES user (id),
        FOREIGN KEY (file_id) REFERENCES file (id)
    );

CREATE TABLE
    IF NOT EXISTS project (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        name VARCHAR(255),
        FOREIGN KEY (user_id) REFERENCES user (id)
    );

CREATE TABLE
    IF NOT EXISTS password_reset (
        user_id INT UNIQUE,
        token VARCHAR(255),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES user (id)
    );

CREATE TABLE
    IF NOT EXISTS mentor_intern (
        id INT AUTO_INCREMENT PRIMARY KEY,
        mentor_id INT,
        intern_id INT,
        FOREIGN KEY (mentor_id) REFERENCES user (id),
        FOREIGN KEY (intern_id) REFERENCES user (id)
    );