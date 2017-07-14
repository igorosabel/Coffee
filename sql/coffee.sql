-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 14-07-2017 a las 11:31:19
-- Versión del servidor: 5.5.55-0+deb8u1
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `coffee`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coffee`
--

DROP TABLE IF EXISTS `coffee`;
CREATE TABLE `coffee` (
  `id` int(11) NOT NULL COMMENT 'Id único de cada día que se ha bajado al café',
  `d` int(11) NOT NULL COMMENT 'Día que se ha bajado al café',
  `m` int(11) NOT NULL COMMENT 'Mes que se ha bajado al café',
  `y` int(11) NOT NULL COMMENT 'Año que se ha bajado al café',
  `special` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indica si es un día especial (viernes de pintxo)',
  `id_person` int(11) DEFAULT NULL COMMENT 'Id de la persona que ha pagado',
  `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` datetime NOT NULL COMMENT 'Fecha de última modificación del registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `coffee`
--

INSERT INTO `coffee` (`id`, `d`, `m`, `y`, `special`, `id_person`, `created_at`, `updated_at`) VALUES
(1, 11, 7, 2017, 0, 5, '2017-07-11 11:16:02', '2017-07-11 11:16:02'),
(2, 12, 7, 2017, 0, 4, '2017-07-12 11:35:51', '2017-07-12 11:35:51'),
(3, 13, 7, 2017, 0, 6, '2017-07-13 11:22:06', '2017-07-13 11:22:06'),
(4, 14, 7, 2017, 1, 4, '2017-07-14 11:11:13', '2017-07-14 11:11:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person`
--

DROP TABLE IF EXISTS `person`;
CREATE TABLE `person` (
  `id` int(11) NOT NULL COMMENT 'Id único de cada persona',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nombre de la persona',
  `num_coffee` int(11) NOT NULL DEFAULT '0' COMMENT 'Número de veces que ha bajado al café',
  `num_pay` int(11) NOT NULL DEFAULT '0' COMMENT 'Número de veces que ha pagado',
  `num_special` int(11) NOT NULL DEFAULT '0' COMMENT 'Número de viernes que ha bajado',
  `num_special_pay` int(11) NOT NULL DEFAULT '0' COMMENT 'Número de viernes que ha pagado',
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Color para identificar a la persona',
  `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` datetime NOT NULL COMMENT 'Fecha de última modificación del registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `person`
--

INSERT INTO `person` (`id`, `name`, `num_coffee`, `num_pay`, `num_special`, `num_special_pay`, `color`, `created_at`, `updated_at`) VALUES
(1, 'Iñigo', 4, 0, 1, 0, '#2525bd', '2017-07-03 14:44:31', '2017-07-14 11:11:13'),
(3, 'Txopi', 4, 0, 1, 0, '#c11c1c', '2017-07-11 09:35:03', '2017-07-14 11:11:13'),
(4, 'Aitor', 4, 2, 1, 1, '#f7eb4e', '2017-07-11 09:35:22', '2017-07-14 11:11:13'),
(5, 'Unai', 4, 1, 1, 0, '#47a5d2', '2017-07-11 09:35:38', '2017-07-14 11:11:13'),
(6, 'Joxean', 3, 1, 1, 0, '#43c971', '2017-07-11 09:39:19', '2017-07-14 11:11:13'),
(7, 'Entzi', 1, 0, 0, 0, '#ff8104', '2017-07-11 11:15:48', '2017-07-11 11:15:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `went`
--

DROP TABLE IF EXISTS `went`;
CREATE TABLE `went` (
  `id_person` int(11) NOT NULL COMMENT 'Id de la persona que ha bajado al café',
  `id_coffee` int(11) NOT NULL COMMENT 'Id de la vez que se ha bajado al café',
  `pay` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indica si la persona ha pagado 1 o no 0',
  `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` datetime NOT NULL COMMENT 'Fecha de última modificación del registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `went`
--

INSERT INTO `went` (`id_person`, `id_coffee`, `pay`, `created_at`, `updated_at`) VALUES
(1, 1, 0, '2017-07-11 11:16:02', '2017-07-11 11:16:02'),
(1, 2, 0, '2017-07-12 11:35:51', '2017-07-12 11:35:51'),
(1, 3, 0, '2017-07-13 11:22:06', '2017-07-13 11:22:06'),
(1, 4, 0, '2017-07-14 11:11:13', '2017-07-14 11:11:13'),
(3, 1, 0, '2017-07-11 11:16:02', '2017-07-11 11:16:02'),
(3, 2, 0, '2017-07-12 11:35:51', '2017-07-12 11:35:51'),
(3, 3, 0, '2017-07-13 11:22:06', '2017-07-13 11:22:06'),
(3, 4, 0, '2017-07-14 11:11:13', '2017-07-14 11:11:13'),
(4, 1, 0, '2017-07-11 11:16:02', '2017-07-11 11:16:02'),
(4, 2, 1, '2017-07-12 11:35:51', '2017-07-12 11:35:51'),
(4, 3, 0, '2017-07-13 11:22:06', '2017-07-13 11:22:06'),
(4, 4, 1, '2017-07-14 11:11:13', '2017-07-14 11:11:13'),
(5, 1, 1, '2017-07-11 11:16:02', '2017-07-11 11:16:02'),
(5, 2, 0, '2017-07-12 11:35:51', '2017-07-12 11:35:51'),
(5, 3, 0, '2017-07-13 11:22:06', '2017-07-13 11:22:06'),
(5, 4, 0, '2017-07-14 11:11:13', '2017-07-14 11:11:13'),
(6, 1, 0, '2017-07-11 11:16:02', '2017-07-11 11:16:02'),
(6, 3, 1, '2017-07-13 11:22:06', '2017-07-13 11:22:06'),
(6, 4, 0, '2017-07-14 11:11:13', '2017-07-14 11:11:13'),
(7, 1, 0, '2017-07-11 11:16:02', '2017-07-11 11:16:02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `coffee`
--
ALTER TABLE `coffee`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `went`
--
ALTER TABLE `went`
  ADD PRIMARY KEY (`id_person`,`id_coffee`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `coffee`
--
ALTER TABLE `coffee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada día que se ha bajado al café', AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada persona', AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
