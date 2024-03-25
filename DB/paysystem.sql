-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-03-2024 a las 15:44:35
-- Versión del servidor: 10.1.32-MariaDB
-- Versión de PHP: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `paysystem`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `branch`
--

CREATE TABLE `branch` (
  `id` int(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `detail` text NOT NULL,
  `delete_status` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `branch`
--

INSERT INTO `branch` (`id`, `branch`, `address`, `detail`, `delete_status`) VALUES
(1, 'Banco Falabella', 'Calle 54 N 35 23', 'Excelente banco no cobra por cuota de manejo ni por sacar el dinero de cajeros automáticos.', '1'),
(2, 'Banco Colpatria', 'Calle 88 N 123 - 12', 'Excelente bancos, tampoco cobra cuota de manejo, ni por sacar dinero de sus cajero automáticos.', '1'),
(6, 'Bancolombia', 'Calle 34 N 18 - 14', 'Es el banco que tiene más personas en Colombia, tiene cajeros y sucursales en todas partes, pero tiene cargos en sus cuentas, por movimientos y por cuota de manejo.', '1'),
(7, 'Sacaba', 'calle 1', '', '0'),
(8, 'Quillacollo', 'Calle 1', '', '0'),
(9, 'Tiquipaya', 'Calle 1', '', '0'),
(10, 'Cochabamba', 'calle 1', '', '1'),
(11, 'Secretariado Ejecutivo', 'Calle 1', '', '1'),
(12, 'Cochabamba', 'guadalquivir', '', '0'),
(13, 'Cliza', 'cliza', '', '0'),
(14, 'Punata', 'calle bolivar', 'Punata', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `career`
--

CREATE TABLE `career` (
  `id` int(11) NOT NULL,
  `career` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `address` text COLLATE latin1_spanish_ci NOT NULL,
  `detail` text COLLATE latin1_spanish_ci NOT NULL,
  `delete_status` enum('0','1') COLLATE latin1_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `career`
--

INSERT INTO `career` (`id`, `career`, `address`, `detail`, `delete_status`) VALUES
(1, 'Adm. de empresas', 'calle totora', '', '0'),
(2, 'Estilismo 430', 'calle s/n\r\n', '', '0'),
(3, 'Computer Science', 'Sunflower Lane', '', '0'),
(4, 'Medicine', 'Maple Avenue', '', '0'),
(5, 'Engineering ', 'Riverbend Terrace', '', '0'),
(6, 'Business Administration', 'Pinecrest Road', '', '0'),
(7, 'Psychology', 'Meadowbrook Drive\r\n', '', '1'),
(8, 'Biology', 'Lakeside Boulevard\r\n', '', '0'),
(9, 'Economics', 'Oakwood Court\r\n', '', '0'),
(10, 'Nursing', 'Willow Way\r\n', '', '1'),
(11, 'Education', 'Cedar Lane\r\n', '', '1'),
(12, 'Architecture', 'Elm Street\r\n', '', '0'),
(13, 'neurociencia', 's/n', '', '0'),
(14, 'Confecciones', 'c1', '', '0'),
(15, 'Corte confeccion', 'tres sucursales', ' estsnajhda m.kfhgsaksfksfsv jgadsDah', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fees_transaction`
--

CREATE TABLE `fees_transaction` (
  `id` int(255) NOT NULL,
  `stdid` varchar(255) NOT NULL,
  `code_transaction` int(11) NOT NULL,
  `paid` int(255) NOT NULL,
  `submitdate` datetime NOT NULL,
  `transcation_remark` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `fees_transaction`
--

INSERT INTO `fees_transaction` (`id`, `stdid`, `code_transaction`, `paid`, `submitdate`, `transcation_remark`) VALUES
(11, '10', 0, 100000, '2020-08-20 00:00:00', 'Estudiante Generado para posible intercambio con colegio Alemán'),
(12, '10', 0, 100000, '2020-08-20 00:00:00', 'Pagado por adelantado'),
(13, '10', 0, 100000, '2020-08-20 00:00:00', 'Pago adelantado'),
(14, '11', 0, 0, '2020-08-21 00:00:00', 'En la semana que viene el estudiante promete realizar el primer pago.'),
(15, '11', 0, 120000, '2020-08-21 00:00:00', 'Pudo realizar el pago antes de lo acordado.'),
(16, '12', 0, 300, '2024-02-19 00:00:00', ''),
(17, '13', 0, 300, '2024-02-19 00:00:00', 'Pago primera mensualidad'),
(18, '14', 0, 0, '2024-02-19 00:00:00', 'inscripcion'),
(19, '15', 0, 300, '2024-02-19 00:00:00', 'Pago Primera mensualidad'),
(20, '16', 0, 300, '2024-02-19 00:00:00', 'Pago matricula'),
(21, '13', 0, 200, '2024-02-19 00:00:00', 'Pago matricula'),
(22, '17', 0, 300, '2024-02-20 00:00:00', 'Primer pago de carrera'),
(23, '17', 0, 300, '2024-02-20 00:00:00', 'Segunda mesualidad'),
(24, '18', 0, 300, '2024-02-21 00:00:00', 'Primera mensualidad'),
(25, '18', 0, 300, '2024-03-01 00:00:00', 'Segunda mensualidad'),
(26, '19', 0, 200, '2024-02-27 00:00:00', 'primera mensualidad'),
(27, '19', 0, 200, '2024-02-29 00:00:00', 'segunda mensualidad'),
(28, '13', 0, 300, '2024-03-06 00:00:00', 'Tercera Mensualidad'),
(29, '17', 0, 100, '2024-03-06 00:00:00', 'cuarto pago'),
(30, '17', 0, 100, '2024-03-07 00:00:00', 'quinto pago'),
(31, '17', 0, 100, '2024-03-07 00:00:00', 'sexto pago'),
(32, '17', 0, 100, '2024-03-08 00:00:00', 'septimo pago'),
(33, '17', 0, 100, '2024-03-11 00:00:00', 'octavo pago'),
(34, '17', 0, 100, '2024-03-12 00:00:00', 'noveno pago'),
(35, '17', 0, 100, '2024-03-14 00:00:00', 'decimo pago'),
(36, '17', 0, 100, '2024-03-20 00:00:00', 'undecimo pago'),
(37, '17', 0, 100, '2024-03-21 00:00:00', 'doceavo pago'),
(38, '17', 0, 100, '2024-03-25 00:00:00', 'treceavo pago'),
(39, '17', 0, 100, '2024-03-28 00:00:00', 'catorceavo pago'),
(40, '17', 0, 100, '2024-03-29 00:00:00', 'quinceavo pago'),
(41, '17', 0, 100, '2024-04-01 00:00:00', 'dieciseisavo pago'),
(42, '17', 0, 100, '2024-04-03 00:00:00', 'decimoseptimo pago'),
(43, '20', 0, 100, '2024-03-06 00:00:00', 'Primer pago'),
(44, '20', 0, 100, '2024-03-07 00:00:00', 'segundo pago\r\n'),
(45, '20', 0, 100, '2024-03-08 00:00:00', '3 pago\r\n'),
(46, '20', 0, 100, '2024-03-09 00:00:00', '4 pago\r\n'),
(47, '20', 0, 100, '2024-03-10 00:00:00', '5 pago\r\n'),
(48, '20', 0, 100, '2024-03-11 00:00:00', '6 pago\r\n'),
(49, '20', 0, 100, '2024-03-12 00:00:00', '7 pago\r\n'),
(50, '20', 0, 100, '2024-03-12 00:00:00', '8 pago\r\n'),
(51, '20', 0, 100, '2024-03-14 00:00:00', '9 pago\r\n'),
(52, '20', 0, 100, '2024-03-15 00:00:00', '10 pago\r\n'),
(53, '20', 0, 100, '2024-03-16 00:00:00', '11 pago\r\n'),
(54, '20', 0, 100, '2024-03-17 00:00:00', '12 pago\r\n'),
(55, '20', 0, 100, '2024-03-18 00:00:00', '13 pago\r\n'),
(56, '20', 0, 100, '2024-03-19 00:00:00', '14 pago\r\n'),
(57, '20', 0, 100, '2024-03-20 00:00:00', '15 pago\r\n'),
(58, '20', 0, 100, '2024-03-22 00:00:00', '16 pago\r\n'),
(59, '20', 0, 100, '2024-03-23 00:00:00', '17 pago\r\n'),
(60, '20', 0, 100, '2024-03-24 00:00:00', '18 pago\r\n'),
(61, '20', 0, 100, '2024-03-25 00:00:00', '19 pago\r\n'),
(62, '20', 0, 100, '2024-03-27 00:00:00', '20 pago\r\n'),
(63, '20', 0, 100, '2024-03-29 00:00:00', '21 pago\r\n'),
(64, '20', 0, 100, '2024-04-01 00:00:00', '22 pago\r\n'),
(65, '20', 0, 100, '2024-04-02 00:00:00', '23 pago\r\n'),
(66, '20', 0, 100, '2024-04-03 00:00:00', '24 pago\r\n'),
(67, '20', 0, 100, '2024-04-04 00:00:00', '25 pago\r\n'),
(68, '20', 0, 100, '2024-04-05 00:00:00', '26 pago\r\n'),
(69, '20', 0, 100, '2024-04-05 00:00:00', '27 pago\r\n'),
(70, '20', 0, 100, '2024-04-06 00:00:00', '28 pago\r\n'),
(71, '20', 0, 100, '2024-04-07 00:00:00', '29 pago\r\n'),
(72, '20', 0, 100, '2024-04-08 00:00:00', '30 pago\r\n'),
(73, '20', 0, 100, '2024-04-10 00:00:00', '31 pago\r\n'),
(74, '13', 0, 100, '2024-03-06 00:00:00', '1ra utoa'),
(75, '13', 0, 100, '2024-03-21 00:00:00', ''),
(76, '13', 0, 100, '2024-03-13 00:00:00', ''),
(77, '15', 0, 100, '2024-03-05 00:00:00', 'sdfsdf'),
(78, '15', 0, 100, '2024-03-14 00:00:00', 'fff'),
(79, '15', 0, 100, '2024-03-21 00:00:00', 'kjbljkb'),
(80, '19', 0, 100, '2024-03-27 00:00:00', 'safdsdf'),
(81, '19', 0, 100, '2024-03-06 00:00:00', 'adsfas'),
(82, '19', 0, 100, '2024-03-27 00:00:00', 'sdf'),
(83, '19', 0, 100, '2024-03-20 00:00:00', 'asdf'),
(84, '18', 0, 450, '2024-03-27 00:00:00', 'sdf'),
(85, '18', 0, 200, '2024-03-28 00:00:00', 'asdf'),
(86, '20', 0, 900, '2024-03-29 00:00:00', 'un pago realizado'),
(87, '19', 0, 200, '2024-03-27 00:00:00', 'asdf'),
(88, '19', 0, 1000, '2024-03-14 00:00:00', 'pago final'),
(89, '18', 0, 250, '2024-03-14 00:00:00', 'cancelado\r\n'),
(90, '21', 0, 200, '2024-03-14 00:00:00', 'Primera Cuota'),
(91, '21', 0, 200, '2024-03-15 00:00:00', '2da Cuota'),
(92, '22', 0, 100, '2024-03-14 00:00:00', 'Matricula'),
(93, '22', 0, 100, '2024-03-14 00:00:00', 'Primera cuota'),
(94, '15', 0, 100, '2024-03-20 00:00:00', 'iyhh'),
(95, '21', 0, 200, '2024-03-26 00:00:00', 'df'),
(96, '21', 0, 400, '2024-03-28 00:00:00', 'otra cuota'),
(97, '21', 0, 100, '2024-03-22 00:00:00', 'oytra cuota'),
(98, '21', 0, 100, '2024-03-27 00:00:00', 'sdf'),
(99, '21', 0, 200, '2024-03-29 00:00:00', 'asdf'),
(100, '21', 0, 600, '2024-03-27 00:00:00', 'jhvbjh'),
(101, '21', 0, 200, '2024-03-28 00:00:00', 'dsfsdf'),
(102, '23', 0, 200, '2024-03-18 00:00:00', 'primera cuota'),
(103, '24', 0, 0, '2024-03-22 00:00:00', ''),
(104, '25', 0, 100, '2024-03-22 00:00:00', 'matricula');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student`
--

CREATE TABLE `student` (
  `id` int(255) NOT NULL,
  `ci` varchar(10) NOT NULL,
  `emailid` varchar(255) NOT NULL,
  `sname` varchar(255) NOT NULL,
  `joindate` datetime NOT NULL,
  `about` text NOT NULL,
  `contact` varchar(255) NOT NULL,
  `fees` int(255) NOT NULL,
  `career` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `balance` int(255) NOT NULL,
  `delete_status` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `student`
--

INSERT INTO `student` (`id`, `ci`, `emailid`, `sname`, `joindate`, `about`, `contact`, `fees`, `career`, `branch`, `balance`, `delete_status`) VALUES
(10, '', 'rpelaez@cweb.com', 'Roberto Pelaez', '2020-08-20 00:00:00', 'Excelente estudiante recomendado', '3162345871', 1000000, '2', '', 700000, '1'),
(11, '', 'fmendoza@cweb.com', 'Francisco Mendoza', '2020-08-21 00:00:00', 'Estudiante es migrado de otro colegio, noticias excelentes.', '3154678143', 1200000, '1', '', 1080000, '1'),
(12, '', '', 'David ', '2024-02-19 00:00:00', '', '75266992', 1500, '2', '', 1200, '1'),
(13, '8793900', 'angetgg@hotmail.com', 'Kevin Titichoca Veizaga', '2024-02-19 00:00:00', 'Carrera programaciÃ³n', '75266992', 1500, '7', '10', 400, '0'),
(14, '', 'anwwgetgg@hotmail.com', 'Diego Ardaya', '2024-02-19 00:00:00', 'Estudiante DiseÃ±o grafico', '69345899', 1200, '9', '', 1200, '1'),
(15, '591966', 'anwwgetgg@hotmail.com', 'Diego Ardaya', '2024-02-19 00:00:00', 'Estudiante  ', '69345899', 1500, '9', '8', 800, '0'),
(16, '789526', 'anwwgenfetgg@hotmail.com', 'David Calizaya', '2024-02-19 00:00:00', 'Estudiante enfermeria', '65897889', 1500, '8', '9', 1200, '0'),
(17, '', 'anwwggdfgtetgg@hotmail.com', 'Marco aguilar', '2024-02-20 00:00:00', 'Estudoiante de periodismo', '75655551', 2000, '10', '', 0, '1'),
(18, '456189', 'anwwgetgjjg@hotmail.com', 'Julian Rojas', '2024-02-21 00:00:00', 'Estudiante de la carrera de Se. Ejec.', '78556566', 1500, '11', '', 0, '1'),
(19, '565265', 'sd@mail.com', 'jorge perez', '2024-02-27 00:00:00', 'asdf', '12345779', 15000, '8', '8', 13000, '0'),
(20, '51655656', '', 'marco aguilar', '2024-03-06 00:00:00', '', '7516895', 80000, '9', '10', 76000, '0'),
(21, '49189', 'elmeregildo.smith@gmail.com', 'Elmeregildo Smith', '2024-03-14 00:00:00', '', '79841898', 15000, '3', '10', 12800, '0'),
(22, '313152', 'pruebas@gmail.com', 'Lucia Mendez', '2024-03-14 00:00:00', 'pruebas', '72252966', 300, '12', '8', 100, '0'),
(23, '562548', '', 'Rick Hunter', '2024-03-18 00:00:00', '', '75651659', 12200, '8', '12', 12000, '0'),
(24, '5906950', 'estudiante@hotmail.com', 'Alex Rojas', '2024-03-22 00:00:00', 'kjfdshkfdslfsfh, jgdskagaskjdas,kjdgadshaj', '72252933', 1200, '15', '8', 1200, '0'),
(25, '4417190', 'anwwgetgg@hotmail.com', 'saul rojas', '2024-03-22 00:00:00', 'fsdf', '4646464', 1000, '12', '14', 900, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `emailid` varchar(255) NOT NULL,
  `lastlogin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `name`, `emailid`, `lastlogin`) VALUES
(1, 'configuroweb', '4b67deeb9aba04a5b54632ad19934f26', 'ConfiguroWeb', 'hola@cweb.com', '0000-00-00 00:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `career`
--
ALTER TABLE `career`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `fees_transaction`
--
ALTER TABLE `fees_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `branch`
--
ALTER TABLE `branch`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `career`
--
ALTER TABLE `career`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `fees_transaction`
--
ALTER TABLE `fees_transaction`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT de la tabla `student`
--
ALTER TABLE `student`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
