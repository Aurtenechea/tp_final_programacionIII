CREATE DATABASE PARKING_SYSTEM;

USE PARKING_SYSTEM;

CREATE TABLE CAR
(
  id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  license VARCHAR(10) DEFAULT 'undefined',
  color VARCHAR(20) DEFAULT 'undefined',
  brand VARCHAR(50) DEFAULT 'undefined',
  -- owner_id BIGINT NOT NULL, -- no va mas
  comment TINYTEXT,
  disabled BOOLEAN NOT NULL DEFAULT FALSE
);

-- creo que no va mas
-- CREATE TABLE PERSON
-- (
--     id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
--     dni VARCHAR(10) NOT NULL DEFAULT 'undefined',
--     first_name VARCHAR(30) DEFAULT 'undefined',
--     last_name VARCHAR(30) DEFAULT 'undefined',
--     email VARCHAR(50) DEFAULT 'undefined',
--     phone VARCHAR(20) DEFAULT 'undefined',
--     gender CHAR(1) DEFAULT '-',
--     unique (dni)
-- );

-- DROP TABLE LOCATION;
CREATE TABLE LOCATION
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    floor TINYINT NOT NULL DEFAULT 0,
    sector VARCHAR(3) NOT NULL DEFAULT '-',
    number VARCHAR(5) NOT NULL DEFAULT '-',
    reserved BOOLEAN NOT NULL DEFAULT FALSE
    /* agregar para cocheras alquiladas. */
);

CREATE TABLE EMPLOYEE
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    rol VARCHAR(30) DEFAULT 'undefined',
    first_name VARCHAR(30) DEFAULT 'undefined',
    last_name VARCHAR(30) DEFAULT 'undefined',
    email VARCHAR(50) NOT NULL DEFAULT 'undefined',
    shift VARCHAR(15) NOT NULL DEFAULT 'undefined',
    password VARCHAR(20) NOT NULL DEFAULT 'pass',
    state VARCHAR(15) NOT NULL DEFAULT 'active'
);

insert  into EMPLOYEE   (rol,   first_name, last_name,  email, shift, password, state)
        values          ('employee', 'pepe','garcia','pepe', 'noche','pepe','active');


CREATE TABLE EMP_LOG
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    emp_id INT NOT NULL,
    log_in DATETIME DEFAULT NULL
);

CREATE TABLE PARKS
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    location_id INT NOT NULL,
    check_in DATETIME DEFAULT NULL,
    check_out DATETIME DEFAULT NULL,
    emp_id_chek_in INT NOT NULL,
    emp_id_chek_out INT DEFAULT NULL,
    cost NUMERIC(15,2) DEFAULT NULL
);
 SELECT * FROM PARKS WHERE car_id = 9 AND ISNULL(check_out) limit 1;


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

CREATE TABLE LOGED_IN
(
  emp_id BIGINT NOT NULL,
  on_date DATE DEFAULT NULL
  /*  se puede agregar lo de foreign
  FOREIGN KEY (emp_id) REFERENCES EMPLOYEE(id);*/
);


CREATE TABLE PRICE
(
  id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  hour NUMERIC(15,2) DEFAULT 0,
  half_day NUMERIC(15,2) DEFAULT 0,
  day NUMERIC(15,2) DEFAULT 0,
  on_date DATETIME DEFAULT NULL
);

insert into PRICE (hour, half_day, day, on_date) values (5,5,5,'2017-05-10 18:46:42');




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



id
first_name
last_name
email
shift
password
state


INSERT INTO LOCATION (  floor,
                        sector,
                        number,
                        reserved
                    )
                    VALUES (1,
                            'B',
                            '006',
                            0
                        );

SELECT  L.*
    FROM LOCATION AS L
    LEFT JOIN
        (SELECT * FROM PARKS WHERE ISNULL(check_out))AS P
            ON L.ID = P.location_id
    WHERE
        ISNULL(P.check_in)
    AND
        reserved = 0
        limit 1;


DELETE FROM PARKS WHERE id = 5;


UPDATE PARKS SET CHECK_OUT= NULL WHERE id = 4;





CREATE DEFINER=`root`@`localhost` PROCEDURE `BorrarPersona`(IN `idp` INT(18))
    NO SQL
delete from persona	WHERE id=idp$$



delete from CAR;
select * from CAR;
DROP PROCEDURE saveCar;

INSERT INTO CAR(license, owner_id)
VALUES
('sarasa', 36)
INSERT INTO CAR(id, license, owner_id)
VALUES
(94, 'sarasa', 36)
-- ('Vanina Strotsky', 20, 'vani@gmail.com'),
-- ('Juliana Stroensberg', 27, 'juli@gmail.com')
