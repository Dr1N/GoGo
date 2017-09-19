-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 19 2017 г., 15:22
-- Версия сервера: 5.7.19-0ubuntu0.16.04.1
-- Версия PHP: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `gouser_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ads`
--

CREATE TABLE `ads` (
  `id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `title` varchar(1024) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  `gender` int(1) DEFAULT NULL,
  `age` int(2) DEFAULT NULL,
  `weight` int(2) DEFAULT NULL,
  `height` int(3) DEFAULT NULL,
  `text` text,
  `parsed` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ad_phone_relation`
--

CREATE TABLE `ad_phone_relation` (
  `id` int(11) NOT NULL,
  `ad_id` int(11) NOT NULL,
  `phone_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL COMMENT 'Country Id',
  `name` varchar(32) NOT NULL COMMENT 'Name of City',
  `url` varchar(255) NOT NULL COMMENT 'City Url',
  `is_enabled` int(1) NOT NULL DEFAULT '1',
  `pages` int(11) DEFAULT NULL,
  `save_image` int(1) NOT NULL DEFAULT '0',
  `parse_urls` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Cities from site';

--
-- Дамп данных таблицы `cities`
--

INSERT INTO `cities` (`id`, `country_id`, `name`, `url`, `is_enabled`, `pages`, `save_image`, `parse_urls`) VALUES
(1, 1, 'Белая Церковь', 'http://bc.ukrgo.com/', 1, NULL, 0, 1),
(2, 1, 'Борисполь', 'http://borispol.ukrgo.com/', 1, NULL, 0, 1),
(3, 1, 'Бровары', 'http://brovary.ukrgo.com/', 1, NULL, 0, 1),
(4, 1, 'Буча', 'http://bucha.ukrgo.com/', 1, NULL, 0, 1),
(5, 1, 'Киев', 'http://kiev.ukrgo.com/', 1, NULL, 0, 1),
(6, 1, 'Переяслав-Хмельницкий', 'http://ph.ukrgo.com/', 1, NULL, 0, 1),
(7, 1, 'Славутич', 'http://slavutich.ukrgo.com/', 1, NULL, 0, 1),
(8, 1, 'Фастов', 'http://fastov.ukrgo.com/', 1, NULL, 0, 1),
(9, 1, 'Евпатория', 'http://evpatoria.ukrgo.com/', 1, NULL, 0, 1),
(10, 1, 'Керчь', 'http://kerch.ukrgo.com/', 1, NULL, 0, 1),
(11, 1, 'Севастополь', 'http://sevastopol.ukrgo.com/', 1, NULL, 0, 1),
(12, 1, 'Симферополь', 'http://simferopol.ukrgo.com/', 1, NULL, 0, 1),
(13, 1, 'Феодосия', 'http://feodosia.ukrgo.com/', 1, NULL, 0, 1),
(14, 1, 'Ялта', 'http://yalta.ukrgo.com/', 1, NULL, 0, 1),
(15, 1, 'Винница', 'http://vinnica.ukrgo.com/', 1, NULL, 0, 1),
(16, 1, 'Жмеринка', 'http://zhmerinka.ukrgo.com/', 1, NULL, 0, 1),
(17, 1, 'Могилёв-Подольский', 'http://mogilev.ukrgo.com/', 1, NULL, 0, 1),
(18, 1, 'Владимир-Волынский', 'http://vv.ukrgo.com/', 1, NULL, 0, 1),
(19, 1, 'Ковель', 'http://kovel.ukrgo.com/', 1, NULL, 0, 1),
(20, 1, 'Луцк', 'http://lutsk.ukrgo.com/', 1, NULL, 0, 1),
(21, 1, 'Нововолынск', 'http://nv.ukrgo.com/', 1, NULL, 0, 1),
(22, 1, 'Днепродзержинск', 'http://dz.ukrgo.com/', 1, NULL, 0, 1),
(23, 1, 'Днепропетровск', 'http://dp.ukrgo.com/', 1, NULL, 0, 1),
(24, 1, 'Кривой Рог', 'http://kr.ukrgo.com/', 1, NULL, 0, 1),
(25, 1, 'Марганец', 'http://marganec.ukrgo.com/', 1, NULL, 0, 1),
(26, 1, 'Никополь', 'http://nikopol.ukrgo.com/', 1, NULL, 0, 1),
(27, 1, 'Новомосковск', 'http://nm.ukrgo.com/', 1, NULL, 0, 1),
(28, 1, 'Павлоград', 'http://pavlograd.ukrgo.com/', 1, NULL, 0, 1),
(29, 1, 'Горловка', 'http://gorlovka.ukrgo.com/', 1, NULL, 0, 1),
(30, 1, 'Донецк', 'http://donetsk.ukrgo.com/', 1, NULL, 0, 1),
(31, 1, 'Енакиево', 'http://enakievo.ukrgo.com/', 1, NULL, 0, 1),
(32, 1, 'Краматорск', 'http://kramatorsk.ukrgo.com/', 1, NULL, 0, 1),
(33, 1, 'Макеевка', 'http://makeevka.ukrgo.com/', 1, NULL, 0, 1),
(34, 1, 'Мариуполь', 'http://mariupol.ukrgo.com/', 1, NULL, 0, 1),
(35, 1, 'Новоазовск', 'http://novoazovsk.ukrgo.com/', 1, NULL, 0, 1),
(36, 1, 'Славянск', 'http://slavyansk.ukrgo.com/', 1, NULL, 0, 1),
(37, 1, 'Харцызск', 'http://khartsyzk.ukrgo.com/', 1, NULL, 0, 1),
(38, 1, 'Бердичев', 'http://berdichev.ukrgo.com/', 1, NULL, 0, 1),
(39, 1, 'Житомир', 'http://zhytomir.ukrgo.com/', 1, NULL, 0, 1),
(40, 1, 'Коростень', 'http://korosten.ukrgo.com/', 1, NULL, 0, 1),
(41, 1, 'Мукачево', 'http://mukachevo.ukrgo.com/', 1, NULL, 0, 1),
(42, 1, 'Ужгород', 'http://uzhgorod.ukrgo.com/', 1, NULL, 0, 1),
(43, 1, 'Хуст', 'http://hust.ukrgo.com/', 1, NULL, 0, 1),
(44, 1, 'Бердянск', 'http://berdyansk.ukrgo.com/', 1, NULL, 0, 1),
(45, 1, 'Запорожье', 'http://zp.ukrgo.com/', 1, NULL, 0, 1),
(46, 1, 'Мелитополь', 'http://melitopol.ukrgo.com/', 1, NULL, 0, 1),
(47, 1, 'Энергодар', 'http://energo.ukrgo.com/', 1, NULL, 0, 1),
(48, 1, 'Ивано-Франковск', 'http://if.ukrgo.com/', 1, NULL, 0, 1),
(49, 1, 'Калуш', 'http://kalush.ukrgo.com/', 1, NULL, 0, 1),
(50, 1, 'Коломыя', 'http://kolomiya.ukrgo.com/', 1, NULL, 0, 1),
(51, 1, 'Александрия', 'http://alexandria.ukrgo.com/', 1, NULL, 0, 1),
(52, 1, 'Кировоград', 'http://kirovograd.ukrgo.com/', 1, NULL, 0, 1),
(53, 1, 'Светловодск', 'http://svetlovodsk.ukrgo.com/', 1, NULL, 0, 1),
(54, 1, 'Алчевск', 'http://alchevsk.ukrgo.com/', 1, NULL, 0, 1),
(55, 1, 'Лисичанск', 'http://lisichansk.ukrgo.com/', 1, NULL, 0, 1),
(56, 1, 'Луганск', 'http://lugansk.ukrgo.com/', 1, NULL, 0, 1),
(57, 1, 'Первомайск', 'http://pervomaisk.ukrgo.com/', 1, NULL, 0, 1),
(58, 1, 'Рубежное', 'http://rubezhnoe.ukrgo.com/', 1, NULL, 0, 1),
(59, 1, 'Северодонецк', 'http://sd.ukrgo.com/', 1, NULL, 0, 1),
(60, 1, 'Львов', 'http://lvov.ukrgo.com/', 1, NULL, 0, 1),
(61, 1, 'Вознесенск', 'http://voznesensk.ukrgo.com/', 1, NULL, 0, 1),
(62, 1, 'Николаев', 'http://nikolaev.ukrgo.com/', 1, NULL, 0, 1),
(63, 1, 'Очаков', 'http://ochakov.ukrgo.com/', 1, NULL, 0, 1),
(64, 1, 'Южноукраинск', 'http://uk.ukrgo.com/', 1, NULL, 0, 1),
(65, 1, 'Белгород-Днестровский', 'http://bd.ukrgo.com/', 1, NULL, 0, 1),
(66, 1, 'Измаил', 'http://izmail.ukrgo.com/', 1, NULL, 0, 1),
(67, 1, 'Ильичёвск', 'http://il.ukrgo.com/', 1, NULL, 0, 1),
(68, 1, 'Одесса', 'http://odessa.ukrgo.com/', 1, NULL, 0, 1),
(69, 1, 'Комсомольск', 'http://komsomolsk.ukrgo.com/', 1, NULL, 0, 1),
(70, 1, 'Кременчуг', 'http://kremenchug.ukrgo.com/', 1, NULL, 0, 1),
(71, 1, 'Лубны', 'http://lubny.ukrgo.com/', 1, NULL, 0, 1),
(72, 1, 'Миргород', 'http://mirgorod.ukrgo.com/', 1, NULL, 0, 1),
(73, 1, 'Полтава', 'http://poltava.ukrgo.com/', 1, NULL, 0, 1),
(74, 1, 'Дубно', 'http://dubno.ukrgo.com/', 1, NULL, 0, 1),
(75, 1, 'Кузнецовск', 'http://kz.ukrgo.com/', 1, NULL, 0, 1),
(76, 1, 'Острог', 'http://ostrog.ukrgo.com/', 1, NULL, 0, 1),
(77, 1, 'Ровно', 'http://rovno.ukrgo.com/', 1, NULL, 0, 1),
(78, 1, 'Ахтырка', 'http://ak.ukrgo.com/', 1, NULL, 0, 1),
(79, 1, 'Конотоп', 'http://konotop.ukrgo.com/', 1, NULL, 0, 1),
(80, 1, 'Лебедин', 'http://lebedin.ukrgo.com/', 1, NULL, 0, 1),
(81, 1, 'Сумы', 'http://summy.ukrgo.com/', 1, NULL, 0, 1),
(82, 1, 'Шостка', 'http://shostka.ukrgo.com/', 1, NULL, 0, 1),
(83, 1, 'Тернополь', 'http://ternopol.ukrgo.com/', 1, NULL, 0, 1),
(84, 1, 'Изюм', 'http://izyum.ukrgo.com/', 1, NULL, 0, 1),
(85, 1, 'Купянск', 'http://kupyansk.ukrgo.com/', 1, NULL, 0, 1),
(86, 1, 'Харьков', 'http://kharkov.ukrgo.com/', 1, NULL, 0, 1),
(87, 1, 'Чугуев', 'http://chuguev.ukrgo.com/', 1, NULL, 0, 1),
(88, 1, 'Каховка', 'http://kahovka.ukrgo.com/', 1, NULL, 0, 1),
(89, 1, 'Новая Каховка', 'http://nkahovka.ukrgo.com/', 1, NULL, 0, 1),
(90, 1, 'Херсон', 'http://kherson.ukrgo.com/', 1, NULL, 0, 1),
(91, 1, 'Каменец-Подольский', 'http://kp.ukrgo.com/', 1, NULL, 0, 1),
(92, 1, 'Хмельницкий', 'http://khm.ukrgo.com/', 1, NULL, 0, 1),
(93, 1, 'Шепетовка', 'http://shepetovka.ukrgo.com/', 1, NULL, 0, 1),
(94, 1, 'Золотоноша', 'http://zolotonosha.ukrgo.com/', 1, NULL, 0, 1),
(95, 1, 'Корсунь-Шевченковский', 'http://k-sh.ukrgo.com/', 1, NULL, 0, 1),
(96, 1, 'Смела', 'http://smela.ukrgo.com/', 1, NULL, 0, 1),
(97, 1, 'Умань', 'http://uman.ukrgo.com/', 1, NULL, 0, 1),
(98, 1, 'Черкассы', 'http://cherkassy.ukrgo.com/', 1, NULL, 0, 1),
(99, 1, 'Нежин', 'http://nezhin.ukrgo.com/', 1, NULL, 0, 1),
(100, 1, 'Прилуки', 'http://priluki.ukrgo.com/', 1, NULL, 0, 1),
(101, 1, 'Чернигов', 'http://chernigov.ukrgo.com/', 1, NULL, 0, 1),
(102, 1, 'Новоднестровск', 'http://nvd.ukrgo.com/', 1, NULL, 0, 1),
(103, 1, 'Черновцы', 'http://chernovcy.ukrgo.com/', 1, NULL, 0, 1),
(104, 2, 'Москва', 'http://moscow.russgo.com/', 1, NULL, 0, 1),
(105, 2, 'Санкт-Петербург', 'http://sp.russgo.com/', 1, NULL, 0, 1),
(106, 2, 'Благовещенск', 'http://blk.russgo.com/', 1, NULL, 0, 1),
(107, 2, 'Владивосток', 'http://vladivostok.russgo.com/', 1, NULL, 0, 1),
(108, 2, 'Комсомольск-на-Амуре', 'http://ka.russgo.com/', 1, NULL, 0, 1),
(109, 2, 'Магадан', 'http://magadan.russgo.com/', 1, NULL, 0, 1),
(110, 2, 'Находка', 'http://nahodka.russgo.com/', 1, NULL, 0, 1),
(111, 2, 'Петропавловск-Камчатский', 'http://pk.russgo.com/', 1, NULL, 0, 1),
(112, 2, 'Хабаровск', 'http://habarovsk.russgo.com/', 1, NULL, 0, 1),
(113, 2, 'Южно-Сахалинск', 'http://ys.russgo.com/', 1, NULL, 0, 1),
(114, 2, 'Якутск', 'http://yakutsk.russgo.com/', 1, NULL, 0, 1),
(115, 2, 'Альметьевск', 'http://almetevsk.russgo.com/', 1, NULL, 0, 1),
(116, 2, 'Арзамас', 'http://arzamas.russgo.com/', 1, NULL, 0, 1),
(117, 2, 'Балаково', 'http://balakovo.russgo.com/', 1, NULL, 0, 1),
(118, 2, 'Березники', 'http://berezniki.russgo.com/', 1, NULL, 0, 1),
(119, 2, 'Дзержинск', 'http://dzerzhinsk.russgo.com/', 1, NULL, 0, 1),
(120, 2, 'Димитровград', 'http://dimitrovgrad.russgo.com/', 1, NULL, 0, 1),
(121, 2, 'Ижевск', 'http://izhevsk.russgo.com/', 1, NULL, 0, 1),
(122, 2, 'Йошкар-Ола', 'http://yola.russgo.com/', 1, NULL, 0, 1),
(123, 2, 'Казань', 'http://kazan.russgo.com/', 1, NULL, 0, 1),
(124, 2, 'Киров', 'http://kirov.russgo.com/', 1, NULL, 0, 1),
(125, 2, 'Набережные Челны', 'http://nchelny.russgo.com/', 1, NULL, 0, 1),
(126, 2, 'Нефтекамск', 'http://neftekamsk.russgo.com/', 1, NULL, 0, 1),
(127, 2, 'Нижнекамск', 'http://nkamsk.russgo.com/', 1, NULL, 0, 1),
(128, 2, 'Нижний Новгород', 'http://nn.russgo.com/', 1, NULL, 0, 1),
(129, 2, 'Новотроицк', 'http://novotroick.russgo.com/', 1, NULL, 0, 1),
(130, 2, 'Оренбург', 'http://orenburg.russgo.com/', 1, NULL, 0, 1),
(131, 2, 'Орск', 'http://orsk.russgo.com/', 1, NULL, 0, 1),
(132, 2, 'Пенза', 'http://penza.russgo.com/', 1, NULL, 0, 1),
(133, 2, 'Пермь', 'http://perm.russgo.com/', 1, NULL, 0, 1),
(134, 2, 'Салават', 'http://salavat.russgo.com/', 1, NULL, 0, 1),
(135, 2, 'Самара', 'http://samara.russgo.com/', 1, NULL, 0, 1),
(136, 2, 'Саранск', 'http://saransk.russgo.com/', 1, NULL, 0, 1),
(137, 2, 'Саратов', 'http://saratov.russgo.com/', 1, NULL, 0, 1),
(138, 2, 'Стерлитамак', 'http://stamak.russgo.com/', 1, NULL, 0, 1),
(139, 2, 'Сызрань', 'http://syzran.russgo.com/', 1, NULL, 0, 1),
(140, 2, 'Тольятти', 'http://tolyatti.russgo.com/', 1, NULL, 0, 1),
(141, 2, 'Ульяновск', 'http://ulyanovsk.russgo.com/', 1, NULL, 0, 1),
(142, 2, 'Уфа', 'http://ufa.russgo.com/', 1, NULL, 0, 1),
(143, 2, 'Чебоксары', 'http://cheboksary.russgo.com/', 1, NULL, 0, 1),
(144, 2, 'Энгельс', 'http://engels.russgo.com/', 1, NULL, 0, 1),
(145, 2, 'Архангельск', 'http://arhangelsk.russgo.com/', 1, NULL, 0, 1),
(146, 2, 'Великие Луки', 'http://velikie.russgo.com/', 1, NULL, 0, 1),
(147, 2, 'Великий Новгород', 'http://vnovgorod.russgo.com/', 1, NULL, 0, 1),
(148, 2, 'Вологда', 'http://vologda.russgo.com/', 1, NULL, 0, 1),
(149, 2, 'Калининград', 'http://kaliningrad.russgo.com/', 1, NULL, 0, 1),
(150, 2, 'Мурманск', 'http://murmansk.russgo.com/', 1, NULL, 0, 1),
(151, 2, 'Петрозаводск', 'http://petrozavodsk.russgo.com/', 1, NULL, 0, 1),
(152, 2, 'Псков', 'http://pskov.russgo.com/', 1, NULL, 0, 1),
(153, 2, 'Северодвинск', 'http://severodvinsk.russgo.com/', 1, NULL, 0, 1),
(154, 2, 'Сыктывкар', 'http://syktyvkar.russgo.com/', 1, NULL, 0, 1),
(155, 2, 'Ухта', 'http://uhta.russgo.com/', 1, NULL, 0, 1),
(156, 2, 'Череповец', 'http://cherepovec.russgo.com/', 1, NULL, 0, 1),
(157, 2, 'Абакан', 'http://abakan.russgo.com/', 1, NULL, 0, 1),
(158, 2, 'Ангарск', 'http://angarsk.russgo.com/', 1, NULL, 0, 1),
(159, 2, 'Барнаул', 'http://barnaul.russgo.com/', 1, NULL, 0, 1),
(160, 2, 'Бийск', 'http://biisk.russgo.com/', 1, NULL, 0, 1),
(161, 2, 'Братск', 'http://bratsk.russgo.com/', 1, NULL, 0, 1),
(162, 2, 'Горно-Алтайск', 'http://galtaisk.russgo.com/', 1, NULL, 0, 1),
(163, 2, 'Иркутск', 'http://irkutsk.russgo.com/', 1, NULL, 0, 1),
(164, 2, 'Кемерово', 'http://kemerovo.russgo.com/', 1, NULL, 0, 1),
(165, 2, 'Красноярск', 'http://krasnoyarsk.russgo.com/', 1, NULL, 0, 1),
(166, 2, 'Кызыл', 'http://kyzyl.russgo.com/', 1, NULL, 0, 1),
(167, 2, 'Новокузнецк', 'http://novokusneck.russgo.com/', 1, NULL, 0, 1),
(168, 2, 'Новосибирск', 'http://ns.russgo.com/', 1, NULL, 0, 1),
(169, 2, 'Норильск', 'http://norilsk.russgo.com/', 1, NULL, 0, 1),
(170, 2, 'Омск', 'http://omsk.russgo.com/', 1, NULL, 0, 1),
(171, 2, 'Прокопьевск', 'http://prokopevsk.russgo.com/', 1, NULL, 0, 1),
(172, 2, 'Рубцовск', 'http://rubcovsk.russgo.com/', 1, NULL, 0, 1),
(173, 2, 'Северск', 'http://seversk.russgo.com/', 1, NULL, 0, 1),
(174, 2, 'Томск', 'http://tomsk.russgo.com/', 1, NULL, 0, 1),
(175, 2, 'Улан-Удэ', 'http://ulanude.russgo.com/', 1, NULL, 0, 1),
(176, 2, 'Чита', 'http://chita.russgo.com/', 1, NULL, 0, 1),
(177, 2, 'Екатеринбург', 'http://ek.russgo.com/', 1, NULL, 0, 1),
(178, 2, 'Златоуст', 'http://zlatoust.russgo.com/', 1, NULL, 0, 1),
(179, 2, 'Каменск-Уральский', 'http://ku.russgo.com/', 1, NULL, 0, 1),
(180, 2, 'Курган', 'http://kurgan.russgo.com/', 1, NULL, 0, 1),
(181, 2, 'Магнитогорск', 'http://magnitogorsk.russgo.com/', 1, NULL, 0, 1),
(182, 2, 'Миасс', 'http://miass.russgo.com/', 1, NULL, 0, 1),
(183, 2, 'Нефтеюганск', 'http://nyugansk.russgo.com/', 1, NULL, 0, 1),
(184, 2, 'Нижневартовск', 'http://nvartovsk.russgo.com/', 1, NULL, 0, 1),
(185, 2, 'Нижний Тагил', 'http://ntagil.russgo.com/', 1, NULL, 0, 1),
(186, 2, 'Новый Уренгой', 'http://nurengoi.russgo.com/', 1, NULL, 0, 1),
(187, 2, 'Ноябрьск', 'http://noyabrsk.russgo.com/', 1, NULL, 0, 1),
(188, 2, 'Первоуральск', 'http://pervouralsk.russgo.com/', 1, NULL, 0, 1),
(189, 2, 'Полевской', 'http://polevskoy.russgo.com/', 1, NULL, 0, 1),
(190, 2, 'Сургут', 'http://surgut.russgo.com/', 1, NULL, 0, 1),
(191, 2, 'Тобольск', 'http://tobolsk.russgo.com/', 1, NULL, 0, 1),
(192, 2, 'Тюмень', 'http://tyumen.russgo.com/', 1, NULL, 0, 1),
(193, 2, 'Челябинск', 'http://chelyabinsk.russgo.com/', 1, NULL, 0, 1),
(194, 2, 'Балашиха', 'http://balashiha.russgo.com/', 1, NULL, 0, 1),
(195, 2, 'Белгород', 'http://belgorod.russgo.com/', 1, NULL, 0, 1),
(196, 2, 'Брянск', 'http://bryansk.russgo.com/', 1, NULL, 0, 1),
(197, 2, 'Владимир', 'http://vladimir.russgo.com/', 1, NULL, 0, 1),
(198, 2, 'Воронеж', 'http://voronezh.russgo.com/', 1, NULL, 0, 1),
(199, 2, 'Иваново', 'http://ivanovo.russgo.com/', 1, NULL, 0, 1),
(200, 2, 'Калуга', 'http://kaluga.russgo.com/', 1, NULL, 0, 1),
(201, 2, 'Королев', 'http://korolev.russgo.com/', 1, NULL, 0, 1),
(202, 2, 'Кострома', 'http://kostroma.russgo.com/', 1, NULL, 0, 1),
(203, 2, 'Курск', 'http://kursk.russgo.com/', 1, NULL, 0, 1),
(204, 2, 'Липецк', 'http://lipeck.russgo.com/', 1, NULL, 0, 1),
(205, 2, 'Люберцы', 'http://lyubercy.russgo.com/', 1, NULL, 0, 1),
(206, 2, 'Мытищи', 'http://mytishi.russgo.com/', 1, NULL, 0, 1),
(207, 2, 'Новомосковск', 'http://nmoskovsk.russgo.com/', 1, NULL, 0, 1),
(208, 2, 'Орел', 'http://orel.russgo.com/', 1, NULL, 0, 1),
(209, 2, 'Подольск', 'http://podolsk.russgo.com/', 1, NULL, 0, 1),
(210, 2, 'Рыбинск', 'http://rybinsk.russgo.com/', 1, NULL, 0, 1),
(211, 2, 'Рязань', 'http://ryazan.russgo.com/', 1, NULL, 0, 1),
(212, 2, 'Смоленск', 'http://smolensk.russgo.com/', 1, NULL, 0, 1),
(213, 2, 'Старый Оскол', 'http://soskol.russgo.com/', 1, NULL, 0, 1),
(214, 2, 'Тамбов', 'http://tambov.russgo.com/', 1, NULL, 0, 1),
(215, 2, 'Тверь', 'http://tver.russgo.com/', 1, NULL, 0, 1),
(216, 2, 'Тула', 'http://tula.russgo.com/', 1, NULL, 0, 1),
(217, 2, 'Химки ', 'http://himki.russgo.com/', 1, NULL, 0, 1),
(218, 2, 'Ярославль', 'http://yaroslavl.russgo.com/', 1, NULL, 0, 1),
(219, 2, 'Армавир', 'http://armavir.russgo.com/', 1, NULL, 0, 1),
(220, 2, 'Астрахань', 'http://astrahan.russgo.com/', 1, NULL, 0, 1),
(221, 2, 'Владикавказ', 'http://vladikavkaz.russgo.com/', 1, NULL, 0, 1),
(222, 2, 'Волгоград', 'http://volgograd.russgo.com/', 1, NULL, 0, 1),
(223, 2, 'Волгодонск', 'http://volgodonsk.russgo.com/', 1, NULL, 0, 1),
(224, 2, 'Волжский', 'http://volzhskiy.russgo.com/', 1, NULL, 0, 1),
(225, 2, 'Грозный', 'http://groznyi.russgo.com/', 1, NULL, 0, 1),
(226, 2, 'Краснодар', 'http://krasnodar.russgo.com/', 1, NULL, 0, 1),
(227, 2, 'Майкоп', 'http://maikop.russgo.com/', 1, NULL, 0, 1),
(228, 2, 'Махачкала', 'http://mahachkala.russgo.com/', 1, NULL, 0, 1),
(229, 2, 'Назрань', 'http://nazran.russgo.com/', 1, NULL, 0, 1),
(230, 2, 'Нальчик', 'http://nalchik.russgo.com/', 1, NULL, 0, 1),
(231, 2, 'Новороссийск', 'http://nrossiysk.russgo.com/', 1, NULL, 0, 1),
(232, 2, 'Новочеркасск', 'http://novocherkassk.russgo.com/', 1, NULL, 0, 1),
(233, 2, 'Пятигорск', 'http://pyatigorsk.russgo.com/', 1, NULL, 0, 1),
(234, 2, 'Ростов-на-Дону', 'http://rnd.russgo.com/', 1, NULL, 0, 1),
(235, 2, 'Сочи', 'http://sochi.russgo.com/', 1, NULL, 0, 1),
(236, 2, 'Ставрополь', 'http://stavropol.russgo.com/', 1, NULL, 0, 1),
(237, 2, 'Таганрог', 'http://taganrog.russgo.com/', 1, NULL, 0, 1),
(238, 2, 'Хасавюрт', 'http://hasavyurt.russgo.com/', 1, NULL, 0, 1),
(239, 2, 'Черкесск', 'http://cherkessk.russgo.com/', 1, NULL, 0, 1),
(240, 2, 'Шахты', 'http://shahty.russgo.com/', 1, NULL, 0, 1),
(241, 2, 'Элиста', 'http://elista.russgo.com/', 1, NULL, 0, 1),
(242, 3, 'Борисов', 'http://borisov.belarusgo.com/', 1, NULL, 0, 1),
(243, 3, 'Минск', 'http://minsk.belarusgo.com/', 1, NULL, 0, 1),
(244, 3, 'Молодечно', 'http://mo.belarusgo.com/', 1, NULL, 0, 1),
(245, 3, 'Солигорск', 'http://soligorsk.belarusgo.com/', 1, NULL, 0, 1),
(246, 3, 'Барановичи', 'http://baranovichy.belarusgo.com/', 1, NULL, 0, 1),
(247, 3, 'Брест', 'http://brest.belarusgo.com/', 1, NULL, 0, 1),
(248, 3, 'Пинск', 'http://pinsk.belarusgo.com/', 1, NULL, 0, 1),
(249, 3, 'Витебск', 'http://vitebsk.belarusgo.com/', 1, NULL, 0, 1),
(250, 3, 'Новополоцк', 'http://nk.belarusgo.com/', 1, NULL, 0, 1),
(251, 3, 'Орша', 'http://orsha.belarusgo.com/', 1, NULL, 0, 1),
(252, 3, 'Полоцк', 'http://pk.belarusgo.com/', 1, NULL, 0, 1),
(253, 3, 'Гомель', 'http://gomel.belarusgo.com/', 1, NULL, 0, 1),
(254, 3, 'Жлобин', 'http://zhlobin.belarusgo.com/', 1, NULL, 0, 1),
(255, 3, 'Мозырь', 'http://mozyr.belarusgo.com/', 1, NULL, 0, 1),
(256, 3, 'Светлогорск', 'http://sk.belarusgo.com/', 1, NULL, 0, 1),
(257, 3, 'Гродно', 'http://grodno.belarusgo.com/', 1, NULL, 0, 1),
(258, 3, 'Лида', 'http://lida.belarusgo.com/', 1, NULL, 0, 1),
(259, 3, 'Бобруйск', 'http://bk.belarusgo.com/', 1, NULL, 0, 1),
(260, 3, 'Могилёв', 'http://mogilev.belarusgo.com/', 1, NULL, 0, 1),
(261, 4, 'Астана', 'http://astana.kazgo.com/', 1, NULL, 0, 1),
(262, 4, 'Алматы', 'http://almaata.kazgo.com/', 1, NULL, 0, 1),
(263, 4, 'Кокчетав', 'http://konchetav.kazgo.com/', 1, NULL, 0, 1),
(264, 4, 'Актюбинск', 'http://aktubinsk.kazgo.com/', 1, NULL, 0, 1),
(265, 4, 'Атырау', 'http://atyrau.kazgo.com/', 1, NULL, 0, 1),
(266, 4, 'Байконур', 'http://baikonur.kazgo.com/', 1, NULL, 0, 1),
(267, 4, 'Риддер ', 'http://ridder.kazgo.com/', 1, NULL, 0, 1),
(268, 4, 'Семипалатинск', 'http://semipalatinsk.kazgo.com/', 1, NULL, 0, 1),
(269, 4, 'Усть-Каменогорск', 'http://uk.kazgo.com/', 1, NULL, 0, 1),
(270, 4, 'Тараз', 'http://taraz.kazgo.com/', 1, NULL, 0, 1),
(271, 4, 'Уральск', 'http://uralsk.kazgo.com/', 1, NULL, 0, 1),
(272, 4, 'Балхаш', 'http://balhash.kazgo.com/', 1, NULL, 0, 1),
(273, 4, 'Джезказган', 'http://dzhezkazgan.kazgo.com/', 1, NULL, 0, 1),
(274, 4, 'Караганда', 'http://karaganda.kazgo.com/', 1, NULL, 0, 1),
(275, 4, 'Сатпаев', 'http://satpayev.kazgo.com/', 1, NULL, 0, 1),
(276, 4, 'Темиртау', 'http://temirtau.kazgo.com/', 1, NULL, 0, 1),
(277, 4, 'Костанайск', 'http://kostanaysk.kazgo.com/', 1, NULL, 0, 1),
(278, 4, 'Рудный', 'http://rudnyy.kazgo.com/', 1, NULL, 0, 1),
(279, 4, 'Кзыл-Орда', 'http://ko.kazgo.com/', 1, NULL, 0, 1),
(280, 4, 'Актау', 'http://aktau.kazgo.com/', 1, NULL, 0, 1),
(281, 4, 'Павлодар', 'http://pavlodar.kazgo.com/', 1, NULL, 0, 1),
(282, 4, 'Экибастуз', 'http://ekibastuz.kazgo.com/', 1, NULL, 0, 1),
(283, 4, 'Петропавловск', 'http://petropavlovsk.kazgo.com/', 1, NULL, 0, 1),
(284, 4, 'Кентау', 'http://kentau.kazgo.com/', 1, NULL, 0, 1),
(285, 4, 'Туркестан', 'http://turkestan.kazgo.com/', 1, NULL, 0, 1),
(286, 4, 'Чимкент', 'http://chimkent.kazgo.com/', 1, NULL, 0, 1),
(287, 5, 'Ташкент', 'http://tashkent.uzbekgo.com/', 1, NULL, 0, 1),
(288, 5, 'Андижан', 'http://andizhan.uzbekgo.com/', 1, NULL, 0, 1),
(289, 5, 'Асака', 'http://asaka.uzbekgo.com/', 1, NULL, 0, 1),
(290, 5, 'Карасу', 'http://karasu.uzbekgo.com/', 1, NULL, 0, 1),
(291, 5, 'Ханабад', 'http://hanabad.uzbekgo.com/', 1, NULL, 0, 1),
(292, 5, 'Щахрихан', 'http://shahrihan.uzbekgo.com/', 1, NULL, 0, 1),
(293, 5, 'Алат', 'http://alat.uzbekgo.com/', 1, NULL, 0, 1),
(294, 5, 'Бухара', 'http://buhara.uzbekgo.com/', 1, NULL, 0, 1),
(295, 5, 'Газли', 'http://gazli.uzbekgo.com/', 1, NULL, 0, 1),
(296, 5, 'Галаасия', 'http://galaasija.uzbekgo.com/', 1, NULL, 0, 1),
(297, 5, 'Гиждуван', 'http://gizhduvan.uzbekgo.com/', 1, NULL, 0, 1),
(298, 5, 'Каган', 'http://kagan.uzbekgo.com/', 1, NULL, 0, 1),
(299, 5, 'Каракул', 'http://karakul.uzbekgo.com/', 1, NULL, 0, 1),
(300, 5, 'Ромитан', 'http://romitan.uzbekgo.com/', 1, NULL, 0, 1),
(301, 5, 'Шафиркан', 'http://shafirkan.uzbekgo.com/', 1, NULL, 0, 1),
(302, 5, 'Джизак', 'http://dzhizak.uzbekgo.com/', 1, NULL, 0, 1),
(303, 5, 'Нукус', 'http://nukus.uzbekgo.com/', 1, NULL, 0, 1),
(304, 5, 'Карши', 'http://karshi.uzbekgo.com/', 1, NULL, 0, 1),
(305, 5, 'Навои', 'http://navois.uzbekgo.com/', 1, NULL, 0, 1),
(306, 5, 'Наманган', 'http://namangan.uzbekgo.com/', 1, NULL, 0, 1),
(307, 5, 'Самарканд', 'http://samarkand.uzbekgo.com/', 1, NULL, 0, 1),
(308, 5, 'Термез', 'http://termez.uzbekgo.com/', 1, NULL, 0, 1),
(309, 5, 'Гулистан', 'http://gulistan.uzbekgo.com/', 1, NULL, 0, 1),
(310, 5, 'Фергана', 'http://fergana.uzbekgo.com/', 1, NULL, 0, 1),
(311, 5, 'Ургенч', 'http://urgench.uzbekgo.com/', 1, NULL, 0, 1),
(312, 6, 'Бельцы', 'http://belcy.mldgo.com/', 1, NULL, 0, 1),
(313, 6, 'Тирасполь', 'http://tiraspol.mldgo.com/', 1, NULL, 0, 1),
(314, 6, 'Кагул', 'http://kagul.mldgo.com/', 1, NULL, 0, 1),
(315, 6, 'Оргеев', 'http://orgeev.mldgo.com/', 1, NULL, 0, 1),
(316, 6, 'Сороки', 'http://soroki.mldgo.com/', 1, NULL, 0, 1),
(317, 6, 'Хынчешты', 'http://hyncheshty.mldgo.com/', 1, NULL, 0, 1),
(318, 7, 'Тбилиси', 'http://tbilisi.gruzgo.com/', 1, NULL, 0, 1),
(319, 7, 'Батуми', 'http://batumi.gruzgo.com/', 1, NULL, 0, 1),
(320, 7, 'Кутаиси', 'http://kutaisi.gruzgo.com/', 1, NULL, 0, 1),
(321, 7, 'Гардабани', 'http://gardabani.gruzgo.com/', 1, NULL, 0, 1),
(322, 7, 'Марнеули', 'http://marneuli.gruzgo.com/', 1, NULL, 0, 1),
(323, 7, 'Рустави', 'http://rustavi.gruzgo.com/', 1, NULL, 0, 1),
(324, 7, 'Зугдиди', 'http://zugdidi.gruzgo.com/', 1, NULL, 0, 1),
(325, 7, 'Гори', 'http://gori.gruzgo.com/', 1, NULL, 0, 1),
(326, 8, 'Баку', 'http://baku.azbgo.com/', 1, NULL, 0, 1),
(327, 8, 'Гянджа', 'http://gyandzha.azbgo.com/', 1, NULL, 0, 1),
(328, 8, 'Ленкорань', 'http://lenkoran.azbgo.com/', 1, NULL, 0, 1),
(329, 8, 'Мингечаур', 'http://mingechaur.azbgo.com/', 1, NULL, 0, 1),
(330, 8, 'Сумгаит', 'http://sumgait.azbgo.com/', 1, NULL, 0, 1),
(331, 9, 'Армавир', 'http://armavir.arngo.com/', 1, NULL, 0, 1),
(332, 9, 'Ереван', 'http://erevan.arngo.com/', 1, NULL, 0, 1),
(333, 9, 'Ванадзор', 'http://vanadzor.arngo.com/', 1, NULL, 0, 1),
(334, 9, 'Гюмри', 'http://gyumri.arngo.com/', 1, NULL, 0, 1),
(335, 10, 'Абадан', 'http://abadan.turkmengo.com/', 1, NULL, 0, 1),
(336, 10, 'Дашогуз', 'http://dashoguz.turkmengo.com/', 1, NULL, 0, 1),
(337, 10, 'Туркменабад', 'http://turkmenabad.turkmengo.com/', 1, NULL, 0, 1),
(338, 10, 'Мары', 'http://mary.turkmengo.com/', 1, NULL, 0, 1),
(339, 11, 'Бишкек', 'http://bishkek.kirgizgo.com/', 1, NULL, 0, 1),
(340, 11, 'Джалал-Абад', 'http://da.kirgizgo.com/', 1, NULL, 0, 1),
(341, 11, 'Ош', 'http://osh.kirgizgo.com/', 1, NULL, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL COMMENT 'Country name',
  `url` varchar(256) NOT NULL,
  `is_enabled` int(1) NOT NULL DEFAULT '1',
  `start_city_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Countries from "go.com"';

--
-- Дамп данных таблицы `countries`
--

INSERT INTO `countries` (`id`, `name`, `url`, `is_enabled`, `start_city_id`) VALUES
(1, 'Украина', 'http://ukrgo.com/', 1, NULL),
(2, 'Россия', 'http://russgo.com/', 1, NULL),
(3, 'Беларусь', 'http://belarusgo.com/', 1, NULL),
(4, 'Казахстан', 'http://kazgo.com/', 1, NULL),
(5, 'Узбекистан', 'http://uzbekgo.com/', 1, NULL),
(6, 'Молдавия', 'http://mldgo.com/', 1, NULL),
(7, 'Грузия', 'http://gruzgo.com/', 1, NULL),
(8, 'Азербайджан', 'http://azbgo.com/', 1, NULL),
(9, 'Армения', 'http://arngo.com/', 1, NULL),
(10, 'Туркмения', 'http://turkmengo.com/', 1, NULL),
(11, 'Киргизия', 'http://kirgizgo.com/', 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `ad_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `filename` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `phones`
--

CREATE TABLE `phones` (
  `id` int(11) NOT NULL,
  `phone` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `parsed_inx` (`parsed`,`date`);

--
-- Индексы таблицы `ad_phone_relation`
--
ALTER TABLE `ad_phone_relation`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `phones`
--
ALTER TABLE `phones`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `ads`
--
ALTER TABLE `ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=737835;
--
-- AUTO_INCREMENT для таблицы `ad_phone_relation`
--
ALTER TABLE `ad_phone_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=764557;
--
-- AUTO_INCREMENT для таблицы `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=342;
--
-- AUTO_INCREMENT для таблицы `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1629737;
--
-- AUTO_INCREMENT для таблицы `phones`
--
ALTER TABLE `phones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15990;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
