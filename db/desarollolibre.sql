-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-10-2025 a las 02:19:53
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
-- Base de datos: `prueba1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aerolinea`
--

CREATE TABLE `aerolinea` (
  `idAerolinea` int(11) NOT NULL,
  `nombreAerolinea` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `nit` int(15) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aerolinea`
--

INSERT INTO `aerolinea` (`idAerolinea`, `nombreAerolinea`, `email`, `nit`, `direccion`, `password`) VALUES
(1, 'Avianca', 'contacto@avianca.com', 800037991, 'Calle 26 #59-15, Bogotá', 'avianca123'),
(2, 'LATAM Airlines', 'info@latam.com', 830077422, 'Av. El Dorado #69-63, Bogotá', 'latam123'),
(3, 'Copa Airlines', 'servicio@copaair.com', 890001212, 'Cra 7 #71-52, Bogotá', 'copa123'),
(4, 'AviancaA', 'admin4@senaporc.com', 132, 'Calle 26 #59-15, Bogotá', '123'),
(5, 'AviancaA', 'vete2@gmail.com', 123, 'asd', '12344'),
(6, 'AviancaATE', 'r@gmail.com', 123, 'asd', '12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asientos`
--

CREATE TABLE `asientos` (
  `idAsiento` int(11) NOT NULL,
  `idVuelo` int(11) NOT NULL,
  `codigoAsiento` varchar(5) NOT NULL,
  `estado` enum('disponible','ocupado') DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aviones`
--

CREATE TABLE `aviones` (
  `idAvion` int(11) NOT NULL,
  `nombreAvion` varchar(70) NOT NULL,
  `idModeloA` int(11) NOT NULL,
  `idAerolinea` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aviones`
--

INSERT INTO `aviones` (`idAvion`, `nombreAvion`, `idModeloA`, `idAerolinea`) VALUES
(1, 'Avianca Express', 1, 1),
(2, 'LATAM Sky', 2, 2),
(3, 'Copa Dream', 3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidad`
--

CREATE TABLE `disponibilidad` (
  `idDisponibilidad` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `fechaRegreso` date DEFAULT NULL,
  `asiento` varchar(50) NOT NULL,
  `origen` varchar(50) NOT NULL,
  `destino` varchar(50) NOT NULL,
  `horaSalida` varchar(40) NOT NULL,
  `horaLlegada` varchar(40) NOT NULL,
  `idAvion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `disponibilidad`
--

INSERT INTO `disponibilidad` (`idDisponibilidad`, `fecha`, `fechaRegreso`, `asiento`, `origen`, `destino`, `horaSalida`, `horaLlegada`, `idAvion`) VALUES
(1, '2025-11-10', NULL, '1A', 'Bogotá', 'Medellín', '08:30', '09:40', 1),
(2, '2025-11-10', NULL, '2B', 'Medellín', 'Bogotá', '18:15', '19:20', 2),
(4, '2025-11-01', '2025-11-30', 'A2', 'Bogotá', 'Medellín', '13:00 p.m', '2:15 p.m', 2),
(5, '2025-11-15', NULL, 'B33', 'Pereira', 'Florencia', '5:30 p.m', '7:00 p.m', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modeloaviones`
--

CREATE TABLE `modeloaviones` (
  `idModeloA` int(11) NOT NULL,
  `modelo` int(60) NOT NULL,
  `capacidad` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modeloaviones`
--

INSERT INTO `modeloaviones` (`idModeloA`, `modelo`, `capacidad`) VALUES
(1, 320, 180),
(2, 737, 160),
(3, 787, 250),
(4, 220, 120),
(5, 122, 10),
(6, 122, 10),
(7, 122, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pasajeros`
--

CREATE TABLE `pasajeros` (
  `idPasajero` int(50) NOT NULL,
  `nombres` varchar(60) NOT NULL,
  `primerApellido` varchar(40) NOT NULL,
  `segundoApellido` varchar(40) NOT NULL,
  `fechNacimiento` date NOT NULL,
  `genero` varchar(40) NOT NULL,
  `tipoDocumento` varchar(12) NOT NULL,
  `documento` int(11) NOT NULL,
  `celular` bigint(20) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `idRol` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pasajeros`
--

INSERT INTO `pasajeros` (`idPasajero`, `nombres`, `primerApellido`, `segundoApellido`, `fechNacimiento`, `genero`, `tipoDocumento`, `documento`, `celular`, `email`, `password`, `idRol`) VALUES
(1, 'Carlos Andrés', 'Gómez', 'Pérez', '1995-03-12', 'Masculino', 'CC', 1025487963, 2147483647, 'carlos.gomez@mail.com', 'pass123', 1),
(2, 'María Fernanda', 'López', 'Ríos', '1998-07-22', 'Femenino', 'CC', 1004567891, 2147483647, 'maria.lopez@mail.com', 'maria456', 1),
(3, 'Juan Sebastián', 'Ramírez', 'Torres', '1987-10-05', 'Masculino', 'CC', 79865412, 2147483647, 'juan.ramirez@mail.com', 'juan789', 1),
(4, 'Ana Sofía', 'Martínez', 'Díaz', '2000-12-01', 'Femenino', 'TI', 104589623, 2147483647, 'ana.martinez@mail.com', 'ana123', 1),
(5, 'asd', 'asd', 'asd', '2025-10-22', 'asd', 'asd', 0, 0, 'admin4@senaporc.com', '123', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `idReserva` int(11) NOT NULL,
  `condicionInfante` int(3) NOT NULL,
  `iva` float NOT NULL,
  `descuento` float NOT NULL,
  `subtotal` float NOT NULL,
  `idDisponibilidad` int(11) NOT NULL,
  `idPasajeros` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `idRol` int(11) NOT NULL,
  `nombreRol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`idRol`, `nombreRol`) VALUES
(2, 'admin'),
(3, 'aerolinea'),
(1, 'pasajero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiquetes`
--

CREATE TABLE `tiquetes` (
  `idTiquete` int(11) NOT NULL,
  `idPasajero` int(11) DEFAULT NULL,
  `idVuelo` int(11) DEFAULT NULL,
  `asiento` varchar(5) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `fechaCompra` date DEFAULT NULL,
  `codigoReserva` varchar(30) DEFAULT NULL,
  `fecha` date NOT NULL,
  `totalPagar` float NOT NULL,
  `idReserva` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aerolinea`
--
ALTER TABLE `aerolinea`
  ADD PRIMARY KEY (`idAerolinea`);

--
-- Indices de la tabla `asientos`
--
ALTER TABLE `asientos`
  ADD PRIMARY KEY (`idAsiento`),
  ADD KEY `idVuelo` (`idVuelo`);

--
-- Indices de la tabla `aviones`
--
ALTER TABLE `aviones`
  ADD PRIMARY KEY (`idAvion`),
  ADD KEY `aviones_ibfk_1` (`idModeloA`),
  ADD KEY `aviones_ibfk_2` (`idAerolinea`);

--
-- Indices de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  ADD PRIMARY KEY (`idDisponibilidad`),
  ADD KEY `idAvion` (`idAvion`);

--
-- Indices de la tabla `modeloaviones`
--
ALTER TABLE `modeloaviones`
  ADD PRIMARY KEY (`idModeloA`);

--
-- Indices de la tabla `pasajeros`
--
ALTER TABLE `pasajeros`
  ADD PRIMARY KEY (`idPasajero`),
  ADD KEY `pasajeros_ibfk_1` (`idRol`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`idReserva`),
  ADD KEY `idDisponibilidad` (`idDisponibilidad`),
  ADD KEY `idPasajeros` (`idPasajeros`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idRol`),
  ADD UNIQUE KEY `nombreRol` (`nombreRol`);

--
-- Indices de la tabla `tiquetes`
--
ALTER TABLE `tiquetes`
  ADD PRIMARY KEY (`idTiquete`),
  ADD KEY `idReserva` (`idReserva`),
  ADD KEY `idPasajero_idVuelo` (`idPasajero`,`idVuelo`),
  ADD KEY `codigoReserva` (`codigoReserva`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aerolinea`
--
ALTER TABLE `aerolinea`
  MODIFY `idAerolinea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `asientos`
--
ALTER TABLE `asientos`
  MODIFY `idAsiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `aviones`
--
ALTER TABLE `aviones`
  MODIFY `idAvion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  MODIFY `idDisponibilidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `modeloaviones`
--
ALTER TABLE `modeloaviones`
  MODIFY `idModeloA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pasajeros`
--
ALTER TABLE `pasajeros`
  MODIFY `idPasajero` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `idReserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tiquetes`
--
ALTER TABLE `tiquetes`
  MODIFY `idTiquete` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `aviones`
--
ALTER TABLE `aviones`
  ADD CONSTRAINT `aviones_ibfk_1` FOREIGN KEY (`idModeloA`) REFERENCES `modeloaviones` (`idModeloA`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `aviones_ibfk_2` FOREIGN KEY (`idAerolinea`) REFERENCES `aerolinea` (`idAerolinea`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  ADD CONSTRAINT `disponibilidad_ibfk_1` FOREIGN KEY (`idAvion`) REFERENCES `aviones` (`idAvion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pasajeros`
--
ALTER TABLE `pasajeros`
  ADD CONSTRAINT `pasajeros_ibfk_1` FOREIGN KEY (`idRol`) REFERENCES `roles` (`idRol`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`idDisponibilidad`) REFERENCES `disponibilidad` (`idDisponibilidad`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`idPasajeros`) REFERENCES `pasajeros` (`idPasajero`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tiquetes`
--
ALTER TABLE `tiquetes`
  ADD CONSTRAINT `tiquetes_ibfk_1` FOREIGN KEY (`idReserva`) REFERENCES `reservas` (`idReserva`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
