-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Янв 30 2022 г., 10:37
-- Версия сервера: 5.7.32
-- Версия PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `warships`
--

-- --------------------------------------------------------

--
-- Структура таблицы `buildings`
--

CREATE TABLE `buildings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `building_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `lvl` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `buildings`
--

INSERT INTO `buildings` (`id`, `building_id`, `city_id`, `lvl`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(2, 2, 1, 1, '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(3, 3, 1, 2, '2022-01-30 07:30:21', '2022-01-30 07:30:21');

-- --------------------------------------------------------

--
-- Структура таблицы `building_dictionary`
--

CREATE TABLE `building_dictionary` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `building_dictionary`
--

INSERT INTO `building_dictionary` (`id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Главное управление', 'Главное здание на острове', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(2, 'Шахта', 'Здесь добывается золото', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(3, 'Дом', 'Чем больше домов, тем больше рабочих рук!', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(4, 'Таверна', 'Повышает престиж острова и увеличивает приток населения', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(5, 'Ферма', 'Здесь добывается еда!', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(6, 'Верфь', 'Здесь производятся военные корабли', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(7, 'Пристань', 'Позволяет рыбакам ловить рыбу, а торговцам проводить свои операции', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(8, 'Форт', 'Основное защитное сооружение на острове', '2022-01-30 07:30:21', '2022-01-30 07:30:21');

-- --------------------------------------------------------

--
-- Структура таблицы `building_resources`
--

CREATE TABLE `building_resources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `building_id` bigint(20) UNSIGNED NOT NULL,
  `gold` int(11) NOT NULL,
  `population` int(11) NOT NULL,
  `lvl` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `building_resources`
--

INSERT INTO `building_resources` (`id`, `building_id`, `gold`, `population`, `lvl`, `created_at`, `updated_at`) VALUES
(1, 1, 100, 20, 1, '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(2, 1, 200, 30, 2, '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(3, 1, 500, 50, 3, '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(4, 2, 200, 50, 1, '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(5, 2, 300, 70, 2, '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(6, 2, 500, 100, 3, '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(7, 3, 100, 0, 1, '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(8, 3, 200, 0, 2, '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(9, 3, 400, 0, 3, '2022-01-30 07:30:21', '2022-01-30 07:30:21');

-- --------------------------------------------------------

--
-- Структура таблицы `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coord_x` int(11) NOT NULL,
  `coord_y` int(11) NOT NULL,
  `gold` int(11) NOT NULL,
  `population` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cities`
--

INSERT INTO `cities` (`id`, `user_id`, `title`, `coord_x`, `coord_y`, `gold`, `population`, `created_at`, `updated_at`) VALUES
(1, 1, 'Остров Alex-a', 1, 1, 1000, 200, '2022-01-30 07:30:21', '2022-01-30 07:30:21');

-- --------------------------------------------------------

--
-- Структура таблицы `city_building_queues`
--

CREATE TABLE `city_building_queues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `building_id` bigint(20) UNSIGNED NOT NULL,
  `gold` int(11) NOT NULL,
  `population` int(11) NOT NULL,
  `lvl` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `deadline` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `city_building_queues`
--

INSERT INTO `city_building_queues` (`id`, `city_id`, `building_id`, `gold`, `population`, `lvl`, `time`, `deadline`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 300, 70, 2, 37, '2022-01-30 07:30:58', '2022-01-30 07:30:21', '2022-01-30 07:30:21');

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_01_08_174901_create_cities_table', 1),
(6, '2022_01_19_201900_create_building_dictionary_table', 1),
(7, '2022_01_19_201909_create_buildings_table', 1),
(8, '2022_01_20_210349_create_building_resources_table', 1),
(9, '2022_01_22_193601_create_city_building_queues_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Alex', 'alex@test.ru', '2022-01-30 07:30:21', '$2y$10$gBVAZka0cpwy6KlNAkTsLOcNOcTIVn.3Te/9KxepYdDKEYrFrSQ2G', '4qpcE0iPps', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(2, 'Nasir', 'dkoelpin@example.org', '2022-01-30 07:30:21', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'J9jjPlNltT', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(3, 'Sylvia', 'winnifred.zulauf@example.org', '2022-01-30 07:30:21', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'KQ7RM0b1LI', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(4, 'Collin', 'oconner.juana@example.com', '2022-01-30 07:30:21', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'KPL0PxmzK7', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(5, 'Reyes', 'dtremblay@example.org', '2022-01-30 07:30:21', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'UuM1AJuFfb', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(6, 'Micaela', 'lou.ortiz@example.org', '2022-01-30 07:30:21', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'uWasPNqVHW', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(7, 'Sigmund', 'okon.lauryn@example.org', '2022-01-30 07:30:21', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0q1sLLXKqL', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(8, 'Zoey', 'manley85@example.org', '2022-01-30 07:30:21', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tPFAi9WRyI', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(9, 'Leone', 'rbaumbach@example.com', '2022-01-30 07:30:21', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'GUt5lxccMS', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(10, 'Melisa', 'stroman.tyreek@example.org', '2022-01-30 07:30:21', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'LlB5eQOq2x', '2022-01-30 07:30:21', '2022-01-30 07:30:21'),
(11, 'Lavada', 'peyton.quitzon@example.com', '2022-01-30 07:30:21', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'phs6oU3H8q', '2022-01-30 07:30:21', '2022-01-30 07:30:21');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buildings_building_id_foreign` (`building_id`),
  ADD KEY `buildings_city_id_foreign` (`city_id`);

--
-- Индексы таблицы `building_dictionary`
--
ALTER TABLE `building_dictionary`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `building_resources`
--
ALTER TABLE `building_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `building_resources_building_id_foreign` (`building_id`);

--
-- Индексы таблицы `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `city_building_queues`
--
ALTER TABLE `city_building_queues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_building_queues_city_id_foreign` (`city_id`),
  ADD KEY `city_building_queues_building_id_foreign` (`building_id`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `building_dictionary`
--
ALTER TABLE `building_dictionary`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `building_resources`
--
ALTER TABLE `building_resources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `city_building_queues`
--
ALTER TABLE `city_building_queues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `buildings`
--
ALTER TABLE `buildings`
  ADD CONSTRAINT `buildings_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `buildings_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `building_resources`
--
ALTER TABLE `building_resources`
  ADD CONSTRAINT `building_resources_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`);

--
-- Ограничения внешнего ключа таблицы `city_building_queues`
--
ALTER TABLE `city_building_queues`
  ADD CONSTRAINT `city_building_queues_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`),
  ADD CONSTRAINT `city_building_queues_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
