-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 17 2021 г., 21:56
-- Версия сервера: 10.4.19-MariaDB
-- Версия PHP: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `management`
--

-- --------------------------------------------------------

--
-- Структура таблицы `branch`
--

CREATE TABLE `branch` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` varchar(128) NOT NULL,
  `address` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `branch`
--

INSERT INTO `branch` (`id`, `name`, `description`, `address`) VALUES
(1, 'Магазин №2', 'Первый магазин сети в городе', 'Санкт-Петербург, ул. Горького, 13'),
(2, 'Магазин №3', 'Очень хороший магазин', 'ул. Граничная, д. 3'),
(7, 'Магазин №1', 'Хороший магазин', 'ул. Уличная, д.1'),
(19, 'Магазин №37', ' отзывчивые сорудники, близко от метро, отзывчивые сорудники приятный коллектив, хорошие руководители, 15 минут на транспорте', 'пр-т Героев, д. 14'),
(22, 'Магазин №93', ' близко от метро, отзывчивые сорудники, приятный коллектив', 'ул. Дивенская, д. 45'),
(23, 'Магазин №48', ' близко от метро, отзывчивые сорудники, приятный коллектив близко от метро, много места, 15 минут на транспорте', 'ул. Миллионная, д. 75'),
(24, 'Магазин №17', ' отзывчивые сорудники,большие окна,большие окна', '1-я Никитинская улица, д. 33'),
(25, 'Магазин №81', ' отзывчивые сорудники,большие окна,большие окна приятный коллектив, много места', '1-й Верхний переулок, д. 79'),
(27, 'Магазин №34', ' малая проходимость, отзывчивые сорудники, хорошие руководители', 'ул. Миллионная, д. 77'),
(29, 'Магазин №41', ' много места, 15 минут на транспорте, много места', 'ул. Глухарская, д. 100'),
(30, 'Магазин №30', ' много места, 15 минут на транспорте, много места много места, близко от метро, близко от метро', 'ул. Граничная, д. 58'),
(31, 'Магазин №68', ' отзывчивые сорудники, малая проходимость, отзывчивые сорудники', 'ул. Глухарская, д. 60'),
(32, 'Магазин №23', ' отзывчивые сорудники, малая проходимость, отзывчивые сорудники малая проходимость,большие окна, малая проходимость', '1-й Верхний переулок, д. 16');

-- --------------------------------------------------------

--
-- Структура таблицы `branch_manager`
--

CREATE TABLE `branch_manager` (
  `id` int(11) NOT NULL,
  `id_branch` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `branch_manager`
--

INSERT INTO `branch_manager` (`id`, `id_branch`, `id_user`) VALUES
(46, 1, 2),
(56, 1, 27),
(97, 1, 44),
(100, 1, 47),
(99, 1, 48),
(98, 1, 49),
(29, 2, 6),
(63, 2, 27),
(66, 2, 32),
(31, 2, 53),
(74, 7, 42),
(75, 7, 43),
(77, 7, 45),
(76, 7, 46),
(92, 23, 31),
(91, 23, 42),
(94, 23, 48),
(93, 23, 51),
(96, 24, 2),
(78, 24, 33),
(79, 24, 43),
(80, 24, 46),
(81, 24, 47),
(87, 29, 42),
(88, 29, 45),
(89, 29, 48),
(90, 29, 49),
(82, 30, 32),
(83, 30, 43),
(86, 30, 50),
(84, 30, 51),
(85, 30, 52);

-- --------------------------------------------------------

--
-- Структура таблицы `proposal`
--

CREATE TABLE `proposal` (
  `id` int(11) NOT NULL,
  `id_branch_manager` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` varchar(128) NOT NULL,
  `date` date NOT NULL,
  `wd_start` time NOT NULL,
  `wd_end` time NOT NULL,
  `lunch_start` time NOT NULL,
  `lunch_end` time NOT NULL,
  `rate_per_hour` int(11) NOT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `proposal`
--

