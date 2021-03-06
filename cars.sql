-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost:3306
-- Время создания: Ноя 29 2013 г., 10:12
-- Версия сервера: 5.5.32
-- Версия PHP: 5.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `cars`
--
CREATE DATABASE IF NOT EXISTS `cars` DEFAULT CHARACTER SET cp1251 COLLATE cp1251_general_ci;
USE `cars`;

-- --------------------------------------------------------

--
-- Структура таблицы `buyers`
--

DROP TABLE IF EXISTS `buyers`;
CREATE TABLE IF NOT EXISTS `buyers` (
  `driver_license` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`driver_license`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- Очистить таблицу перед добавлением данных `buyers`
--

TRUNCATE TABLE `buyers`;
--
-- Дамп данных таблицы `buyers`
--

INSERT INTO `buyers` (`driver_license`, `name`, `surname`, `middlename`) VALUES
('123456', 'Иван', 'Иванов', 'Иванович'),
('365214', 'Пётр', 'Петров', 'Петрович');

-- --------------------------------------------------------

--
-- Структура таблицы `cars`
--

DROP TABLE IF EXISTS `cars`;
CREATE TABLE IF NOT EXISTS `cars` (
  `engine_number` varchar(255) NOT NULL,
  `model_id` int(11) NOT NULL,
  `created_date` date NOT NULL,
  `engine_type_id` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `color_id` int(11) NOT NULL,
  `byer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`engine_number`),
  KEY `model_id` (`model_id`),
  KEY `engine_type_id` (`engine_type_id`),
  KEY `color_id` (`color_id`),
  KEY `byer` (`byer`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- Очистить таблицу перед добавлением данных `cars`
--

TRUNCATE TABLE `cars`;
--
-- Дамп данных таблицы `cars`
--

INSERT INTO `cars` (`engine_number`, `model_id`, `created_date`, `engine_type_id`, `price`, `color_id`, `byer`) VALUES
('DX9875211554-20789', 1, '2010-05-20', 2, '760000', 1, NULL),
('TX125036790-19802', 1, '2010-10-20', 1, '780000', 1, NULL),
('VW234010102003-01', 3, '2015-12-20', 1, '1200000', 14, NULL),
('VW234010102004-98', 3, '2005-06-20', 2, '150000', 1, NULL),
('АВФ-9877416544', 3, '2010-01-01', 3, '50000', 8, '365214');

-- --------------------------------------------------------

--
-- Структура таблицы `colors`
--

DROP TABLE IF EXISTS `colors`;
CREATE TABLE IF NOT EXISTS `colors` (
  `color_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=19 ;

--
-- Очистить таблицу перед добавлением данных `colors`
--

TRUNCATE TABLE `colors`;
--
-- Дамп данных таблицы `colors`
--

INSERT INTO `colors` (`color_id`, `name`) VALUES
(1, 'черный'),
(2, 'белый'),
(4, 'синий'),
(6, 'красный'),
(8, 'зеленый'),
(14, 'серебристый'),
(16, 'хаки'),
(18, 'металлик');

-- --------------------------------------------------------

--
-- Структура таблицы `engines`
--

DROP TABLE IF EXISTS `engines`;
CREATE TABLE IF NOT EXISTS `engines` (
  `engine_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`engine_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=4 ;

--
-- Очистить таблицу перед добавлением данных `engines`
--

TRUNCATE TABLE `engines`;
--
-- Дамп данных таблицы `engines`
--

INSERT INTO `engines` (`engine_id`, `type`) VALUES
(1, 'TDI'),
(2, 'Турбодизель'),
(3, 'карбюраторный');

-- --------------------------------------------------------

--
-- Структура таблицы `models`
--

DROP TABLE IF EXISTS `models`;
CREATE TABLE IF NOT EXISTS `models` (
  `model_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  PRIMARY KEY (`model_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=5 ;

--
-- Очистить таблицу перед добавлением данных `models`
--

TRUNCATE TABLE `models`;
--
-- Дамп данных таблицы `models`
--

INSERT INTO `models` (`model_id`, `name`, `country`) VALUES
(1, 'Subaru Forester', 'Япония'),
(2, 'Subaru Impreza', 'Япония'),
(3, 'VolksWagen Golf', 'Германия'),
(4, 'ВАЗ 2101', 'Россия');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_8` FOREIGN KEY (`engine_type_id`) REFERENCES `engines` (`engine_id`),
  ADD CONSTRAINT `cars_ibfk_4` FOREIGN KEY (`byer`) REFERENCES `buyers` (`driver_license`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `cars_ibfk_6` FOREIGN KEY (`color_id`) REFERENCES `colors` (`color_id`),
  ADD CONSTRAINT `cars_ibfk_7` FOREIGN KEY (`model_id`) REFERENCES `models` (`model_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
