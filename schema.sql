-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Июл 17 2019 г., 05:05
-- Версия сервера: 8.0.16
-- Версия PHP: 7.2.19-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `yeticave`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(3, 'Ботинки'),
(1, 'Доски и лыжи'),
(5, 'Инструменты'),
(2, 'Крепления'),
(4, 'Одежда'),
(6, 'Разное');

-- --------------------------------------------------------

--
-- Структура таблицы `lot`
--

CREATE TABLE `lot` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'наименование лота',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'описание лота',
  `img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` int(11) DEFAULT NULL COMMENT 'начальная цена',
  `step` int(11) DEFAULT NULL COMMENT 'шаг ставки',
  `fav_count` int(11) DEFAULT NULL COMMENT 'кол-во добавлений в избранное',
  `create_date` date DEFAULT NULL COMMENT 'дата создания лота',
  `end_date` date DEFAULT NULL COMMENT 'дата завершения торгов по лоту',
  `id_category` int(11) DEFAULT NULL,
  `id_author` int(11) NOT NULL,
  `id_winner` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `lot`
--

INSERT INTO `lot` (`id`, `name`, `message`, `img`, `price`, `step`, `fav_count`, `create_date`, `end_date`, `id_category`, `id_author`, `id_winner`) VALUES
(1, '2014 Rossingol District Snowboard', '', 'img/lot-1.jpg', 25000, 1000, 300, '2019-06-27', '2019-06-14', 1, 1, 2),
(2, 'DC Ply Mens 2016/2017 Snowboard', 'Сноу агонь!', 'img/lot-2.jpg', 222222, 1000, NULL, '2019-05-30', '2019-06-30', 1, 4, 24),
(3, 'Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления что надо!', 'img/lot-3.jpg', 25000, 500, NULL, '2019-05-28', '2019-08-31', 2, 4, NULL),
(4, 'Ботинки для сноуборда DC Mutiny Charocal', 'Боты как на тебя шили! 2015', 'img/lot-4.jpg', 13000, 1001, NULL, '2019-05-07', '2019-08-24', 3, 4, NULL),
(5, 'Куртка для сноуборда DC Mutiny Charocal', 'Курточка для мужика!', 'img/lot-5.jpg', 8001, 500, NULL, '2019-05-01', '2019-08-24', 4, 4, NULL),
(6, 'Маска Oakley Kanopu', 'Маска как на маскарад!', 'img/lot-6.jpg', 5400, 300, NULL, '2019-05-10', '2019-07-27', 6, 4, NULL),
(10, 'Кал', '3', 'img/tmp/phpdsUCxG', 10000, 1, NULL, '2019-06-20', '2019-08-31', 3, 3, NULL),
(11, 'Кабанчик', 'Кабанчик конечно не лыжи, но тоже продается', 'img/tmp/phpW8OG5m', 10000, 2000, NULL, '2019-07-01', '2019-10-31', 6, 24, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `rate`
--

CREATE TABLE `rate` (
  `id` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `bet` int(11) NOT NULL,
  `id_lot` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `rate`
--

INSERT INTO `rate` (`id`, `date`, `bet`, `id_lot`, `id_user`) VALUES
(1, '2019-05-16 00:00:00', 100500, 1, 1),
(2, '2019-05-31 03:12:21', 11000, 1, 3),
(3, '2019-05-31 05:14:17', 160000, 2, 3),
(4, '2019-05-31 06:21:24', 11000, 3, 3),
(5, '2019-05-31 02:00:00', 12000, 1, 2),
(9, '2019-06-24 11:23:58', 123, 10, 24),
(10, '2019-06-24 11:27:47', 130, 10, 24),
(11, '2019-06-24 13:13:05', 15000, 1, 24),
(12, '2019-06-24 13:17:23', 20000, 1, 24),
(13, '2019-06-24 13:18:37', 25000, 1, 24),
(14, '2019-07-13 03:31:21', 2000, 10, 24),
(15, '2019-07-13 04:24:29', 199999, 2, 24),
(16, '2019-07-13 04:24:45', 11111, 3, 24),
(17, '2019-07-13 04:27:02', 2222, 10, 24),
(18, '2019-07-13 05:51:16', 10000, 10, 24),
(19, '2019-07-17 00:44:20', 222222, 2, 24),
(20, '2019-07-17 00:45:02', 25000, 3, 24),
(21, '2019-07-17 02:18:54', 13000, 4, 24),
(22, '2019-07-17 02:22:13', 8001, 5, 24),
(23, '2019-06-25 00:00:00', 33333, 1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reg_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `avatar`, `contact`, `reg_date`) VALUES
(1, 'Иван', 'ivan@gmail.com', 'qwest12345', NULL, '1212fdgrgtrt не звоните черти', '2019-04-01'),
(2, 'Игнат', 'ignat.v@gmail.com', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', NULL, 'телефон 0222200022222 скайп chernozhop', '2019-06-01'),
(3, 'Леночка', 'kitty_93@li.ru', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', NULL, 'телефон 02787878 скайп chernozhop1', NULL),
(4, 'Руслан', 'warrior07@mail.ru', '2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', NULL, 'телефон 0222 скайп urexeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', NULL),
(24, 'Евгений', 'zheka.slk2010@gmail.com', '$2y$10$96rNJbH7mNfn2sETtLTXxekEsKM5ndZ/tTFb..aFT0RQ5YORxkBQG', 'img/tmp/phpKg4Wjn', 'телефон 9979997 скайп vizitor', '2019-06-18');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `lot`
--
ALTER TABLE `lot`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `img` (`img`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `id_author` (`id_author`),
  ADD KEY `id_winner` (`id_winner`),
  ADD KEY `price` (`price`),
  ADD KEY `step` (`step`),
  ADD KEY `fav_count` (`fav_count`),
  ADD KEY `name` (`name`);
ALTER TABLE `lot` ADD FULLTEXT KEY `lot_search` (`name`,`message`);

--
-- Индексы таблицы `rate`
--
ALTER TABLE `rate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lot` (`id_lot`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `bet` (`bet`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `avatar` (`avatar`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `lot`
--
ALTER TABLE `lot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `rate`
--
ALTER TABLE `rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `lot`
--
ALTER TABLE `lot`
  ADD CONSTRAINT `lot_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lot_ibfk_2` FOREIGN KEY (`id_author`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lot_ibfk_3` FOREIGN KEY (`id_winner`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `rate`
--
ALTER TABLE `rate`
  ADD CONSTRAINT `rate_ibfk_1` FOREIGN KEY (`id_lot`) REFERENCES `lot` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `rate_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
