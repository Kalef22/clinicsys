-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-04-2024 a las 13:18:39
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `aferesis`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario`
--

CREATE TABLE `calendario` (
  `id_dia` int(11) NOT NULL,
  `tipo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

CREATE TABLE `cita` (
  `id_cita` int(11) NOT NULL,
  `id_donante` int(11) DEFAULT NULL,
  `id_dia` int(11) DEFAULT NULL,
  `id_maquina` int(11) DEFAULT NULL,
  `id_horario` int(11) DEFAULT NULL,
  `apto` varchar(3) DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `decision`
--

CREATE TABLE `decision` (
  `id_decision` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `respuesta` varchar(3) DEFAULT NULL,
  `id_cita` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donante`
--

CREATE TABLE `donante` (
  `id_donante` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido1` varchar(255) DEFAULT NULL,
  `apellido2` varchar(255) DEFAULT NULL,
  `nhc` varchar(20) DEFAULT NULL,
  `telef1` varchar(100) DEFAULT NULL,
  `telef2` varchar(100) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `ultima_donacion` date DEFAULT NULL,
  `recordatorio` varchar(255) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `llamar` varchar(255) NOT NULL DEFAULT 'No',
  `cipa` varchar(255) DEFAULT NULL,
  `acepto_comunicacion` varchar(3) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `citable` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `donante`
--

INSERT INTO `donante` (`id_donante`, `nombre`, `apellido1`, `apellido2`, `nhc`, `telef1`, `telef2`, `dni`, `ultima_donacion`, `recordatorio`, `observaciones`, `llamar`, `cipa`, `acepto_comunicacion`, `fecha_nacimiento`, `citable`) VALUES
(21, 'RUFINO', 'ARENAL', 'HERRERO', '7', '628772007', '', '', '2023-12-01', '2024-01-01', NULL, 'Sí', NULL, NULL, NULL, NULL),
(22, 'MARIA', 'LOPEZ', 'CARRETO', '15', '', '', '', '2023-12-01', '2024-02-01', NULL, 'Sí', NULL, NULL, NULL, NULL),
(23, 'RAMON', 'MUÑOZ', 'FERNANDEZ', '10156598', '616472153', '606414210', '', '2023-12-02', '2024-01-02', NULL, 'No', NULL, NULL, NULL, NULL),
(24, 'CARLOS', 'CANO', 'BENITEZ', '10156592', '678720672', '', '48921076F', '2023-12-04', '2024-01-04', '', 'No', NULL, NULL, NULL, NULL),
(25, 'DANIEL', 'DE RADA', 'SANCHEZ', '10156545', '695109415', '642113307', '48161015G', '2023-12-04', '2024-01-04', NULL, 'No', NULL, NULL, NULL, NULL),
(26, 'CARLOS', 'RUIZ', 'FERNANDEZ', '10176598', '605851037', '605851030', '50978299H', '2023-12-01', '2024-01-01', NULL, 'Sí', NULL, NULL, NULL, NULL),
(27, 'ALBERTO', 'MARTINEZ', 'VELA', '10176590', '608091439', '', '70356961F', NULL, NULL, NULL, 'No', NULL, NULL, NULL, NULL),
(28, 'ANGEL', 'VIDAL', 'ROSADO', '10176593', '916837411', '648428525', '02491097J', NULL, NULL, NULL, 'No', NULL, NULL, NULL, NULL),
(29, 'JULIANA', 'DE LUCAS', 'LUCAS', '10176698', '949385336', '', '03054287W', NULL, NULL, NULL, 'No', NULL, NULL, NULL, NULL),
(30, 'ABDELOUAFI', 'AOULAD', 'EL ARBI', '10176696', '637165888', '', '', '2023-11-29', '2023-12-29', NULL, 'Sí', NULL, NULL, NULL, NULL),
(31, 'SOFIA ISABELLA', 'SALAZAR', 'ALARCON', '10176699', '658113537', '628061923', '', '2023-11-29', '2023-12-29', NULL, 'Sí', NULL, NULL, NULL, NULL),
(32, 'LAURA CARLOTA', 'ARIAS', 'HIDALGO', '10176597', '917157080', '639183604', '54023357Z', '2023-12-01', '2024-01-01', NULL, 'Sí', NULL, NULL, NULL, NULL),
(33, 'JOANNA', 'MAGINO', 'JEREZ', '10176594', '617001342', '', '71749538A', NULL, NULL, NULL, 'No', NULL, NULL, NULL, NULL),
(35, 'GABRIEL EDU', 'MATEO', 'SOLERA', '8', '', '', '', '2023-12-04', '2024-01-04', NULL, 'No', NULL, NULL, NULL, NULL),
(36, 'ENRIQUE', 'ROPERO', 'MONTERO', '5000', '0006997302', '', '', '2023-12-04', '2024-02-04', NULL, 'Sí', NULL, NULL, NULL, NULL),
(37, 'GREGORIO', 'SANCHEZ GARRIDO', 'MUÑOZ', '2000', '0007922125', '', '', '2023-12-04', '2024-02-04', NULL, 'Sí', NULL, NULL, NULL, NULL),
(38, 'MANUEL', 'ALONSO', 'PUENTE', '1', '11', '', '', NULL, NULL, NULL, 'No', NULL, NULL, NULL, NULL),
(39, 'ANSELMO', 'ASENJO', 'MESA', '33', '', '', '', NULL, NULL, NULL, 'No', NULL, NULL, NULL, NULL),
(40, 'LAGO', 'COUSO', 'NAKATSUKA', '10265974', '915421767', '602092084', '12456159A', NULL, NULL, NULL, 'No', NULL, NULL, NULL, NULL),
(41, 'ESTHER VIRGINIA', 'REYES', 'ROJAS', '10569874', '611121125', '', '', '2023-12-11', '2024-02-11', '', 'No', NULL, NULL, NULL, NULL),
(42, 'MIGUEL', 'MUÑOZ', 'BARBA', '10568465', '679315818', '655232627', '', NULL, NULL, NULL, 'No', NULL, NULL, NULL, NULL),
(44, 'ISAAC', 'GALAN', 'MARTIN', '21', '917963101', '', '05769035Z', NULL, NULL, NULL, 'No', NULL, NULL, NULL, NULL),
(45, 'PETRA', 'LUENGO', 'MOYA', '5', '669377104', '629120475', '05754694W', NULL, NULL, NULL, 'No', NULL, NULL, NULL, NULL),
(46, 'DAVID ALEJANDRO', 'BARROSO', 'CONTRERAS', NULL, '674550859', '', '55026262G', NULL, '2024-04-18', '', 'Si', '1988075422', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id_horario` int(11) NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `tiempo_cita` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `operacion` varchar(255) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maquina`
--

CREATE TABLE `maquina` (
  `id_maquina` int(11) NOT NULL,
  `descripcion_maquina` varchar(50) DEFAULT NULL,
  `activa` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `observaciones`
--

CREATE TABLE `observaciones` (
  `id_observacion` int(11) NOT NULL,
  `id_donante` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `observacion` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta`
--

CREATE TABLE `pregunta` (
  `id_pregunta` int(11) NOT NULL,
  `pregunta` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE `respuesta` (
  `id_respuesta` int(11) NOT NULL,
  `id_pregunta` int(11) DEFAULT NULL,
  `id_donante` int(11) DEFAULT NULL,
  `fecha_respuesta` timestamp NOT NULL DEFAULT current_timestamp(),
  `observacion` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `descripcion_rol` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido1` varchar(50) DEFAULT NULL,
  `apellido2` varchar(50) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calendario`
--
ALTER TABLE `calendario`
  ADD PRIMARY KEY (`id_dia`);

--
-- Indices de la tabla `cita`
--
ALTER TABLE `cita`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `id_donante` (`id_donante`),
  ADD KEY `id_dia` (`id_dia`),
  ADD KEY `id_maquina` (`id_maquina`),
  ADD KEY `id_horario` (`id_horario`);

--
-- Indices de la tabla `decision`
--
ALTER TABLE `decision`
  ADD PRIMARY KEY (`id_decision`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `decision_ibfk_1` (`id_cita`);

--
-- Indices de la tabla `donante`
--
ALTER TABLE `donante`
  ADD PRIMARY KEY (`id_donante`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id_horario`);

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `maquina`
--
ALTER TABLE `maquina`
  ADD PRIMARY KEY (`id_maquina`);

--
-- Indices de la tabla `observaciones`
--
ALTER TABLE `observaciones`
  ADD PRIMARY KEY (`id_observacion`),
  ADD KEY `id_donante` (`id_donante`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD PRIMARY KEY (`id_pregunta`);

--
-- Indices de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD PRIMARY KEY (`id_respuesta`),
  ADD KEY `id_pregunta` (`id_pregunta`),
  ADD KEY `id_donante` (`id_donante`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `calendario`
--
ALTER TABLE `calendario`
  MODIFY `id_dia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cita`
--
ALTER TABLE `cita`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `decision`
--
ALTER TABLE `decision`
  MODIFY `id_decision` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `donante`
--
ALTER TABLE `donante`
  MODIFY `id_donante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maquina`
--
ALTER TABLE `maquina`
  MODIFY `id_maquina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `observaciones`
--
ALTER TABLE `observaciones`
  MODIFY `id_observacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  MODIFY `id_respuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `cita_ibfk_1` FOREIGN KEY (`id_donante`) REFERENCES `donante` (`id_donante`),
  ADD CONSTRAINT `cita_ibfk_2` FOREIGN KEY (`id_dia`) REFERENCES `calendario` (`id_dia`),
  ADD CONSTRAINT `cita_ibfk_3` FOREIGN KEY (`id_maquina`) REFERENCES `maquina` (`id_maquina`),
  ADD CONSTRAINT `cita_ibfk_4` FOREIGN KEY (`id_horario`) REFERENCES `horarios` (`id_horario`);

--
-- Filtros para la tabla `decision`
--
ALTER TABLE `decision`
  ADD CONSTRAINT `decision_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `cita` (`id_cita`),
  ADD CONSTRAINT `fk_decision_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `observaciones`
--
ALTER TABLE `observaciones`
  ADD CONSTRAINT `observaciones_ibfk_1` FOREIGN KEY (`id_donante`) REFERENCES `donante` (`id_donante`),
  ADD CONSTRAINT `observaciones_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD CONSTRAINT `respuesta_ibfk_1` FOREIGN KEY (`id_pregunta`) REFERENCES `pregunta` (`id_pregunta`),
  ADD CONSTRAINT `respuesta_ibfk_2` FOREIGN KEY (`id_donante`) REFERENCES `donante` (`id_donante`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
