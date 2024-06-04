-- phpMyAdmin SQL Dump
-- version 6.0.0-dev
-- https://www.phpmyadmin.net/
--
-- Servidor: 192.168.30.22
-- Tiempo de generación: 20-04-2024 a las 05:36:43
-- Versión del servidor: 10.4.8-MariaDB-1:10.4.8+maria~stretch-log
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `facturacion_tienda_db`
--
CREATE DATABASE IF NOT EXISTS `facturacion_tienda_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `facturacion_tienda_db`;

-- --------------------------------------------------------
key
--
-- Estructura de tabla para la tabla `articulos`
--

DROP TABLE IF EXISTS `articulos`;
CREATE TABLE `articulos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `precio` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id`, `nombre`, `precio`) VALUES
(1, 'Maleta deportiva', 120000),
(2, 'Colchoneta profesional', 60000),
(3, 'Bandas elasticas', 45000),
(4, 'Casco para bicicleta', 179000),
(5, 'Barra multifuncional', 175900),
(6, 'Lazo para ejercicio', 16900),
(7, 'Gorra deportiva', 89990),
(8, 'Botella de agua deportiva', 31990),
(9, 'Kit de entrenamiento', 158900),
(10, 'Canguro riñonera', 29900),
(11, 'Guantes de entrenamiento', 39000),
(12, 'Rueda abdominales', 79900),
(13, 'BAnco de pesas', 419900);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombreCompleto` varchar(150) NOT NULL,
  `tipoDocumento` enum('CC','CE','NIT','TI','Otro') NOT NULL,
  `numeroDocumento` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleFacturas`
--

DROP TABLE IF EXISTS `detalleFacturas`;
CREATE TABLE `detalleFacturas` (
  `id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precioUnitario` double NOT NULL,
  `idArticulo` int(11) NOT NULL,
  `referenciaFactura` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

DROP TABLE IF EXISTS `facturas`;
CREATE TABLE `facturas` (
  `referencia` varchar(150) NOT NULL,
  `fecha` datetime NOT NULL,
  `idCliente` int(11) NOT NULL,
  `estado` enum('Cambio','Devolución','Error','Pagada') NOT NULL DEFAULT 'Pagada',
  `descuento` enum('0','5','10') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `pwd` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `pwd`) VALUES
(1, 'comercial1', 'comercio12345'),
(2, 'comercial2', 'comercio67890');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalleFacturas`
--
ALTER TABLE `detalleFacturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalleFacturas_articulos` (`idArticulo`),
  ADD KEY `fk_detalleFacturas_referenciaFactura` (`referenciaFactura`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`referencia`),
  ADD KEY `fk_facturas_idCliente` (`idCliente`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalleFacturas`
--
ALTER TABLE `detalleFacturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalleFacturas`
--
ALTER TABLE `detalleFacturas`
  ADD CONSTRAINT `fk_detalleFacturas_articulos` FOREIGN KEY (`idArticulo`) REFERENCES `articulos` (`id`),
  ADD CONSTRAINT `fk_detalleFacturas_referenciaFactura` FOREIGN KEY (`referenciaFactura`) REFERENCES `facturas` (`referencia`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `fk_facturas_idCliente` FOREIGN KEY (`idCliente`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;