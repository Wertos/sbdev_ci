-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июн 28 2025 г., 22:10
-- Версия сервера: 8.0.41-0ubuntu0.22.04.1
-- Версия PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Структура таблицы `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `t_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `sort` int NOT NULL DEFAULT '0',
  `name` varchar(30) NOT NULL DEFAULT '',
  `parent` int NOT NULL DEFAULT '0',
  `url` varchar(70) NOT NULL,
  `icon` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int UNSIGNED NOT NULL,
  `user` int UNSIGNED NOT NULL DEFAULT '0',
  `fid` int UNSIGNED NOT NULL DEFAULT '0',
  `added` int UNSIGNED NOT NULL,
  `text` mediumtext NOT NULL,
  `editedby` int UNSIGNED NOT NULL DEFAULT '0',
  `location` enum('torrents') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `countries`
--

CREATE TABLE `countries` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `flagpic` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `countries`
--

INSERT INTO `countries` (`id`, `name`, `flagpic`) VALUES
(1, 'Швеция', 'sweden.gif'),
(2, 'США', 'usa.gif'),
(3, 'Россия', 'russia.gif'),
(4, 'Финляндия', 'finland.gif'),
(5, 'Канада', 'canada.gif'),
(6, 'Франция', 'france.gif'),
(7, 'Германия', 'germany.gif'),
(8, 'Китай', 'china.gif'),
(9, 'Италия', 'italy.gif'),
(10, 'Дания', 'denmark.gif'),
(11, 'Норвегия', 'norway.gif'),
(12, 'Великобритания', 'uk.gif'),
(13, 'Ирландия', 'ireland.gif'),
(14, 'Польша', 'poland.gif'),
(15, 'Нидерланды', 'netherlands.gif'),
(16, 'Бельгия', 'belgium.gif'),
(17, 'Япония', 'japan.gif'),
(18, 'Бразилия', 'brazil.gif'),
(19, 'Аргентина', 'argentina.gif'),
(20, 'Австралия', 'australia.gif'),
(21, 'Новая Зеландия', 'newzealand.gif'),
(22, 'Испания', 'spain.gif'),
(23, 'Португалия', 'portugal.gif'),
(24, 'Мексика', 'mexico.gif'),
(25, 'Сингапур', 'singapore.gif'),
(26, 'Индия', 'india.gif'),
(27, 'Албания', 'albania.gif'),
(28, 'ЮАР', 'southafrica.gif'),
(29, 'Южная Корея', 'southkorea.gif'),
(30, 'Ямайка', 'jamaica.gif'),
(31, 'Люксембург', 'luxembourg.gif'),
(32, 'Гонконг', 'hongkong.gif'),
(33, 'Белиз', 'belize.gif'),
(34, 'Алжир', 'algeria.gif'),
(35, 'Ангола', 'angola.gif'),
(36, 'Австрия', 'austria.gif'),
(37, 'Югославия', 'yugoslavia.gif'),
(38, 'Самоа', 'westernsamoa.gif'),
(39, 'Малайзия', 'malaysia.gif'),
(40, 'Доминиканская Республика', 'dominicanrep.gif'),
(41, 'Греция', 'greece.gif'),
(42, 'Гватемала', 'guatemala.gif'),
(43, 'Израиль', 'israel.gif'),
(44, 'Пакистан', 'pakistan.gif'),
(45, 'Чехия', 'czechrep.gif'),
(46, 'Сербия', 'serbia.gif'),
(47, 'Сейшельские Острова', 'seychelles.gif'),
(48, 'Тайвань', 'taiwan.gif'),
(49, 'Пуэрто-Рико', 'puertorico.gif'),
(50, 'Чили', 'chile.gif'),
(51, 'Куба', 'cuba.gif'),
(52, 'Конго', 'congo.gif'),
(53, 'Афганистан', 'afghanistan.gif'),
(54, 'Турция', 'turkey.gif'),
(55, 'Узбекистан', 'uzbekistan.gif'),
(56, 'Швейцария', 'switzerland.gif'),
(57, 'Кирибати', 'kiribati.gif'),
(58, 'Филиппины', 'philippines.gif'),
(59, 'Буркина Фасо', 'burkinafaso.gif'),
(60, 'Нигерия', 'nigeria.gif'),
(61, 'Исландия', 'iceland.gif'),
(62, 'Науру', 'nauru.gif'),
(63, 'Словакия', 'slovenia.gif'),
(64, 'Туркменистан', 'turkmenistan.gif'),
(65, 'Босния и Герцеговина', 'bosniaherzegovina.gif'),
(66, 'Андорра', 'andorra.gif'),
(67, 'Литва', 'lithuania.gif'),
(68, 'Македония', 'macedonia.gif'),
(69, 'Нидерландские Антиллы', 'nethantilles.gif'),
(70, 'Украина', 'ukraine.gif'),
(71, 'Венесуэла', 'venezuela.gif'),
(72, 'Венгрия', 'hungary.gif'),
(73, 'Румыния', 'romania.gif'),
(74, 'Вануату', 'vanuatu.gif'),
(75, 'Вьетнам', 'vietnam.gif'),
(76, 'Тринидад и Тобаго', 'trinidadandtobago.gif'),
(77, 'Гондурас', 'honduras.gif'),
(78, 'Кыргызстан', 'kyrgyzstan.gif'),
(79, 'Эквадор', 'ecuador.gif'),
(80, 'Багамы', 'bahamas.gif'),
(81, 'Перу', 'peru.gif'),
(82, 'Камбоджа', 'cambodia.gif'),
(83, 'Барбадос', 'barbados.gif'),
(84, 'Бангладеш', 'bangladesh.gif'),
(85, 'Лаос', 'laos.gif'),
(86, 'Уругвай', 'uruguay.gif'),
(87, 'Антигуа и Барбуда', 'antiguabarbuda.gif'),
(88, 'Парагвай', 'paraguay.gif'),
(89, 'Таиланд', 'thailand.gif'),
(90, 'СССР', 'ussr.gif'),
(91, 'Сенегал', 'senegal.gif'),
(92, 'Того', 'togo.gif'),
(93, 'Северная Корея', 'northkorea.gif'),
(94, 'Хорватия', 'croatia.gif'),
(95, 'Эстония', 'estonia.gif'),
(96, 'Колумбия', 'colombia.gif'),
(97, 'Ливан', 'lebanon.gif'),
(98, 'Латвия', 'latvia.gif'),
(99, 'Коста-Рика', 'costarica.gif'),
(100, 'Египет', 'egypt.gif'),
(101, 'Болгария', 'bulgaria.gif'),
(102, 'Исла де Муерто', 'jollyroger.gif'),
(103, 'Молдова', 'moldova.gif'),
(104, 'Беларусь', 'belarus.gif'),
(105, 'Казахстан', 'kazakhstan.gif'),
(106, 'Таджикистан', 'tajikistan.gif'),
(107, 'Грузия', 'georgia.gif'),
(108, 'Армения', 'armenia.gif'),
(109, 'Азербайджан', 'azerbaijan.gif');

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `id` int UNSIGNED NOT NULL,
  `torrent` int UNSIGNED NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `size` bigint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` mediumint UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Администратор'),
(2, 'moderator', 'Модератор'),
(3, 'uploader', 'Заливающий'),
(5, 'user', 'Пользователь');

-- --------------------------------------------------------

--
-- Структура таблицы `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int UNSIGNED NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `parser`
--

