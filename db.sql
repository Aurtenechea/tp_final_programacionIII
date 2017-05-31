CREATE DATABASE PARKING_SYSTEM;

USE PARKING_SYSTEM;

CREATE TABLE CAR
(
  id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  license VARCHAR(10) DEFAULT 'undefined',
  color VARCHAR(20) DEFAULT 'undefined',
  model VARCHAR(50) DEFAULT 'undefined',
  owner_id BIGINT NOT NULL,
  comment TINYTEXT
);

CREATE TABLE PERSON
(
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(10) NOT NULL DEFAULT 'undefined',
    first_name VARCHAR(30) DEFAULT 'undefined',
    last_name VARCHAR(30) DEFAULT 'undefined',
    email VARCHAR(50) DEFAULT 'undefined',
    phone VARCHAR(20) DEFAULT 'undefined',
    gender CHAR(1) DEFAULT '-',
    unique (dni)
);

CREATE TABLE LOCATION
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    floor TINYINT NOT NULL DEFAULT 0,
    sector VARCHAR(3) NOT NULL DEFAULT '-',
    number VARCHAR(5) NOT NULL DEFAULT '-'
    /* agregar para cocheras alquiladas. */
);

CREATE TABLE EMPLOYEE
(
    person_id INT NOT NULL PRIMARY KEY,
    shift VARCHAR(15) NOT NULL DEFAULT 'undefined',
    password VARCHAR(20) NOT NULL DEFAULT 'pass'
);

CREATE TABLE PARKS
(
    car_id INT NOT NULL,
    location_id INT NOT NULL,
    check_in DATE DEFAULT NULL,
    check_out DATE DEFAULT NULL,
    unique (car_id),
    unique (location_id)
);

CREATE TABLE CLIENT
(
    person_id INT NOT NULL,
    balance INT DEFAULT 0
);

/* tabla temporal para una clase */
CREATE TABLE USER
(
  id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  mail VARCHAR(50) DEFAULT 'undefined',
  pass VARCHAR(20) DEFAULT 'undefined',
  state CHAR DEFAULT 'T'
);



/* procedures to CAR */
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `saveCar`(
                                                    IN `car_license` VARCHAR(10),
                                                    IN `car_color` VARCHAR(20),
                                                    IN `car_model` VARCHAR(50),
                                                    IN `car_owner_id` BIGINT,
                                                    IN `comment` TINYTEXT
                                                 )
        NO SQL
    INSERT into CAR (license, color, model, owner_id, comment)
        values (car_license,
                car_color,
                car_model,
                car_owner_id,
                comment);

    --SET @last_insert_id_car = LAST_INSERT_ID();
$$
DELIMITER ;










CREATE DEFINER=`root`@`localhost` PROCEDURE `BorrarPersona`(IN `idp` INT(18))
    NO SQL
delete from persona	WHERE id=idp$$



delete from CAR;
select * from CAR;
DROP PROCEDURE saveCar;

INSERT INTO CAR(license, owner_id)
VALUES
('sarasa', 36)
-- ('Vanina Strotsky', 20, 'vani@gmail.com'),
-- ('Juliana Stroensberg', 27, 'juli@gmail.com')
