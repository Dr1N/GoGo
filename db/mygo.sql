-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 09 2016 г., 13:52
-- Версия сервера: 5.5.48
-- Версия PHP: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mygo`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ads`
--

CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `region` varchar(32) DEFAULT NULL COMMENT 'Район города',
  `datetime` int(10) unsigned NOT NULL COMMENT 'Дата публикации',
  `update` int(10) unsigned NOT NULL COMMENT 'Дата обновления в базе',
  `title` varchar(70) NOT NULL,
  `gender` int(1) unsigned NOT NULL COMMENT 'Пол 0 - женский 1 - мужской',
  `age` int(10) unsigned NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `text` text,
  `url` varchar(256) NOT NULL,
  `state` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Состояние: 0 - есть, 1 - удалено',
  `description` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL COMMENT 'Country Id',
  `name` varchar(32) NOT NULL COMMENT 'Name of City',
  `prefix` varchar(32) NOT NULL COMMENT 'Prefix for URL',
  'url' varchar(255) NOT NULL COMMENT 'City Url',
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Cities from site';

-- --------------------------------------------------------

--
-- Структура таблицы `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL COMMENT 'Country name',
  `url` varchar(256) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='Countries from "go.com"';

--
-- Дамп данных таблицы `countries`
--

INSERT INTO `countries` (`id`, `name`, `url`) VALUES
(1, 'Украина', 'http://ukrgo.com/'),
(2, 'Россия', 'http://russgo.com/'),
(3, 'Беларусь', 'http://belarusgo.com/'),
(4, 'Казахстан', 'http://kazgo.com/'),
(5, 'Узбекистан', 'http://uzbekgo.com/'),
(6, 'Молдавия', 'http://mldgo.com/'),
(7, 'Грузия', 'http://gruzgo.com/'),
(8, 'Азербайджан', 'http://azbgo.com/'),
(9, 'Армения', 'http://arngo.com/'),
(10, 'Туркмения', 'http://turkmengo.com/'),
(11, 'Киргизия', 'http://kirgizgo.com/');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__cities` (`city_id`);

--
-- Индексы таблицы `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_cities_countries` (`country_id`);

--
-- Индексы таблицы `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `ads`
--
ALTER TABLE `ads`
  ADD CONSTRAINT `FK__cities` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `FK_cities_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
