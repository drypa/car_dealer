-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost:3306
-- Время создания: Ноя 27 2013 г., 10:18
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
-- Дамп данных таблицы `cars`
--

INSERT INTO `cars` (`engine_number`, `model_id`, `created_date`, `engine_type_id`, `price`, `color_id`, `byer`) VALUES
('DX9875211554-20789', 1, '2010-05-20', 2, '760000', 1, NULL),
('TX125036790-19802', 1, '2010-10-20', 1, '780000', 1, '123456'),
('VW234010102003-01', 3, '2015-12-20', 1, '1200000', 14, NULL),
('VW234010102004-98', 3, '2005-06-20', 2, '150000', 1, NULL),
('АВФ-9877416544', 4, '2010-01-01', 3, '50000', 8, '365214');

-- --------------------------------------------------------

--
-- Структура таблицы `colors`
--

DROP TABLE IF EXISTS `colors`;
CREATE TABLE IF NOT EXISTS `colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `colors`
--

INSERT INTO `colors` (`id`, `name`) VALUES
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `engines`
--

INSERT INTO `engines` (`id`, `type`) VALUES
(1, 'TDI'),
(2, 'Турбодизель'),
(3, 'карбюраторный');

-- --------------------------------------------------------

--
-- Структура таблицы `engine_gasoline`
--

DROP TABLE IF EXISTS `engine_gasoline`;
CREATE TABLE IF NOT EXISTS `engine_gasoline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `engine_type` int(11) NOT NULL,
  `gasoline_type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `engine_type` (`engine_type`,`gasoline_type`),
  KEY `engine_type_2` (`engine_type`),
  KEY `gasoline_type` (`gasoline_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `engine_gasoline`
--

INSERT INTO `engine_gasoline` (`id`, `engine_type`, `gasoline_type`) VALUES
(1, 0, 1),
(2, 0, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `gasoline_types`
--

DROP TABLE IF EXISTS `gasoline_types`;
CREATE TABLE IF NOT EXISTS `gasoline_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `gasoline_types`
--

INSERT INTO `gasoline_types` (`id`, `name`) VALUES
(1, 'АИ 95'),
(2, 'АИ 92'),
(3, 'Диз.топливо'),
(4, 'АИ 98');

-- --------------------------------------------------------

--
-- Структура таблицы `models`
--

DROP TABLE IF EXISTS `models`;
CREATE TABLE IF NOT EXISTS `models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `models`
--

INSERT INTO `models` (`id`, `name`, `country`) VALUES
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
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`),
  ADD CONSTRAINT `cars_ibfk_2` FOREIGN KEY (`engine_type_id`) REFERENCES `gasoline_types` (`id`),
  ADD CONSTRAINT `cars_ibfk_3` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`),
  ADD CONSTRAINT `cars_ibfk_4` FOREIGN KEY (`byer`) REFERENCES `buyers` (`driver_license`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ограничения внешнего ключа таблицы `engine_gasoline`
--
ALTER TABLE `engine_gasoline`
  ADD CONSTRAINT `engine_gasoline_ibfk_1` FOREIGN KEY (`gasoline_type`) REFERENCES `gasoline_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
