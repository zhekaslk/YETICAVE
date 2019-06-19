-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Июн 19 2019 г., 03:04
-- Версия сервера: 8.0.16
-- Версия PHP: 7.2.17-0ubuntu0.18.04.1

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
  `message` text COLLATE utf8mb4_general_ci COMMENT 'описание лота',
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
(1, '2014 Rossingol District Snowboard', '', 'img/lot-1.jpg', 10999, 1000, NULL, '2019-04-07', '2019-07-27', 1, 1, NULL),
(2, 'DC Ply Mens 2016/2017 Snowboard', 'Сноу агонь!', 'img/lot-2.jpg', 159999, 1000, NULL, '2019-05-30', '2019-06-30', 1, 4, NULL),
(3, 'Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления что надо!', 'img/lot-3.jpg', 8000, 500, NULL, '2019-05-28', '2019-06-30', 2, 4, NULL),
(4, 'Ботинки для сноуборда DC Mutiny Charocal', 'Боты как на тебя шили!', 'img/lot-4.jpg', 10999, 1001, NULL, '2019-05-07', '2019-06-28', 3, 4, NULL),
(5, 'Куртка для сноуборда DC Mutiny Charocal', 'Курточка для мужика!', 'img/lot-5.jpg', 7500, 500, NULL, '2019-05-01', '2019-06-29', 4, 4, NULL),
(6, 'Маска Oakley Kanopu', 'Маска как на маскарад!', 'img/lot-6.jpg', 5400, 300, NULL, '2019-05-10', '2019-07-27', 6, 4, NULL),
(7, 'Чебурек', 'Сноуборды для лохов, чебуреки для пацанов!', 'img/cheburek.jpg', 1, 2, NULL, '2019-06-19', '2019-07-19', 6, 24, NULL);

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
(5, '2019-05-31 02:00:00', 12000, 1, 2);

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
(1, 'Иван', 'ivan@gmail.com', 'qwest12345', NULL, NULL, '2019-04-01'),
(2, 'Игнат', 'ignat.v@gmail.com', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', NULL, NULL, '2019-06-01'),
(3, 'Леночка', 'kitty_93@li.ru', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', NULL, NULL, NULL),
(4, 'Руслан', 'warrior07@mail.ru', '2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', NULL, NULL, NULL),
(24, 'Евгений', 'zheka.slk2010@gmail.com', '$2y$10$96rNJbH7mNfn2sETtLTXxekEsKM5ndZ/tTFb..aFT0RQ5YORxkBQG', 'img/tmp/phpKg4Wjn', 'никак', '2019-06-18');

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
  ADD KEY `fav_count` (`fav_count`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `rate`
--
ALTER TABLE `rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
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
