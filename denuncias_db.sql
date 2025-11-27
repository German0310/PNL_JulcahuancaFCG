-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-11-2025 a las 23:59:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `denuncias_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `denuncias`
--

CREATE TABLE `denuncias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `ubicacion` varchar(150) NOT NULL,
  `estado` varchar(20) DEFAULT 'Pendiente',
  `ciudadano` varchar(100) NOT NULL,
  `telefono_ciudadano` varchar(15) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `denuncias`
--

INSERT INTO `denuncias` (`id`, `titulo`, `descripcion`, `ubicacion`, `estado`, `ciudadano`, `telefono_ciudadano`, `fecha_registro`) VALUES
(1, 'Bache en la calle', 'Hay un bache muy grande', ' Av. Principal esquina con Jr. 2do', 'pendiente', 'Juan Pérez', '987654321', '2025-11-17 19:17:26'),
(2, 'Parque sucio', 'El parque de la plaza está lleno de basura y perros sueltos.', 'Parque Principal', 'resuelto', 'María López', '912345678', '2025-11-17 19:17:26'),
(4, 'Bache en la calle', 'Hay un bache muy grande en la esquina que representa peligro para los vehículos', 'Av. Principal esquina con Calle Secundaria', 'en proceso', 'Juan Pérez', '', '2025-11-17 20:28:30'),
(5, 'Parque sucio', 'El parque de la plaza está lleno de basura y necesita limpieza urgente', 'Parque Principal del distrito', 'en proceso', 'María López', '', '2025-11-17 20:28:30'),
(6, 'Recolección de basura', 'No se recogió la basura ayer como era scheduled, hay acumulación de desechos', 'Av. Mexico cuadra 5', 'resuelto', 'Carlos García', '', '2025-11-17 20:28:30'),
(7, 'Alumbrado público dañado', 'Poste de luz no funciona en toda la cuadra, es peligroso de noche', 'Calle Los Pinos 123', 'pendiente', 'Ana Martínez', '', '2025-11-17 20:28:30'),
(8, 'Fuga de agua', 'Tubería rota está causando fuga de agua y inundación en la vereda', 'Jr. Union 456', 'resuelto', 'Roberto Sánchez', '', '2025-11-17 20:28:30');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `denuncias`
--
ALTER TABLE `denuncias`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `denuncias`
--
ALTER TABLE `denuncias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
