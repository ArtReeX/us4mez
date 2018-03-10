--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 7.2.58.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 05.01.2018 17:55:26
-- Версия сервера: 5.5.53
-- Версия клиента: 4.1
--


-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установить режим SQL (SQL mode)
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

-- 
-- Установка базы данных по умолчанию
--
USE us4mez;

--
-- Описание для таблицы authors
--
DROP TABLE IF EXISTS authors;
CREATE TABLE authors (
  author_id INT(11) NOT NULL AUTO_INCREMENT,
  author_login VARCHAR(100) NOT NULL,
  author_password VARCHAR(100) NOT NULL,
  author_name VARCHAR(100) NOT NULL,
  author_city VARCHAR(100) NOT NULL,
  author_phone VARCHAR(100) NOT NULL,
  author_mail VARCHAR(100) NOT NULL,
  PRIMARY KEY (author_id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы categories
--
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  category_id INT(11) NOT NULL AUTO_INCREMENT,
  category_name VARCHAR(100) NOT NULL,
  category_info TEXT NOT NULL,
  PRIMARY KEY (category_id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы types
--
DROP TABLE IF EXISTS types;
CREATE TABLE types (
  type_id INT(11) NOT NULL AUTO_INCREMENT,
  type_name VARCHAR(100) NOT NULL,
  PRIMARY KEY (type_id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы ads
--
DROP TABLE IF EXISTS ads;
CREATE TABLE ads (
  ad_id INT(11) NOT NULL AUTO_INCREMENT,
  ad_caption VARCHAR(100) NOT NULL,
  ad_text TEXT NOT NULL,
  ad_photo VARCHAR(100) DEFAULT NULL,
  ad_type INT(11) NOT NULL,
  ad_category INT(11) NOT NULL,
  ad_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ad_author INT(11) NOT NULL,
  PRIMARY KEY (ad_id),
  INDEX ad_author (ad_author),
  INDEX ad_category (ad_category),
  INDEX ad_type (ad_type),
  CONSTRAINT ads_ibfk_1 FOREIGN KEY (ad_author)
    REFERENCES authors(author_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT ads_ibfk_2 FOREIGN KEY (ad_type)
    REFERENCES types(type_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT ads_ibfk_3 FOREIGN KEY (ad_category)
    REFERENCES categories(category_id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

-- 
-- Вывод данных для таблицы authors
--

-- Таблица us4mez.authors не содержит данных

-- 
-- Вывод данных для таблицы categories
--

-- Таблица us4mez.categories не содержит данных

-- 
-- Вывод данных для таблицы types
--

-- Таблица us4mez.types не содержит данных

-- 
-- Вывод данных для таблицы ads
--

-- Таблица us4mez.ads не содержит данных

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;