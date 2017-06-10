CREATE DATABASE 'SARASA';

USE SARASA;

CREATE TABLE user
(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) DEFAULT NULL,
  age INT DEFAULT 0,
  email VARCHAR(50) DEFAULT NULL
);

INSERT INTO user(name, age, email)
VALUES
('Carlos Lopez', 36, 'carlitos@gmail.com'),
('Vanina Strotsky', 20, 'vani@gmail.com'),
('Juliana Stroensberg', 27, 'juli@gmail.com')