CREATE TABLE `parser` (
  `id` int NOT NULL,
  `t_id` int NOT NULL,
  `site` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `reports`
--

CREATE TABLE `reports` (
  `id` int UNSIGNED NOT NULL,
  `fid` int UNSIGNED NOT NULL,
  `comment` mediumtext NOT NULL,
  `added` int UNSIGNED NOT NULL,
  `modded_by` int UNSIGNED NOT NULL DEFAULT '0',
  `location` enum('torrents','comments') NOT NULL,
  `sender` int UNSIGNED NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `torrents`
--

CREATE TABLE `torrents` (
  `id` int UNSIGNED NOT NULL,
  `owner` int UNSIGNED NOT NULL DEFAULT '0',
  `info_hash` binary(20) NOT NULL,
  `numfiles` int UNSIGNED NOT NULL DEFAULT '0',
  `size` bigint UNSIGNED NOT NULL DEFAULT '0',
  `type` enum('single','multi') NOT NULL DEFAULT 'single',
  `name` varchar(255) NOT NULL DEFAULT '',
  `descr` mediumtext NOT NULL,
  `category` int UNSIGNED NOT NULL DEFAULT '0',
  `added` int UNSIGNED NOT NULL,
  `completed` int UNSIGNED NOT NULL DEFAULT '0',
  `leechers` int UNSIGNED NOT NULL DEFAULT '0',
  `seeders` int UNSIGNED NOT NULL DEFAULT '0',
  `poster` varchar(255) DEFAULT NULL,
  `magnet` mediumtext,
  `last_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `url` varchar(250) DEFAULT NULL,
  `file` enum('yes','no') NOT NULL DEFAULT 'yes',
  `comments` int UNSIGNED NOT NULL DEFAULT '0',
  `can_comment` enum('yes','no') NOT NULL DEFAULT 'yes',
  `modded` enum('yes','no') NOT NULL DEFAULT 'no',
  `views` int NOT NULL DEFAULT '0',
  `downloaded` int DEFAULT '0',
  `updated` tinyint(1) NOT NULL DEFAULT '0',
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `torrents_scrape`
--

CREATE TABLE `torrents_scrape` (
  `id` int NOT NULL,
  `tid` int UNSIGNED NOT NULL DEFAULT '0',
  `info_hash` varbinary(40) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `seeders` int UNSIGNED NOT NULL DEFAULT '0',
  `leechers` int UNSIGNED NOT NULL DEFAULT '0',
  `completed` int UNSIGNED NOT NULL DEFAULT '0',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `state` enum('ok','error') NOT NULL DEFAULT 'ok',
  `error` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tracker`
--

CREATE TABLE `tracker` (
  `info_hash` char(40) NOT NULL,
  `ip` char(8) NOT NULL,
  `port` smallint UNSIGNED NOT NULL DEFAULT '0',
  `update_time` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int UNSIGNED DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int UNSIGNED NOT NULL,
  `last_login` int UNSIGNED DEFAULT NULL,
  `active` tinyint UNSIGNED DEFAULT NULL,
  `country` int UNSIGNED NOT NULL DEFAULT '0',
  `userfile` varchar(40) DEFAULT NULL,
  `can_comment` enum('yes','no') NOT NULL DEFAULT 'yes',
  `can_upload` enum('yes','no') NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `country`, `userfile`, `can_comment`, `can_upload`) VALUES
(1, '127.0.0.1', 'Wertos', '$2y$08$J2gXeo61qH0jxIpPfLhnq.DlPdLR04TevheEkz9EhG3VDJ/04g2p.', NULL, 'tutaew@ya.ru', NULL, NULL, NULL, 'wTwEZtVKZuDc5RqsaFGTOe', 1504915200, 1751137453, 1, 3, '77d180107b47a0749bf80a57c364a2e7.jpg', 'yes', 'yes'),
(2, '127.0.0.1', 'Bot', '', NULL, '', NULL, NULL, NULL, NULL, 0, NULL, 0, 3, 'bot.png', 'yes', 'yes'),
(3, '127.0.0.1', 'EroGirl', '', NULL, '', NULL, NULL, NULL, NULL, 0, NULL, 0, 3, 'erobot.jpg', 'yes', 'yes');

-- --------------------------------------------------------

--
-- Структура таблицы `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `group_id` mediumint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `torrent` (`fid`);

--
-- Индексы таблицы `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `torrent` (`torrent`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `parser`
--
ALTER TABLE `parser`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `id_2` (`id`);

--
-- Индексы таблицы `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`,`ip_address`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Индексы таблицы `torrents`
--
ALTER TABLE `torrents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `info_hash` (`info_hash`),
  ADD KEY `category_visible` (`category`);
ALTER TABLE `torrents` ADD FULLTEXT KEY `name` (`name`);

--
-- Индексы таблицы `torrents_scrape`
--
ALTER TABLE `torrents_scrape`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `info_hash` (`info_hash`,`url`,`tid`);

--
-- Индексы таблицы `tracker`
--
ALTER TABLE `tracker`
  ADD PRIMARY KEY (`info_hash`,`ip`,`port`) USING BTREE;

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Индексы таблицы `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `parser`
--
ALTER TABLE `parser`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `torrents`
--
ALTER TABLE `torrents`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `torrents_scrape`
--
ALTER TABLE `torrents_scrape`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