INSERT INTO `proposal` (`id`, `id_branch_manager`, `title`, `description`, `date`, `wd_start`, `wd_end`, `lunch_start`, `lunch_end`, `rate_per_hour`, `is_closed`) VALUES
(3, 29, 'Нужен продавец', '123', '2022-01-01', '12:10:03', '48:10:04', '14:10:04', '17:10:04', 125, 1),
(4, 29, 'Нужен продавец-консультант', '123', '2021-10-03', '12:10:03', '48:10:04', '14:10:04', '17:10:04', 125, 1),
(8, 29, 'Нужен кассир', '123', '2022-01-09', '12:10:03', '48:10:04', '14:10:04', '17:10:04', 125, 0),
(22, 31, 'Нужен кассир на два часа', '123', '2022-01-09', '12:10:03', '48:10:04', '14:10:04', '17:10:04', 125, 0),
(23, 56, 'Ждем кассира', 'пожалуйста', '2021-12-18', '10:00:00', '18:00:00', '10:30:00', '11:00:00', 185, 1),
(24, 56, 'Срочно', 'Несколько часов. Срочно', '2021-12-26', '16:00:00', '20:00:00', '16:30:00', '17:00:00', 185, 0),
(25, 56, 'Смена 10 часов', 'Смена', '2021-12-23', '10:00:00', '20:00:00', '10:30:00', '11:00:00', 185, 1),
(26, 46, 'Смена 8 часов - Кассир', 'test1', '2021-12-22', '10:00:00', '18:00:00', '10:30:00', '11:30:00', 185, 0),
(28, 96, 'Срочно кассир', '1', '2021-12-18', '10:00:00', '17:00:00', '10:30:00', '11:00:00', 185, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `id_proposal` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `request`
--

INSERT INTO `request` (`id`, `id_proposal`, `id_user`, `id_status`) VALUES
(53, 8, 1, 5),
(64, 3, 1, 4),
(76, 8, 6, 2),
(86, 24, 6, 5),
(94, 23, 74, 4),
(95, 23, 66, 4),
(96, 23, 64, 4),
(97, 26, 69, 4),
(98, 26, 65, 5),
(99, 26, 60, 5),
(100, 26, 67, 5),
(101, 22, 6, 2),
(103, 23, 61, 4),
(104, 8, 61, 2),
(105, 25, 61, 1),
(107, 24, 63, 3),
(109, 23, 67, 4),
(110, 26, 6, 3),
(111, 28, 70, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'Администратор ИС'),
(2, 'Менеджер'),
(3, 'Сотрудник');

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Подтверждена'),
(2, 'Ожидает подтверждения'),
(3, 'Отменена по инициативе сотрудника'),
(4, 'Отменена по инициативе работодателя'),
(5, 'Отправлена работодателем');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `login` varchar(32) NOT NULL,
  `password` varchar(128) NOT NULL,
  `surname` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `patronymic` varchar(32) DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `passport` int(10) NOT NULL,
  `snils` int(11) NOT NULL,
  `inn` int(10) NOT NULL,
  `phone_number` bigint(10) NOT NULL,
  `email` varchar(32) NOT NULL,
  `salary_increase` int(11) NOT NULL DEFAULT 1,
  `hash` varchar(128) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `id_role`, `login`, `password`, `surname`, `name`, `patronymic`, `date_of_birth`, `passport`, `snils`, `inn`, `phone_number`, `email`, `salary_increase`, `hash`, `profile_photo`) VALUES
