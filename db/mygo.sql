-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 20 2017 г., 17:03
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
  `url` varchar(255) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `date` int(11) NOT NULL,
  `gender` int(1) DEFAULT NULL,
  `age` int(2) DEFAULT NULL,
  `weight` int(2) DEFAULT NULL,
  `height` int(3) DEFAULT NULL,
  `text` text,
  `parsed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ad_phone_relation`
--

CREATE TABLE IF NOT EXISTS `ad_phone_relation` (
  `id` int(11) NOT NULL,
  `ad_id` int(11) NOT NULL,
  `phone_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL COMMENT 'Country Id',
  `name` varchar(32) NOT NULL COMMENT 'Name of City',
  `url` varchar(255) NOT NULL COMMENT 'City Url'
) ENGINE=InnoDB AUTO_INCREMENT=342 DEFAULT CHARSET=utf8 COMMENT='Cities from site';

--
-- Дамп данных таблицы `cities`
--

INSERT INTO `cities` (`id`, `country_id`, `name`, `url`) VALUES
(1, 1, 'Белая Церковь', 'http://bc.ukrgo.com/'),
(2, 1, 'Борисполь', 'http://borispol.ukrgo.com/'),
(3, 1, 'Бровары', 'http://brovary.ukrgo.com/'),
(4, 1, 'Буча', 'http://bucha.ukrgo.com/'),
(5, 1, 'Киев', 'http://kiev.ukrgo.com/'),
(6, 1, 'Переяслав-Хмельницкий', 'http://ph.ukrgo.com/'),
(7, 1, 'Славутич', 'http://slavutich.ukrgo.com/'),
(8, 1, 'Фастов', 'http://fastov.ukrgo.com/'),
(9, 1, 'Евпатория', 'http://evpatoria.ukrgo.com/'),
(10, 1, 'Керчь', 'http://kerch.ukrgo.com/'),
(11, 1, 'Севастополь', 'http://sevastopol.ukrgo.com/'),
(12, 1, 'Симферополь', 'http://simferopol.ukrgo.com/'),
(13, 1, 'Феодосия', 'http://feodosia.ukrgo.com/'),
(14, 1, 'Ялта', 'http://yalta.ukrgo.com/'),
(15, 1, 'Винница', 'http://vinnica.ukrgo.com/'),
(16, 1, 'Жмеринка', 'http://zhmerinka.ukrgo.com/'),
(17, 1, 'Могилёв-Подольский', 'http://mogilev.ukrgo.com/'),
(18, 1, 'Владимир-Волынский', 'http://vv.ukrgo.com/'),
(19, 1, 'Ковель', 'http://kovel.ukrgo.com/'),
(20, 1, 'Луцк', 'http://lutsk.ukrgo.com/'),
(21, 1, 'Нововолынск', 'http://nv.ukrgo.com/'),
(22, 1, 'Днепродзержинск', 'http://dz.ukrgo.com/'),
(23, 1, 'Днепропетровск', 'http://dp.ukrgo.com/'),
(24, 1, 'Кривой Рог', 'http://kr.ukrgo.com/'),
(25, 1, 'Марганец', 'http://marganec.ukrgo.com/'),
(26, 1, 'Никополь', 'http://nikopol.ukrgo.com/'),
(27, 1, 'Новомосковск', 'http://nm.ukrgo.com/'),
(28, 1, 'Павлоград', 'http://pavlograd.ukrgo.com/'),
(29, 1, 'Горловка', 'http://gorlovka.ukrgo.com/'),
(30, 1, 'Донецк', 'http://donetsk.ukrgo.com/'),
(31, 1, 'Енакиево', 'http://enakievo.ukrgo.com/'),
(32, 1, 'Краматорск', 'http://kramatorsk.ukrgo.com/'),
(33, 1, 'Макеевка', 'http://makeevka.ukrgo.com/'),
(34, 1, 'Мариуполь', 'http://mariupol.ukrgo.com/'),
(35, 1, 'Новоазовск', 'http://novoazovsk.ukrgo.com/'),
(36, 1, 'Славянск', 'http://slavyansk.ukrgo.com/'),
(37, 1, 'Харцызск', 'http://khartsyzk.ukrgo.com/'),
(38, 1, 'Бердичев', 'http://berdichev.ukrgo.com/'),
(39, 1, 'Житомир', 'http://zhytomir.ukrgo.com/'),
(40, 1, 'Коростень', 'http://korosten.ukrgo.com/'),
(41, 1, 'Мукачево', 'http://mukachevo.ukrgo.com/'),
(42, 1, 'Ужгород', 'http://uzhgorod.ukrgo.com/'),
(43, 1, 'Хуст', 'http://hust.ukrgo.com/'),
(44, 1, 'Бердянск', 'http://berdyansk.ukrgo.com/'),
(45, 1, 'Запорожье', 'http://zp.ukrgo.com/'),
(46, 1, 'Мелитополь', 'http://melitopol.ukrgo.com/'),
(47, 1, 'Энергодар', 'http://energo.ukrgo.com/'),
(48, 1, 'Ивано-Франковск', 'http://if.ukrgo.com/'),
(49, 1, 'Калуш', 'http://kalush.ukrgo.com/'),
(50, 1, 'Коломыя', 'http://kolomiya.ukrgo.com/'),
(51, 1, 'Александрия', 'http://alexandria.ukrgo.com/'),
(52, 1, 'Кировоград', 'http://kirovograd.ukrgo.com/'),
(53, 1, 'Светловодск', 'http://svetlovodsk.ukrgo.com/'),
(54, 1, 'Алчевск', 'http://alchevsk.ukrgo.com/'),
(55, 1, 'Лисичанск', 'http://lisichansk.ukrgo.com/'),
(56, 1, 'Луганск', 'http://lugansk.ukrgo.com/'),
(57, 1, 'Первомайск', 'http://pervomaisk.ukrgo.com/'),
(58, 1, 'Рубежное', 'http://rubezhnoe.ukrgo.com/'),
(59, 1, 'Северодонецк', 'http://sd.ukrgo.com/'),
(60, 1, 'Львов', 'http://lvov.ukrgo.com/'),
(61, 1, 'Вознесенск', 'http://voznesensk.ukrgo.com/'),
(62, 1, 'Николаев', 'http://nikolaev.ukrgo.com/'),
(63, 1, 'Очаков', 'http://ochakov.ukrgo.com/'),
(64, 1, 'Южноукраинск', 'http://uk.ukrgo.com/'),
(65, 1, 'Белгород-Днестровский', 'http://bd.ukrgo.com/'),
(66, 1, 'Измаил', 'http://izmail.ukrgo.com/'),
(67, 1, 'Ильичёвск', 'http://il.ukrgo.com/'),
(68, 1, 'Одесса', 'http://odessa.ukrgo.com/'),
(69, 1, 'Комсомольск', 'http://komsomolsk.ukrgo.com/'),
(70, 1, 'Кременчуг', 'http://kremenchug.ukrgo.com/'),
(71, 1, 'Лубны', 'http://lubny.ukrgo.com/'),
(72, 1, 'Миргород', 'http://mirgorod.ukrgo.com/'),
(73, 1, 'Полтава', 'http://poltava.ukrgo.com/'),
(74, 1, 'Дубно', 'http://dubno.ukrgo.com/'),
(75, 1, 'Кузнецовск', 'http://kz.ukrgo.com/'),
(76, 1, 'Острог', 'http://ostrog.ukrgo.com/'),
(77, 1, 'Ровно', 'http://rovno.ukrgo.com/'),
(78, 1, 'Ахтырка', 'http://ak.ukrgo.com/'),
(79, 1, 'Конотоп', 'http://konotop.ukrgo.com/'),
(80, 1, 'Лебедин', 'http://lebedin.ukrgo.com/'),
(81, 1, 'Сумы', 'http://summy.ukrgo.com/'),
(82, 1, 'Шостка', 'http://shostka.ukrgo.com/'),
(83, 1, 'Тернополь', 'http://ternopol.ukrgo.com/'),
(84, 1, 'Изюм', 'http://izyum.ukrgo.com/'),
(85, 1, 'Купянск', 'http://kupyansk.ukrgo.com/'),
(86, 1, 'Харьков', 'http://kharkov.ukrgo.com/'),
(87, 1, 'Чугуев', 'http://chuguev.ukrgo.com/'),
(88, 1, 'Каховка', 'http://kahovka.ukrgo.com/'),
(89, 1, 'Новая Каховка', 'http://nkahovka.ukrgo.com/'),
(90, 1, 'Херсон', 'http://kherson.ukrgo.com/'),
(91, 1, 'Каменец-Подольский', 'http://kp.ukrgo.com/'),
(92, 1, 'Хмельницкий', 'http://khm.ukrgo.com/'),
(93, 1, 'Шепетовка', 'http://shepetovka.ukrgo.com/'),
(94, 1, 'Золотоноша', 'http://zolotonosha.ukrgo.com/'),
(95, 1, 'Корсунь-Шевченковский', 'http://k-sh.ukrgo.com/'),
(96, 1, 'Смела', 'http://smela.ukrgo.com/'),
(97, 1, 'Умань', 'http://uman.ukrgo.com/'),
(98, 1, 'Черкассы', 'http://cherkassy.ukrgo.com/'),
(99, 1, 'Нежин', 'http://nezhin.ukrgo.com/'),
(100, 1, 'Прилуки', 'http://priluki.ukrgo.com/'),
(101, 1, 'Чернигов', 'http://chernigov.ukrgo.com/'),
(102, 1, 'Новоднестровск', 'http://nvd.ukrgo.com/'),
(103, 1, 'Черновцы', 'http://chernovcy.ukrgo.com/'),
(104, 2, 'Москва', 'http://moscow.russgo.com/'),
(105, 2, 'Санкт-Петербург', 'http://sp.russgo.com/'),
(106, 2, 'Благовещенск', 'http://blk.russgo.com/'),
(107, 2, 'Владивосток', 'http://vladivostok.russgo.com/'),
(108, 2, 'Комсомольск-на-Амуре', 'http://ka.russgo.com/'),
(109, 2, 'Магадан', 'http://magadan.russgo.com/'),
(110, 2, 'Находка', 'http://nahodka.russgo.com/'),
(111, 2, 'Петропавловск-Камчатский', 'http://pk.russgo.com/'),
(112, 2, 'Хабаровск', 'http://habarovsk.russgo.com/'),
(113, 2, 'Южно-Сахалинск', 'http://ys.russgo.com/'),
(114, 2, 'Якутск', 'http://yakutsk.russgo.com/'),
(115, 2, 'Альметьевск', 'http://almetevsk.russgo.com/'),
(116, 2, 'Арзамас', 'http://arzamas.russgo.com/'),
(117, 2, 'Балаково', 'http://balakovo.russgo.com/'),
(118, 2, 'Березники', 'http://berezniki.russgo.com/'),
(119, 2, 'Дзержинск', 'http://dzerzhinsk.russgo.com/'),
(120, 2, 'Димитровград', 'http://dimitrovgrad.russgo.com/'),
(121, 2, 'Ижевск', 'http://izhevsk.russgo.com/'),
(122, 2, 'Йошкар-Ола', 'http://yola.russgo.com/'),
(123, 2, 'Казань', 'http://kazan.russgo.com/'),
(124, 2, 'Киров', 'http://kirov.russgo.com/'),
(125, 2, 'Набережные Челны', 'http://nchelny.russgo.com/'),
(126, 2, 'Нефтекамск', 'http://neftekamsk.russgo.com/'),
(127, 2, 'Нижнекамск', 'http://nkamsk.russgo.com/'),
(128, 2, 'Нижний Новгород', 'http://nn.russgo.com/'),
(129, 2, 'Новотроицк', 'http://novotroick.russgo.com/'),
(130, 2, 'Оренбург', 'http://orenburg.russgo.com/'),
(131, 2, 'Орск', 'http://orsk.russgo.com/'),
(132, 2, 'Пенза', 'http://penza.russgo.com/'),
(133, 2, 'Пермь', 'http://perm.russgo.com/'),
(134, 2, 'Салават', 'http://salavat.russgo.com/'),
(135, 2, 'Самара', 'http://samara.russgo.com/'),
(136, 2, 'Саранск', 'http://saransk.russgo.com/'),
(137, 2, 'Саратов', 'http://saratov.russgo.com/'),
(138, 2, 'Стерлитамак', 'http://stamak.russgo.com/'),
(139, 2, 'Сызрань', 'http://syzran.russgo.com/'),
(140, 2, 'Тольятти', 'http://tolyatti.russgo.com/'),
(141, 2, 'Ульяновск', 'http://ulyanovsk.russgo.com/'),
(142, 2, 'Уфа', 'http://ufa.russgo.com/'),
(143, 2, 'Чебоксары', 'http://cheboksary.russgo.com/'),
(144, 2, 'Энгельс', 'http://engels.russgo.com/'),
(145, 2, 'Архангельск', 'http://arhangelsk.russgo.com/'),
(146, 2, 'Великие Луки', 'http://velikie.russgo.com/'),
(147, 2, 'Великий Новгород', 'http://vnovgorod.russgo.com/'),
(148, 2, 'Вологда', 'http://vologda.russgo.com/'),
(149, 2, 'Калининград', 'http://kaliningrad.russgo.com/'),
(150, 2, 'Мурманск', 'http://murmansk.russgo.com/'),
(151, 2, 'Петрозаводск', 'http://petrozavodsk.russgo.com/'),
(152, 2, 'Псков', 'http://pskov.russgo.com/'),
(153, 2, 'Северодвинск', 'http://severodvinsk.russgo.com/'),
(154, 2, 'Сыктывкар', 'http://syktyvkar.russgo.com/'),
(155, 2, 'Ухта', 'http://uhta.russgo.com/'),
(156, 2, 'Череповец', 'http://cherepovec.russgo.com/'),
(157, 2, 'Абакан', 'http://abakan.russgo.com/'),
(158, 2, 'Ангарск', 'http://angarsk.russgo.com/'),
(159, 2, 'Барнаул', 'http://barnaul.russgo.com/'),
(160, 2, 'Бийск', 'http://biisk.russgo.com/'),
(161, 2, 'Братск', 'http://bratsk.russgo.com/'),
(162, 2, 'Горно-Алтайск', 'http://galtaisk.russgo.com/'),
(163, 2, 'Иркутск', 'http://irkutsk.russgo.com/'),
(164, 2, 'Кемерово', 'http://kemerovo.russgo.com/'),
(165, 2, 'Красноярск', 'http://krasnoyarsk.russgo.com/'),
(166, 2, 'Кызыл', 'http://kyzyl.russgo.com/'),
(167, 2, 'Новокузнецк', 'http://novokusneck.russgo.com/'),
(168, 2, 'Новосибирск', 'http://ns.russgo.com/'),
(169, 2, 'Норильск', 'http://norilsk.russgo.com/'),
(170, 2, 'Омск', 'http://omsk.russgo.com/'),
(171, 2, 'Прокопьевск', 'http://prokopevsk.russgo.com/'),
(172, 2, 'Рубцовск', 'http://rubcovsk.russgo.com/'),
(173, 2, 'Северск', 'http://seversk.russgo.com/'),
(174, 2, 'Томск', 'http://tomsk.russgo.com/'),
(175, 2, 'Улан-Удэ', 'http://ulanude.russgo.com/'),
(176, 2, 'Чита', 'http://chita.russgo.com/'),
(177, 2, 'Екатеринбург', 'http://ek.russgo.com/'),
(178, 2, 'Златоуст', 'http://zlatoust.russgo.com/'),
(179, 2, 'Каменск-Уральский', 'http://ku.russgo.com/'),
(180, 2, 'Курган', 'http://kurgan.russgo.com/'),
(181, 2, 'Магнитогорск', 'http://magnitogorsk.russgo.com/'),
(182, 2, 'Миасс', 'http://miass.russgo.com/'),
(183, 2, 'Нефтеюганск', 'http://nyugansk.russgo.com/'),
(184, 2, 'Нижневартовск', 'http://nvartovsk.russgo.com/'),
(185, 2, 'Нижний Тагил', 'http://ntagil.russgo.com/'),
(186, 2, 'Новый Уренгой', 'http://nurengoi.russgo.com/'),
(187, 2, 'Ноябрьск', 'http://noyabrsk.russgo.com/'),
(188, 2, 'Первоуральск', 'http://pervouralsk.russgo.com/'),
(189, 2, 'Полевской', 'http://polevskoy.russgo.com/'),
(190, 2, 'Сургут', 'http://surgut.russgo.com/'),
(191, 2, 'Тобольск', 'http://tobolsk.russgo.com/'),
(192, 2, 'Тюмень', 'http://tyumen.russgo.com/'),
(193, 2, 'Челябинск', 'http://chelyabinsk.russgo.com/'),
(194, 2, 'Балашиха', 'http://balashiha.russgo.com/'),
(195, 2, 'Белгород', 'http://belgorod.russgo.com/'),
(196, 2, 'Брянск', 'http://bryansk.russgo.com/'),
(197, 2, 'Владимир', 'http://vladimir.russgo.com/'),
(198, 2, 'Воронеж', 'http://voronezh.russgo.com/'),
(199, 2, 'Иваново', 'http://ivanovo.russgo.com/'),
(200, 2, 'Калуга', 'http://kaluga.russgo.com/'),
(201, 2, 'Королев', 'http://korolev.russgo.com/'),
(202, 2, 'Кострома', 'http://kostroma.russgo.com/'),
(203, 2, 'Курск', 'http://kursk.russgo.com/'),
(204, 2, 'Липецк', 'http://lipeck.russgo.com/'),
(205, 2, 'Люберцы', 'http://lyubercy.russgo.com/'),
(206, 2, 'Мытищи', 'http://mytishi.russgo.com/'),
(207, 2, 'Новомосковск', 'http://nmoskovsk.russgo.com/'),
(208, 2, 'Орел', 'http://orel.russgo.com/'),
(209, 2, 'Подольск', 'http://podolsk.russgo.com/'),
(210, 2, 'Рыбинск', 'http://rybinsk.russgo.com/'),
(211, 2, 'Рязань', 'http://ryazan.russgo.com/'),
(212, 2, 'Смоленск', 'http://smolensk.russgo.com/'),
(213, 2, 'Старый Оскол', 'http://soskol.russgo.com/'),
(214, 2, 'Тамбов', 'http://tambov.russgo.com/'),
(215, 2, 'Тверь', 'http://tver.russgo.com/'),
(216, 2, 'Тула', 'http://tula.russgo.com/'),
(217, 2, 'Химки ', 'http://himki.russgo.com/'),
(218, 2, 'Ярославль', 'http://yaroslavl.russgo.com/'),
(219, 2, 'Армавир', 'http://armavir.russgo.com/'),
(220, 2, 'Астрахань', 'http://astrahan.russgo.com/'),
(221, 2, 'Владикавказ', 'http://vladikavkaz.russgo.com/'),
(222, 2, 'Волгоград', 'http://volgograd.russgo.com/'),
(223, 2, 'Волгодонск', 'http://volgodonsk.russgo.com/'),
(224, 2, 'Волжский', 'http://volzhskiy.russgo.com/'),
(225, 2, 'Грозный', 'http://groznyi.russgo.com/'),
(226, 2, 'Краснодар', 'http://krasnodar.russgo.com/'),
(227, 2, 'Майкоп', 'http://maikop.russgo.com/'),
(228, 2, 'Махачкала', 'http://mahachkala.russgo.com/'),
(229, 2, 'Назрань', 'http://nazran.russgo.com/'),
(230, 2, 'Нальчик', 'http://nalchik.russgo.com/'),
(231, 2, 'Новороссийск', 'http://nrossiysk.russgo.com/'),
(232, 2, 'Новочеркасск', 'http://novocherkassk.russgo.com/'),
(233, 2, 'Пятигорск', 'http://pyatigorsk.russgo.com/'),
(234, 2, 'Ростов-на-Дону', 'http://rnd.russgo.com/'),
(235, 2, 'Сочи', 'http://sochi.russgo.com/'),
(236, 2, 'Ставрополь', 'http://stavropol.russgo.com/'),
(237, 2, 'Таганрог', 'http://taganrog.russgo.com/'),
(238, 2, 'Хасавюрт', 'http://hasavyurt.russgo.com/'),
(239, 2, 'Черкесск', 'http://cherkessk.russgo.com/'),
(240, 2, 'Шахты', 'http://shahty.russgo.com/'),
(241, 2, 'Элиста', 'http://elista.russgo.com/'),
(242, 3, 'Борисов', 'http://borisov.belarusgo.com/'),
(243, 3, 'Минск', 'http://minsk.belarusgo.com/'),
(244, 3, 'Молодечно', 'http://mo.belarusgo.com/'),
(245, 3, 'Солигорск', 'http://soligorsk.belarusgo.com/'),
(246, 3, 'Барановичи', 'http://baranovichy.belarusgo.com/'),
(247, 3, 'Брест', 'http://brest.belarusgo.com/'),
(248, 3, 'Пинск', 'http://pinsk.belarusgo.com/'),
(249, 3, 'Витебск', 'http://vitebsk.belarusgo.com/'),
(250, 3, 'Новополоцк', 'http://nk.belarusgo.com/'),
(251, 3, 'Орша', 'http://orsha.belarusgo.com/'),
(252, 3, 'Полоцк', 'http://pk.belarusgo.com/'),
(253, 3, 'Гомель', 'http://gomel.belarusgo.com/'),
(254, 3, 'Жлобин', 'http://zhlobin.belarusgo.com/'),
(255, 3, 'Мозырь', 'http://mozyr.belarusgo.com/'),
(256, 3, 'Светлогорск', 'http://sk.belarusgo.com/'),
(257, 3, 'Гродно', 'http://grodno.belarusgo.com/'),
(258, 3, 'Лида', 'http://lida.belarusgo.com/'),
(259, 3, 'Бобруйск', 'http://bk.belarusgo.com/'),
(260, 3, 'Могилёв', 'http://mogilev.belarusgo.com/'),
(261, 4, 'Астана', 'http://astana.kazgo.com/'),
(262, 4, 'Алматы', 'http://almaata.kazgo.com/'),
(263, 4, 'Кокчетав', 'http://konchetav.kazgo.com/'),
(264, 4, 'Актюбинск', 'http://aktubinsk.kazgo.com/'),
(265, 4, 'Атырау', 'http://atyrau.kazgo.com/'),
(266, 4, 'Байконур', 'http://baikonur.kazgo.com/'),
(267, 4, 'Риддер ', 'http://ridder.kazgo.com/'),
(268, 4, 'Семипалатинск', 'http://semipalatinsk.kazgo.com/'),
(269, 4, 'Усть-Каменогорск', 'http://uk.kazgo.com/'),
(270, 4, 'Тараз', 'http://taraz.kazgo.com/'),
(271, 4, 'Уральск', 'http://uralsk.kazgo.com/'),
(272, 4, 'Балхаш', 'http://balhash.kazgo.com/'),
(273, 4, 'Джезказган', 'http://dzhezkazgan.kazgo.com/'),
(274, 4, 'Караганда', 'http://karaganda.kazgo.com/'),
(275, 4, 'Сатпаев', 'http://satpayev.kazgo.com/'),
(276, 4, 'Темиртау', 'http://temirtau.kazgo.com/'),
(277, 4, 'Костанайск', 'http://kostanaysk.kazgo.com/'),
(278, 4, 'Рудный', 'http://rudnyy.kazgo.com/'),
(279, 4, 'Кзыл-Орда', 'http://ko.kazgo.com/'),
(280, 4, 'Актау', 'http://aktau.kazgo.com/'),
(281, 4, 'Павлодар', 'http://pavlodar.kazgo.com/'),
(282, 4, 'Экибастуз', 'http://ekibastuz.kazgo.com/'),
(283, 4, 'Петропавловск', 'http://petropavlovsk.kazgo.com/'),
(284, 4, 'Кентау', 'http://kentau.kazgo.com/'),
(285, 4, 'Туркестан', 'http://turkestan.kazgo.com/'),
(286, 4, 'Чимкент', 'http://chimkent.kazgo.com/'),
(287, 5, 'Ташкент', 'http://tashkent.uzbekgo.com/'),
(288, 5, 'Андижан', 'http://andizhan.uzbekgo.com/'),
(289, 5, 'Асака', 'http://asaka.uzbekgo.com/'),
(290, 5, 'Карасу', 'http://karasu.uzbekgo.com/'),
(291, 5, 'Ханабад', 'http://hanabad.uzbekgo.com/'),
(292, 5, 'Щахрихан', 'http://shahrihan.uzbekgo.com/'),
(293, 5, 'Алат', 'http://alat.uzbekgo.com/'),
(294, 5, 'Бухара', 'http://buhara.uzbekgo.com/'),
(295, 5, 'Газли', 'http://gazli.uzbekgo.com/'),
(296, 5, 'Галаасия', 'http://galaasija.uzbekgo.com/'),
(297, 5, 'Гиждуван', 'http://gizhduvan.uzbekgo.com/'),
(298, 5, 'Каган', 'http://kagan.uzbekgo.com/'),
(299, 5, 'Каракул', 'http://karakul.uzbekgo.com/'),
(300, 5, 'Ромитан', 'http://romitan.uzbekgo.com/'),
(301, 5, 'Шафиркан', 'http://shafirkan.uzbekgo.com/'),
(302, 5, 'Джизак', 'http://dzhizak.uzbekgo.com/'),
(303, 5, 'Нукус', 'http://nukus.uzbekgo.com/'),
(304, 5, 'Карши', 'http://karshi.uzbekgo.com/'),
(305, 5, 'Навои', 'http://navois.uzbekgo.com/'),
(306, 5, 'Наманган', 'http://namangan.uzbekgo.com/'),
(307, 5, 'Самарканд', 'http://samarkand.uzbekgo.com/'),
(308, 5, 'Термез', 'http://termez.uzbekgo.com/'),
(309, 5, 'Гулистан', 'http://gulistan.uzbekgo.com/'),
(310, 5, 'Фергана', 'http://fergana.uzbekgo.com/'),
(311, 5, 'Ургенч', 'http://urgench.uzbekgo.com/'),
(312, 6, 'Бельцы', 'http://belcy.mldgo.com/'),
(313, 6, 'Тирасполь', 'http://tiraspol.mldgo.com/'),
(314, 6, 'Кагул', 'http://kagul.mldgo.com/'),
(315, 6, 'Оргеев', 'http://orgeev.mldgo.com/'),
(316, 6, 'Сороки', 'http://soroki.mldgo.com/'),
(317, 6, 'Хынчешты', 'http://hyncheshty.mldgo.com/'),
(318, 7, 'Тбилиси', 'http://tbilisi.gruzgo.com/'),
(319, 7, 'Батуми', 'http://batumi.gruzgo.com/'),
(320, 7, 'Кутаиси', 'http://kutaisi.gruzgo.com/'),
(321, 7, 'Гардабани', 'http://gardabani.gruzgo.com/'),
(322, 7, 'Марнеули', 'http://marneuli.gruzgo.com/'),
(323, 7, 'Рустави', 'http://rustavi.gruzgo.com/'),
(324, 7, 'Зугдиди', 'http://zugdidi.gruzgo.com/'),
(325, 7, 'Гори', 'http://gori.gruzgo.com/'),
(326, 8, 'Баку', 'http://baku.azbgo.com/'),
(327, 8, 'Гянджа', 'http://gyandzha.azbgo.com/'),
(328, 8, 'Ленкорань', 'http://lenkoran.azbgo.com/'),
(329, 8, 'Мингечаур', 'http://mingechaur.azbgo.com/'),
(330, 8, 'Сумгаит', 'http://sumgait.azbgo.com/'),
(331, 9, 'Армавир', 'http://armavir.arngo.com/'),
(332, 9, 'Ереван', 'http://erevan.arngo.com/'),
(333, 9, 'Ванадзор', 'http://vanadzor.arngo.com/'),
(334, 9, 'Гюмри', 'http://gyumri.arngo.com/'),
(335, 10, 'Абадан', 'http://abadan.turkmengo.com/'),
(336, 10, 'Дашогуз', 'http://dashoguz.turkmengo.com/'),
(337, 10, 'Туркменабад', 'http://turkmenabad.turkmengo.com/'),
(338, 10, 'Мары', 'http://mary.turkmengo.com/'),
(339, 11, 'Бишкек', 'http://bishkek.kirgizgo.com/'),
(340, 11, 'Джалал-Абад', 'http://da.kirgizgo.com/'),
(341, 11, 'Ош', 'http://osh.kirgizgo.com/');

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

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL,
  `ad_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `filename` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `phones`
--

CREATE TABLE IF NOT EXISTS `phones` (
  `id` int(11) NOT NULL,
  `phone` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `ad_phone_relation`
--
ALTER TABLE `ad_phone_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=342;
--
-- AUTO_INCREMENT для таблицы `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `phones`
--
ALTER TABLE `phones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
