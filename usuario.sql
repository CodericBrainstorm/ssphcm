-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 08-11-2012 a las 19:19:17
-- Versión del servidor: 5.5.16
-- Versión de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `ssphcm`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `cod_user` int(11) NOT NULL AUTO_INCREMENT,
  `cedusu` varchar(10) NOT NULL,
  `nomusu` varchar(40) NOT NULL,
  `apeusu` varchar(40) NOT NULL,
  `sexo` enum('F','M') NOT NULL,
  `lugnacusu` varchar(80) NOT NULL,
  `fecnacusu` date NOT NULL,
  `edad` smallint(6) NOT NULL,
  `edocivil` enum('S','C','D','V','O') NOT NULL,
  `dirusu` varchar(120) NOT NULL,
  `cod_cargo` int(11) NOT NULL,
  `login` varchar(15) NOT NULL,
  `password` varchar(32) NOT NULL,
  `observa` varchar(200) NOT NULL,
  PRIMARY KEY (`cod_user`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`cod_user`, `cedusu`, `nomusu`, `apeusu`, `sexo`, `lugnacusu`, `fecnacusu`, `edad`, `edocivil`, `dirusu`, `cod_cargo`, `login`, `password`, `observa`) VALUES
(1, '20568758', 'WILMARYS', 'SUAREZ', '', '', '0000-00-00', 0, '', '', 0, 'wilmars', '2a4809d0b91219f7a1b5ce93c9d9c3ae', ''),
(2, '15916372', 'LOREANNY', 'IRAHOLA', '', '', '0000-00-00', 0, '', '', 0, 'loreannyi', '1671606df64f40848e02729c83ba9577', ''),
(3, '15312317', 'JORGE', 'CASTILLO', 'F', '', '0000-00-00', 0, 'S', '', 0, 'jorgec', '7510ff29eabc706d8a21daedf9a4374f', ''),
(4, '19058829', 'LUZMAR', 'JORDAN', 'F', '', '0000-00-00', 0, 'S', '', 0, 'luzmarj', '638ff1ce61ae4c73d35e1737e9faded0', ''),
(5, '14027337', 'NADIA', 'MORLES', 'F', '', '0000-00-00', 0, 'S', '', 0, 'nadiam', '2c4b0b1644d37cdabb8a2db7172c8782', ''),
(6, '17351339', 'ISBELDY', 'SILVA', 'F', '', '0000-00-00', 0, 'S', '', 0, 'isbeldys', 'a715adf83388854f0f75cee8018bb0f2', ''),
(7, '13901867', 'JUAN', 'GUERRA', 'F', '', '0000-00-00', 0, 'S', '', 0, 'juang', '604c3b34d7bba0e68521ddf78109baed', ''),
(8, '17094400', 'MAIHEN', 'SUAREZ', 'F', '', '0000-00-00', 0, 'S', '', 0, 'maihens', 'a3fe3edd3522be2d5b40c4a38cfeaa5f', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