(1, 3, 'user1', 'user1', 'Мартов', 'Марат', 'Маратович', '1000-01-01', 0, 0, 0, 0, 'test@test', 1, '', ''),
(2, 1, 'root', '63a9f0ea7bb98050796b649e85481845', 'Иванова', 'Раиса', 'Ивановна', '1900-01-02', 3, 4, 5, 0, 'root@root', 1, '', ''),
(6, 3, 'test', '63a9f0ea7bb98050796b649e85481845', 'Игорев', 'Андрей', 'Петрович', '1000-01-01', 0, 0, 0, 0, 'test@test', 1, 'f969d5656c64148e62cc3b7a2c123e48', ''),
(23, 3, 'test177277', '202cb962ac59075b964b07152d234b70', 'Кристалл', 'Игорь', 'Филиппович', '2021-12-01', 123, 123, 123, 1234, 'test177277@mail.ru', 1, '0094aa0127a5692083572837af186570', NULL),
(27, 2, 'manager', '63a9f0ea7bb98050796b649e85481845', 'Петров', 'Петр', 'Петрович', '1900-01-02', 3, 4, 5, 0, 'root@root', 1, '', ''),
(31, 2, 'manager4', '2bda317a24025af3b6414bef1f43b3f8', 'Шилов', 'Иван', 'Иванович', '2021-10-13', 23455, 55, 55454, 345, 'test1234@test', 1, 'f142be57de895486cfb020ef888110ce', ''),
(32, 2, 'manager5', '202cb962ac59075b964b07152d234b70', 'Иванов', 'Иван', 'Иванович', '2021-12-01', 123, 123, 123, 1234, 'test177277@mail.ru', 1, '0094aa0127a5692083572837af186570', NULL),
(33, 2, 'manager6', '45bb51428137bf92a557e00c265fe11d', 'Лазарев', 'Денис', 'Антонович', '2005-12-08', 111, 111, 11, 1234567789, 'ddddd@ttr.u', 1, 'a77d354b6ac8e3846da3685fb0f66305', NULL),
(42, 2, 'manager16392079441', '123', 'Федоров', 'Никита', 'Павлович', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(43, 2, 'manager16392079771', '123', 'Рубцова', 'Ева', 'Юрьевна', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(44, 2, 'manager16392079772', '123', 'Александрова', 'Ольга', '', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(45, 2, 'manager16392079773', '123', 'Рубцова', 'Кира', 'Степановна', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(46, 2, 'manager16392079774', '123', 'Майоров', 'Никита', 'Львович', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(47, 2, 'manager16392079775', '123', 'Леонов', 'Роман', 'Кириллович', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(48, 2, 'manager16392080401', '123', 'Андреева', 'Полина', 'Игоревна', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(49, 2, 'manager16392080402', '123', 'Иванов', 'Руслан', 'Александрович', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(50, 2, 'manager16392080403', '123', 'Тарасов', 'Георгий', '', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(51, 2, 'manager16392080404', '123', 'Александрова', 'Ясмина', '', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(52, 2, 'manager16392080405', '123', 'Евсеева', 'Вера', 'Всеволодовна', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(53, 2, 'manager16392080406', '123', 'Смирнова', 'Эмма', 'Игоревна', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(54, 1, 'admin16392080441', '123', 'Попова', 'Вера', 'Владимировна', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(55, 1, 'admin16392080442', '123', 'Федоров', 'Дмитрий', 'Сергеевич', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(56, 1, 'admin16392080443', '123', 'Рубцова', 'София', 'Никитична', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(57, 1, 'admin16392080444', '123', 'Герасимов', 'Михаил', '', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(58, 1, 'admin16392080445', '123', 'Сидоров', 'Александр', '', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(59, 1, 'admin16392080446', '123', 'Карпова', 'Ева', 'Руслановна', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(60, 3, 'user16392084841', '123', 'Иванов', 'Артём', '', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(61, 3, 'test3', '63a9f0ea7bb98050796b649e85481845', 'Жукова', 'Василиса', 'Максимовна', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(62, 3, 'user16392084843', '123', 'Евсеева', 'Василиса', 'Степановна', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(63, 3, 'user16392084844', '63a9f0ea7bb98050796b649e85481845', 'Захарова', 'Елизавета', '', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(64, 3, 'user16392084845', '123', 'Греков', 'Герман', 'Павлович', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(65, 3, 'user16392084846', '123', 'Юдин', 'Даниил', 'Кириллович', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(66, 3, 'user16392084847', '123', 'Смирнова', 'Вера', '', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(67, 3, 'user16392084848', '123', 'Сидоров', 'Никита', 'Михайлович', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(68, 3, 'user16392084849', '123', 'Попова', 'Ольга', 'Руслановна', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(69, 3, 'user163920848410', '123', 'Козлова', 'Варвара', 'Игоревна', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(70, 3, 'user163920848411', '123', 'Алексеева', 'Ева', '', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(71, 3, 'user163920848412', '123', 'Покровская', 'Эмма', '', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(72, 3, 'user163920848413', '123', 'Смирнова', '', '', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(74, 3, 'user163920848415', '123', 'Мельников', 'Максим', '', '2021-12-30', 134, 124, 135, 1344, '333', 1, '', ''),
(76, 3, 'user', 'null', 'null', 'null', 'null', '1000-01-01', 0, 0, 0, 0, 'user@mail.ru', 1, '4bdcde4126d7f6d1c9891a4fe51ba732', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `branch_manager`
--
ALTER TABLE `branch_manager`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_branch_2` (`id_branch`,`id_user`),
  ADD KEY `ID_BRANCH` (`id_branch`) USING BTREE,
  ADD KEY `ID_USER` (`id_user`) USING BTREE;

--
-- Индексы таблицы `proposal`
--
ALTER TABLE `proposal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_branch_manager` (`id_branch_manager`) USING BTREE;

--
-- Индексы таблицы `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_user_2` (`id_user`,`id_proposal`) USING BTREE,
  ADD KEY `id_proposal` (`id_proposal`) USING BTREE,
  ADD KEY `id_status` (`id_status`) USING BTREE,
  ADD KEY `id_user` (`id_user`) USING BTREE;

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_role` (`id_role`) USING BTREE;

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `branch`
--
ALTER TABLE `branch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `branch_manager`
--
ALTER TABLE `branch_manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT для таблицы `proposal`
--
ALTER TABLE `proposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `branch_manager`
--
ALTER TABLE `branch_manager`
  ADD CONSTRAINT `branch_manager_ibfk_1` FOREIGN KEY (`id_branch`) REFERENCES `branch` (`id`),
  ADD CONSTRAINT `branch_manager_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `proposal`
--
ALTER TABLE `proposal`
  ADD CONSTRAINT `proposal_ibfk_1` FOREIGN KEY (`id_branch_manager`) REFERENCES `branch_manager` (`id`);

--
-- Ограничения внешнего ключа таблицы `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`id_proposal`) REFERENCES `proposal` (`id`),
  ADD CONSTRAINT `request_ibfk_3` FOREIGN KEY (`id_status`) REFERENCES `status` (`id`);

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
