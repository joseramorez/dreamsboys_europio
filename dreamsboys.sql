-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-05-2018 a las 07:22:24
-- Versión del servidor: 10.1.28-MariaDB
-- Versión de PHP: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dreamsboys`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `categoria_id` int(11) NOT NULL,
  `nombre_categoria` varchar(255) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`categoria_id`, `nombre_categoria`, `descripcion`) VALUES
(1, 'accesorios', 'pulceras, cadenas'),
(2, 'ropa', 'pantalones, plalleras'),
(3, 'tabaquería', 'tomacos'),
(4, 'skate', 'skate'),
(5, 'caps & Hats', 'gorras y sombreros'),
(6, 'graffitti', 'fitti'),
(7, 'bags', 'mochilas y bolsas'),
(8, 'toys', 'toys'),
(9, 'body pearcing', 'pearcing'),
(10, 'calzado', 'zapapos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comision`
--

CREATE TABLE `comision` (
  `comision_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_final` date DEFAULT NULL,
  `total_ventas` float DEFAULT NULL,
  `porcentaje_comision` int(11) DEFAULT NULL,
  `total_comision` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `compra_id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `fecha_compra` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`compra_id`, `proveedor_id`, `fecha_compra`) VALUES
(1, 1, '2017-01-28'),
(2, 1, '2017-01-27'),
(3, 1, '2017-01-15'),
(4, 1, '2017-01-24'),
(5, 1, '2017-01-28'),
(6, 1, '2017-01-29'),
(7, 1, '2018-05-05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_detalle`
--

CREATE TABLE `compra_detalle` (
  `compra_detalle_id` int(11) NOT NULL,
  `compra_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_compra` float DEFAULT NULL,
  `precio_venta` float DEFAULT NULL,
  `importe` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `compra_detalle`
--

INSERT INTO `compra_detalle` (`compra_detalle_id`, `compra_id`, `producto_id`, `cantidad`, `precio_compra`, `precio_venta`, `importe`) VALUES
(1, 1, 1, 1, 100, 200, 100),
(2, 2, 1, 1, 100, 200, 100),
(3, 2, 2, 1, 100, 120, 100),
(5, 3, 1, 1, 100, 200, 100),
(6, 5, 1, 2, 100, 200, 200),
(7, 6, 1, 10, 100, 200, 1000),
(8, 7, 56, 3, 194, 195, 582);

--
-- Disparadores `compra_detalle`
--
DELIMITER $$
CREATE TRIGGER `compra_producto_actualizar` AFTER UPDATE ON `compra_detalle` FOR EACH ROW UPDATE producto
SET existencia = (existencia - OLD.cantidad) + NEW.cantidad,
precio_compra = NEW.precio_compra,
precio_venta = NEW.precio_venta
WHERE producto_id = OLD.producto_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `compra_producto_restar` AFTER DELETE ON `compra_detalle` FOR EACH ROW UPDATE producto
SET existencia = existencia - OLD.cantidad 
WHERE producto_id = OLD.producto_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `compra_producto_sumar` AFTER INSERT ON `compra_detalle` FOR EACH ROW UPDATE producto
SET 
existencia = existencia + NEW.cantidad,
precio_compra = NEW.precio_compra,
precio_venta = NEW.precio_venta
WHERE
producto_id = NEW.producto_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `correo`
--

CREATE TABLE `correo` (
  `correo_id` int(11) NOT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `correo`
--

INSERT INTO `correo` (`correo_id`, `correo`, `pass`) VALUES
(1, 'joseramorez.jr@gmail.com', 'bmVtb2thb3MxMDMzNDg1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `empleado_id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido_paterno` varchar(255) DEFAULT NULL,
  `apellido_materno` varchar(255) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` int(11) DEFAULT NULL,
  `curp` varchar(255) DEFAULT NULL,
  `rfc` varchar(255) DEFAULT NULL,
  `salario` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`empleado_id`, `nombre`, `apellido_paterno`, `apellido_materno`, `edad`, `fecha_nacimiento`, `direccion`, `telefono`, `curp`, `rfc`, `salario`) VALUES
(1, 'Jose antonio', 'Ramirez ', 'mendoza', 25, '1991-03-23', 'priv. 5 de mayo ', 2147483647, 'odsafhih7823', 'oifndsao8y ', 1000),
(2, 'KAREN', 'MORALES', 'GONZALES', 23, '1993-06-28', 'calle galiana', 2147483647, 'kamg15525', '1as5d1', 1200),
(3, 'sheldon', 'bazinga', 'bazinga', 30, '1999-02-18', 'qwedrftgyhu', 2345, 'sderfty', 'qswedrftgy', 300),
(4, 'jesus', 'iluhicamina', 'quintero', 25, '2007-06-20', 'dafewffww', 2345434, '22332323d', 'wewedewe', 400),
(5, 'pedro', 'ancelmo', 'feria', 30, '2000-02-18', '2edxsdcx', 23456543, 'asdfgfdsasdf', 'sdfdsaqsdfds', 200),
(6, 'maria', 'ruiz', 'ortiz', 23, '2017-02-03', '33 wsedfwsd', 2147483647, 'wdwsxdsdc', 'ssaqasasxs', 300),
(7, 'mauricio ', 'espeda', 'cruz', 19, '2017-02-17', '23 wedfdswedfcd', 2147483647, 'wsdfcxsedc', 'wsdwsddsd', 333),
(8, 'excelsa', 'algo ', 'algo', 22, '2009-02-10', 'argentina shjdksnhjd', 432432, 'asdewqaz', 'werfderfd', 112),
(9, 'pepe ', 'the ', 'jarcor', 25, '2008-03-23', 'barrio mala muerte', 2345432, 'ramer4qweqweqw', 'qwewewe', 2000),
(10, 'jackie', 'chan', 'chan', 34, '2017-02-02', 'qwertert', 234566543, 'sdfgtrewscgfree', '3rfde4rtfgcdsw34er', 400);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `marca_id` int(11) NOT NULL,
  `nombre_marca` varchar(255) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`marca_id`, `nombre_marca`, `descripcion`) VALUES
(1, 'adidas', 'tenis camisas'),
(2, 'nine', 'tesnis pantalones '),
(3, 'patito', 'patito'),
(4, 'china', 'china'),
(5, 'vietnamita', 'vietnamita'),
(6, 'corriente', 'corriente'),
(7, 'hecho en mexico', 'hecho en mexico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_empleado`
--

CREATE TABLE `pago_empleado` (
  `pago_empleado_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `fecha_pago_inicio` date DEFAULT NULL,
  `fecha_pago_final` date DEFAULT NULL,
  `total_prestamo` float DEFAULT NULL,
  `pago` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pago_empleado`
--

INSERT INTO `pago_empleado` (`pago_empleado_id`, `empleado_id`, `fecha_pago_inicio`, `fecha_pago_final`, `total_prestamo`, `pago`) VALUES
(1, 1, '2017-01-23', '2017-01-29', 100, 900),
(2, 2, '2017-01-23', '2017-01-29', 0, 1200),
(10, 2, '2017-01-29', '2017-01-24', 0, 1200);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE `prestamo` (
  `prestamo_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `prestamo` float DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`prestamo_id`, `empleado_id`, `prestamo`, `fecha`) VALUES
(1, 1, 100, '2017-01-27'),
(3, 2, 100, '2017-01-29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `producto_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `nombre_producto` varchar(255) DEFAULT NULL,
  `marca_id` int(11) DEFAULT NULL,
  `talla` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `modelo` varchar(255) DEFAULT NULL,
  `precio_compra` float DEFAULT NULL,
  `precio_venta` float DEFAULT NULL,
  `existencia` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `codigo` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`producto_id`, `categoria_id`, `nombre_producto`, `marca_id`, `talla`, `color`, `modelo`, `precio_compra`, `precio_venta`, `existencia`, `stock`, `codigo`, `imagen`) VALUES
(1, 1, 'cadena para llaves', 1, 'mediana', 'plata', 'ljsfak213', 100, 200, 23, 11, 123456, 'cadena.jpg'),
(2, 1, 'gorra negra tradicional', 1, 'mediana', 'negra', '1sad651', 100, 120, 100, 12, 1234567, 'gorra.jpg'),
(3, 1, 'arete1', 3, 'chica', 'dorado', '111', 111, 112, 106, 3, 111, 'arete1.jpg'),
(4, 1, 'arete2', 7, 'mediana', 'azul', '112', 112, 113, 103, 3, 112, 'arete2.jpg'),
(5, 1, 'arete3', 3, 'grande', 'morado', '113', 113, 114, 108, 0, 113, ''),
(6, 1, 'arete4', 5, 'chica', 'café', '114', 114, 115, 113, 0, 114, ''),
(7, 1, 'arete5', 6, 'chica', 'blanco', '115', 115, 116, 115, 0, 115, ''),
(8, 1, 'collar1', 1, 'chica', 'rosa', '121', 121, 122, 118, 3, 121, 'collar1.jpg'),
(9, 1, 'collar2', 2, 'mediana', 'turqueza', '122', 122, 123, 117, 3, 122, 'collar2.jpg'),
(10, 1, 'collar3', 3, 'grande', 'azul', '123', 123, 124, 119, 3, 123, 'collar3.jpg'),
(11, 1, 'collar4', 4, 'chica', 'azul-negro', '124', 124, 125, 121, 3, 124, 'collar4.jpg'),
(12, 1, 'collar5', 5, 'mediana', 'rojo', '125', 125, 126, 124, 3, 125, 'collar5.jpg'),
(13, 4, 'skate1', 6, 'tabla', 'negro, rojo, blanco', '131', 131, 132, 126, 3, 131, 'skate1.jpg'),
(14, 4, 'skate2', 6, 'única', 'verde, rojo, amarillo', '132', 132, 133, 127, 3, 132, 'skate2.jpg'),
(15, 4, 'skate3', 7, '133', 'rojo, verde, amarillo, negro', '133', 133, 134, 127, 3, 133, 'skate3.jpg'),
(16, 4, '134', 1, '134', 'negro rojo blanco', '134', 134, 135, 133, 3, 134, 'skate4.jpg'),
(17, 4, '135', 2, '135', 'negro verde blanco', '135', 135, 136, 128, 3, 135, 'skate5.jpg'),
(18, 4, 'skate6', 2, '136', 'verde blanco amarillo', '136', 136, 137, 136, 3, 136, 'skate6.jpg'),
(19, 4, 'skate7', 3, '137', 'amarillo verde crema', '137', 137, 138, 132, 3, 137, 'skate7.jpg'),
(20, 4, 'skate8', 4, '138', 'azul blanco rojo', '138', 138, 139, 138, 3, 138, 'skate8.jpg'),
(21, 4, '139', 5, '139', 'negro blanco', '139', 139, 139, 139, 3, 139, 'skate9.jpg'),
(22, 4, 'skate10', 6, '130', 'negro blanco', '130', 130, 131, 130, 3, 130, 'skate10.jpg'),
(23, 2, 'sudadera1', 6, '141', 'gris', '141', 141, 142, 130, 3, 141, 'sudadera1.jpg'),
(24, 2, 'sudadera2', 7, '142', 'azul', '142', 142, 143, 77, 3, 142, 'sudadera2.jpg'),
(25, 2, 'sudadera3', 1, '143', 'rojo', '143', 143, 144, 83, 3, 143, 'sudadera3.jpg'),
(26, 2, 'sudadera4', 2, '144', 'negro', '144', 144, 145, 140, 3, 145, 'sudadera4.jpg'),
(27, 2, 'sudadera5', 3, '145', 'guinda', '145', 145, 146, 145, 3, 140, 'sudadera5.jpg'),
(28, 2, 'pantalon1', 4, '31', 'gris', '151', 151, 152, 145, 3, 151, 'pantalon1.jpg'),
(29, 2, 'pantalon2', 5, '32', 'azul', '152', 152, 153, 149, 3, 152, 'pantalon2.jpg'),
(30, 2, 'pantalon3', 6, '33', 'gris rojo', '153', 153, 154, 153, 3, 153, 'pantalon3.jpg'),
(31, 2, 'pantalon4', 7, '34', 'negro', '154', 154, 155, 150, 3, 154, 'pantalon4.jpg'),
(32, 2, 'pantalon5', 1, '35', 'azul', '155', 155, 156, 150, 3, 155, 'pantalon5.jpg'),
(33, 5, 'gorra1', 1, '11', 'rojo', '161', 161, 162, 134, 3, 161, 'gorra1.jpg'),
(34, 5, 'gorra2', 2, '12', 'azul marino', '162', 162, 163, 154, 3, 162, 'gorra2.jpg'),
(35, 5, 'gorra3', 3, '13', 'azul', '163', 163, 164, 160, 3, 163, 'gorra3.jpg'),
(36, 5, 'gorra4', 4, '14', 'azul marino', '164', 164, 165, 159, 3, 164, 'gorra4.jpg'),
(37, 5, 'gorra5', 5, '15', 'negro', '165', 165, 166, 164, 3, 165, 'gorra5.jpg'),
(38, 5, 'gorra6', 1, '16', 'azul', '166', 166, 167, 166, 3, 166, 'gorra6.jpg'),
(39, 5, 'gorra7', 2, '17', 'amarillo', '167', 167, 168, 167, 3, 167, 'gorra7.jpg'),
(40, 5, 'gorra8', 3, '18', 'rojo', '168', 168, 169, 168, 3, 168, 'gorra8.jpg'),
(41, 5, 'gorra9', 4, '19', 'negro azul', '169', 169, 170, 169, 3, 169, 'gorra9.jpg'),
(42, 5, 'gorra10', 4, '10', 'blaanco', '160', 160, 161, 160, 3, 160, 'gorra10.jpg'),
(43, 3, 'pipa1', 6, '1', 'café', '171', 171, 172, 169, 3, 171, 'pipa1.jpg'),
(44, 3, 'pipa2', 7, '2', 'plata', '172', 172, 173, 166, 3, 172, 'pipa2.jpg'),
(45, 3, 'pipa3', 1, '3', 'dorado', '173', 173, 174, 164, 3, 173, 'pipa3.jpg'),
(46, 3, 'pipa4', 2, '4', 'rayas', '174', 174, 175, 172, 3, 174, 'pipa4.jpg'),
(47, 3, 'pipa5', 3, '4', 'madera', '175', 175, 176, 175, 3, 175, 'pipa5.jpg'),
(48, 3, 'shisha1', 4, '1', 'negro', '181', 181, 182, 178, 3, 181, 'shisha1.jpg'),
(49, 3, 'shisha2', 5, '2', 'café', '182', 182, 183, 126, 3, 182, 'shisha2.jpg'),
(50, 3, 'shisha3', 6, '3', 'azul', '183', 183, 184, 98, 3, 183, 'shisha3.jpg'),
(51, 3, 'shisha4', 1, '4', 'morado', '184', 184, 185, 184, 3, 184, 'shisha4.jpg'),
(52, 3, 'shisha5', 2, '5', 'rojo', '185', 185, 186, 185, 3, 185, 'shisha5.jpg'),
(53, 7, 'mochila1', 1, '1', 'negro', '191', 191, 192, 190, 3, 191, 'bag1.jpg'),
(54, 7, 'mochila2', 2, '2', 'rosa', '192', 192, 193, 168, 3, 192, 'bag2.jpg'),
(55, 7, 'mochila3', 3, '3', 'azul', '193', 193, 194, 188, 3, 193, 'bag3.jpg'),
(56, 7, 'mochila4', 4, '4', 'morado', '194', 194, 195, 197, 3, 194, 'bag4.jpg'),
(57, 7, 'mochila5', 5, '5', 'gris', '195', 195, 196, 195, 3, 195, 'bag5.jpg'),
(58, 7, 'mochila6', 6, '6', 'rojo', '196', 196, 197, 196, 3, 196, 'bag6.png'),
(59, 7, 'mochila7', 7, '7', 'gris', '197', 197, 198, 197, 3, 197, 'bag7.jpg'),
(60, 7, 'mochila8', 1, '8', 'azul', '198', 198, 199, 198, 3, 198, 'bag8.jpg'),
(61, 7, 'mochila9', 2, '9', 'amarilla', '199', 199, 200, 199, 3, 199, 'bag9.jpg'),
(62, 7, 'mochila10', 2, '10', 'negro', '190', 190, 191, 190, 3, 190, 'bag10.jpg'),
(63, 6, 'fiti1', 4, '1', 'negro', '201', 201, 202, 196, 3, 201, 'fiti1.jpg'),
(64, 6, 'fiti2', 2, '2', 'verde', '202', 202, 203, 201, 3, 202, 'fiti2.jpg'),
(65, 6, 'fiti3', 3, '3', 'rojo', '203', 203, 204, 203, 3, 203, 'fiti3.jpg'),
(66, 6, 'fiti4', 5, '4', 'verde', '204', 204, 205, 198, 3, 204, 'fiti4.jpg'),
(67, 6, 'fiti5', 6, '5', 'rojo', '205', 205, 206, 205, 3, 205, 'fiti5.jpg'),
(68, 6, 'fiti6', 2, '6', 'azul', '206', 206, 207, 201, 3, 206, 'fiti6.jpg'),
(69, 6, 'fiti7', 5, '7', 'negro', '207', 207, 208, 203, 3, 207, 'fiti7.jpg'),
(70, 6, 'fiti8', 6, '8', 'verde', '208', 208, 209, 208, 3, 208, 'fiti8.jpg'),
(71, 6, 'fiti9', 5, '9', 'café', '209', 209, 300, 209, 3, 209, 'fiti9.jpg'),
(72, 6, 'fiti10', 4, '10', 'rosa', '200', 200, 201, 200, 3, 200, 'fiti10.jpg'),
(73, 9, 'pearcing1', 2, '1', 'surtido', '211', 211, 212, 211, 3, 211, 'pearcing1.jpg'),
(74, 9, 'pearcing2', 1, '2', 'plata', '212', 212, 213, 207, 3, 212, 'pearcing2.jpg'),
(75, 9, 'pearcing3', 3, '3', 'surtido', '213', 213, 214, 212, 3, 213, 'pearcing3.jpg'),
(76, 9, 'pearcing4', 3, '4', 'surtiso', '214', 214, 215, 214, 3, 214, 'pearcing4.jpg'),
(77, 9, 'pearcing4', 3, '4', 'surtiso', '214', 214, 215, 214, 3, 214, 'pearcing4.jpg'),
(78, 9, 'pearcing4', 3, '4', 'surtisod', '214', 214, 215, 214, 3, 214, 'pearcing4.jpg'),
(79, 9, 'pearcing5', 4, '5', 'metalico', '215', 215, 216, 215, 3, 215, 'pearcing5.jpg'),
(80, 9, 'pearcing6', 5, '6', 'surtido', '216', 216, 217, 216, 3, 216, 'pearcing6.jpg'),
(81, 9, 'pearcing7', 6, '7', 'surtido', '217', 217, 218, 217, 3, 217, 'pearcing7.jpg'),
(82, 9, 'pearcing8', 6, '8', 'surtido', '218', 218, 219, 218, 3, 218, 'pearcing8.jpg'),
(83, 9, 'pearcing9', 7, '9', 'surtido', '219', 219, 220, 219, 3, 219, 'pearcing9.jpg'),
(84, 9, 'pearcing10', 1, '10', 'plateado', '210', 210, 211, 154, 3, 210, 'pearcing10.jpg'),
(85, 10, 'tenis1', 2, '1', 'negro', '221', 221, 222, 221, 3, 221, 'tennis1.jpg'),
(86, 10, 'tenis2', 3, '2', 'negro', '222', 222, 223, 178, 3, 222, 'tennis2.jpg'),
(87, 10, 'tenis3', 4, '3', 'negro', '223', 223, 224, 223, 3, 223, 'tennis3.jpg'),
(88, 10, 'tenis4', 5, '4', 'azul', '224', 224, 225, 223, 3, 224, 'tennis4.jpg'),
(89, 10, 'tenis5', 6, '5', 'negro  blanco', '225', 225, 226, 225, 3, 225, 'tennis5.jpg'),
(90, 10, 'tenis6', 7, '6', 'gris', '226', 226, 227, 226, 3, 226, 'tennis6.jpg'),
(91, 10, 'tenis7', 1, '7', 'azul', '227', 227, 228, 227, 3, 227, 'tennis7.jpg'),
(92, 10, 'tenis8', 2, '8', 'azul', '228', 228, 229, 228, 3, 228, 'tennis8.jpg'),
(93, 10, 'tenis9', 2, '9', 'verde amarillo rojo', '2229', 229, 230, 229, 3, 229, 'tennis9.jpg'),
(94, 10, 'tenis10', 5, '10', 'rojo', '220', 220, 221, 220, 3, 220, 'tennis10.jpg'),
(95, 8, 'toy1', 4, '1', 'multi', '231', 231, 232, 231, 3, 231, 'toy1.jpg'),
(96, 8, 'toy2', 2, '2', 'multi', '232', 232, 233, 232, 3, 232, 'toy2.jpg'),
(97, 8, 'toy3', 5, '3', 'multi', '233', 233, 234, 227, 3, 233, 'toy3.jpg'),
(98, 8, 'toy4', 6, '4', 'multi', '234', 234, 235, 221, 3, 234, 'toy4.jpg'),
(99, 8, 'toy5', 6, '5', 'multi', '235', 235, 236, 234, 3, 235, 'toy5.jpg'),
(100, 8, 'toy6', 7, '6', 'multi', '236', 236, 237, 236, 3, 236, 'toy6.jpg'),
(101, 8, 'toy7', 1, '7', 'multi', '237', 237, 238, 237, 3, 237, 'toy7.jpg'),
(102, 8, 'toy8', 2, '8', 'multi', '238', 238, 239, 238, 3, 238, 'toy8.jpg'),
(103, 8, 'toy9', 3, '9', 'multi', '239', 239, 240, 239, 3, 239, 'toy9.jpg'),
(104, 8, 'toy10', 5, '10', 'multi', '230', 230, 231, 230, 3, 230, 'toy10.jpg'),
(105, 1, 'prueba', 1, '1112', 'color', 'model', 12, 121, 21, 21, 123421123, 'Captura de pantalla (5).png'),
(106, 1, 'prueba12', 1, '31', 'color', 'model', 12, 121, 31, 0, 1234, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `proveedor_id` int(11) NOT NULL,
  `ruc` varchar(255) DEFAULT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `descripcion_rubro` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`proveedor_id`, `ruc`, `razon_social`, `direccion`, `descripcion_rubro`, `email`, `telefono`) VALUES
(1, '1saf6d416', 'barcelon', 'calzada porfirio', 'venta de ropa', 'joseramirez.jr2303@gmail.com', 1423647),
(2, 'fadasf8686d', 'ninaice', 'adion', 'vanta de calzado', 'joseramirez.jr2303@gmail.com', 65165156);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `level` int(2) NOT NULL,
  `pwd` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`user_id`, `name`, `level`, `pwd`) VALUES
(1, 'jose', 1, '662eaa47199461d01a623884080934ab');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `venta_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `fecha_venta` date DEFAULT NULL,
  `sub_total` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `paga_con` float DEFAULT NULL,
  `cambio` float DEFAULT NULL,
  `tipo_pago` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`venta_id`, `empleado_id`, `fecha_venta`, `sub_total`, `total`, `paga_con`, `cambio`, `tipo_pago`) VALUES
(3, 1, '2017-01-28', 200, 200, 200, 200, NULL),
(5, 2, '2017-01-28', 120, 120, 500, 380, NULL),
(6, 3, '2017-03-01', 7856, 7856, 10000, 2144, NULL),
(7, 4, '2017-03-01', 1103, 1103, 1500, 397, NULL),
(8, 5, '2017-03-01', 17488, 17488, 20000, 2512, NULL),
(9, 6, '2017-03-01', 1064.6, 1064.6, 1111, 46.4, NULL),
(10, 7, '2017-03-01', 944.1, 944.1, 945, 0.9, NULL),
(11, 8, '2017-03-01', 2768.2, 2768.2, 3000, 231.8, NULL),
(12, 9, '2017-03-01', 18965.9, 18965.9, 20000, 1034.08, NULL),
(13, 10, '2017-03-01', 2257.4, 2257.4, 2257.4, 0, NULL),
(14, 1, '2017-03-01', 5097.48, 5097.48, 5100, 2.52, NULL),
(15, 3, '2017-03-01', 2086, 2086, 3000, 914, NULL),
(16, 5, '2017-03-01', -460.5, -460.5, -60, 400.5, NULL),
(17, 7, '2017-03-01', 192, 192, 500, 308, NULL),
(18, 9, '2017-03-01', 3276, 3276, 3500, 224, NULL),
(19, 1, '2017-03-01', 12369.5, 12369.5, 20000, 7630.55, NULL),
(20, 3, '2017-03-01', 2204, 2204, 3333, 1129, NULL),
(21, 5, '2017-03-01', 538.36, 538.36, 600, 61.64, NULL),
(22, 7, '2017-03-01', 495.04, 495.04, 500, 4.96, NULL),
(23, 1, '2017-03-01', 498, 498, 600, 102, NULL),
(24, 3, '2017-03-01', 9812, 9812, 1000, -8812, NULL),
(25, 1, '2017-03-01', 2122, 2122, 3000, 878, NULL),
(26, 1, '2017-03-01', 580, 580, 600, 20, NULL),
(27, 1, '2017-03-01', 235, 235, 600, 365, NULL),
(28, 1, '2017-03-01', 825, 825, 1000, 175, NULL),
(29, 10, '2017-12-27', 200, 200, 2, -198, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_detalle`
--

CREATE TABLE `venta_detalle` (
  `venta_detalle_id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `descuento` int(11) DEFAULT NULL,
  `importe` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta_detalle`
--

INSERT INTO `venta_detalle` (`venta_detalle_id`, `venta_id`, `producto_id`, `cantidad`, `descuento`, `importe`) VALUES
(3, 3, 1, 1, 0, 200),
(5, 5, 2, 1, 0, 120),
(6, 6, 69, 4, 0, 832),
(7, 6, 54, 23, 0, 4439),
(8, 6, 98, 11, 0, 2585),
(9, 7, 5, 4, 0, 456),
(10, 7, 11, 3, 0, 375),
(11, 7, 17, 2, 0, 272),
(12, 8, 33, 5, 0, 810),
(13, 8, 44, 6, 0, 1038),
(14, 8, 50, 85, 0, 15640),
(15, 9, 5, 1, 0, 114),
(16, 9, 4, 4, 0, 452),
(17, 9, 3, 4, 0, 448),
(18, 9, 6, 1, 56, 50.6),
(19, 10, 8, 3, 0, 366),
(20, 10, 9, 5, 6, 578.1),
(21, 11, 13, 5, 0, 660),
(22, 11, 17, 5, 6, 639.2),
(23, 11, 15, 6, 0, 804),
(24, 11, 14, 5, 0, 665),
(25, 12, 23, 11, 34, 1030.92),
(26, 12, 24, 65, 0, 9295),
(27, 12, 25, 55, 0, 7920),
(28, 12, 25, 5, 0, 720),
(29, 13, 28, 6, 5, 866.4),
(30, 13, 29, 3, 0, 459),
(31, 13, 31, 4, 0, 620),
(32, 13, 32, 5, 60, 312),
(33, 14, 33, 22, 0, 3564),
(34, 14, 34, 2, 0, 326),
(35, 14, 35, 3, 56, 216.48),
(36, 14, 36, 5, 0, 825),
(37, 14, 37, 1, 0, 166),
(38, 15, 43, 2, 0, 344),
(39, 15, 45, 5, 0, 870),
(40, 15, 45, 3, 0, 522),
(41, 15, 46, 2, 0, 350),
(42, 16, 48, 3, 0, 546),
(43, 16, 49, 55, 110, -1006.5),
(44, 17, 53, 1, 0, 192),
(45, 18, 63, 4, 0, 808),
(46, 18, 64, 1, 0, 203),
(47, 18, 66, 6, 0, 1230),
(48, 18, 68, 5, 0, 1035),
(49, 19, 84, 56, 0, 11816),
(50, 19, 74, 5, 67, 351.45),
(51, 19, 63, 1, 0, 202),
(52, 20, 4, 5, 0, 565),
(53, 20, 98, 1, 0, 235),
(54, 20, 97, 6, 0, 1404),
(55, 21, 99, 1, 0, 236),
(56, 21, 3, 1, 222, -136.64),
(57, 21, 88, 1, 0, 225),
(58, 21, 75, 1, 0, 214),
(59, 22, 10, 1, 4, 119.04),
(60, 22, 49, 1, 0, 183),
(61, 22, 54, 1, 0, 193),
(62, 23, 16, 0, 0, 0),
(63, 23, 10, 3, 0, 372),
(64, 23, 12, 1, 0, 126),
(65, 24, 86, 44, 0, 9812),
(66, 25, 55, 5, 0, 970),
(67, 25, 34, 6, 0, 978),
(68, 25, 45, 1, 0, 174),
(69, 26, 26, 4, 0, 580),
(70, 27, 98, 1, 0, 235),
(71, 28, 19, 5, 0, 690),
(72, 28, 16, 1, 0, 135),
(73, 29, 1, 1, 0, 200);

--
-- Disparadores `venta_detalle`
--
DELIMITER $$
CREATE TRIGGER `venta_producto_restar` AFTER INSERT ON `venta_detalle` FOR EACH ROW UPDATE producto
SET
existencia = existencia - NEW.cantidad
WHERE 
producto_id = NEW.producto_id
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indices de la tabla `comision`
--
ALTER TABLE `comision`
  ADD PRIMARY KEY (`comision_id`),
  ADD KEY `FKcomision_empleado` (`empleado_id`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`compra_id`),
  ADD KEY `FKcompra_proveedor` (`proveedor_id`);

--
-- Indices de la tabla `compra_detalle`
--
ALTER TABLE `compra_detalle`
  ADD PRIMARY KEY (`compra_detalle_id`),
  ADD KEY `FKcompra_compra` (`compra_id`),
  ADD KEY `FKcompra_producto` (`producto_id`);

--
-- Indices de la tabla `correo`
--
ALTER TABLE `correo`
  ADD PRIMARY KEY (`correo_id`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`empleado_id`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`marca_id`);

--
-- Indices de la tabla `pago_empleado`
--
ALTER TABLE `pago_empleado`
  ADD PRIMARY KEY (`pago_empleado_id`),
  ADD KEY `FKpago_empleado_empleado` (`empleado_id`);

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`prestamo_id`),
  ADD KEY `FKprestamo_empleado` (`empleado_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `FKproducto_categoria` (`categoria_id`),
  ADD KEY `FKproducto_marca` (`marca_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`proveedor_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `name` (`name`,`pwd`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`venta_id`),
  ADD KEY `FKventa_empleado` (`empleado_id`);

--
-- Indices de la tabla `venta_detalle`
--
ALTER TABLE `venta_detalle`
  ADD PRIMARY KEY (`venta_detalle_id`),
  ADD KEY `FKventa_detalle_venta` (`venta_id`),
  ADD KEY `FKventa_detalle_producto` (`producto_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `categoria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `comision`
--
ALTER TABLE `comision`
  MODIFY `comision_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `compra_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `compra_detalle`
--
ALTER TABLE `compra_detalle`
  MODIFY `compra_detalle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `correo`
--
ALTER TABLE `correo`
  MODIFY `correo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `empleado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `marca_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pago_empleado`
--
ALTER TABLE `pago_empleado`
  MODIFY `pago_empleado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `prestamo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `producto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `proveedor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `venta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `venta_detalle`
--
ALTER TABLE `venta_detalle`
  MODIFY `venta_detalle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comision`
--
ALTER TABLE `comision`
  ADD CONSTRAINT `FKcomision_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`empleado_id`);

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `FKcompra_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`proveedor_id`);

--
-- Filtros para la tabla `compra_detalle`
--
ALTER TABLE `compra_detalle`
  ADD CONSTRAINT `FKcompra_compra` FOREIGN KEY (`compra_id`) REFERENCES `compra` (`compra_id`),
  ADD CONSTRAINT `FKcompra_producto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`producto_id`);

--
-- Filtros para la tabla `pago_empleado`
--
ALTER TABLE `pago_empleado`
  ADD CONSTRAINT `FKpago_empleado_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`empleado_id`);

--
-- Filtros para la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `FKprestamo_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`empleado_id`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `FKproducto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`),
  ADD CONSTRAINT `FKproducto_marca` FOREIGN KEY (`marca_id`) REFERENCES `marca` (`marca_id`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `FKventa_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`empleado_id`);

--
-- Filtros para la tabla `venta_detalle`
--
ALTER TABLE `venta_detalle`
  ADD CONSTRAINT `FKventa_detalle_producto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`producto_id`),
  ADD CONSTRAINT `FKventa_detalle_venta` FOREIGN KEY (`venta_id`) REFERENCES `venta` (`venta_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
