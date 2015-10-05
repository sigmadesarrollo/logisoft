/*
SQLyog Enterprise - MySQL GUI v8.05 
MySQL - 5.5.8-log : Database - pmm
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`pmm` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `pmm`;

/*Table structure for table `catempleado_admin` */

DROP TABLE IF EXISTS `catempleado_admin`;

CREATE TABLE `catempleado_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idEmpleado` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `foraneos` */

DROP TABLE IF EXISTS `foraneos`;

CREATE TABLE `foraneos` (
  `Id` double NOT NULL AUTO_INCREMENT,
  `Folio` varchar(20) NOT NULL,
  `IdSucursal` int(11) NOT NULL,
  `Sucursal` varchar(50) NOT NULL,
  `Usuario` varchar(100) NOT NULL,
  `Unidad` varchar(60) NOT NULL,
  `Kilometraje` varchar(100) NOT NULL,
  `Placas` varchar(30) NOT NULL,
  `Servicios` text NOT NULL,
  `Costo` double NOT NULL,
  `Proveedor` varchar(150) NOT NULL,
  `TiempoEntrega` int(2) NOT NULL DEFAULT '0',
  `Fecha` varchar(8) NOT NULL,
  `Hora` varchar(12) NOT NULL,
  `Autorizado` char(2) NOT NULL,
  `Consecutivo` double NOT NULL DEFAULT '0',
  `IdUsuario` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `mantenimiento` */

DROP TABLE IF EXISTS `mantenimiento`;

CREATE TABLE `mantenimiento` (
  `Id` double NOT NULL AUTO_INCREMENT,
  `Folio` varchar(20) NOT NULL,
  `IdSucursal` int(11) NOT NULL,
  `Sucursal` varchar(50) NOT NULL,
  `Usuario` varchar(100) NOT NULL,
  `Unidad` varchar(60) NOT NULL,
  `Kilometraje` varchar(100) NOT NULL,
  `Placas` varchar(30) NOT NULL,
  `Servicios` text NOT NULL,
  `Costo` double NOT NULL,
  `Proveedor` varchar(150) NOT NULL,
  `TiempoEntrega` int(2) NOT NULL DEFAULT '0',
  `Fecha` varchar(8) NOT NULL,
  `Hora` varchar(12) NOT NULL,
  `Autorizado` char(2) NOT NULL,
  `Consecutivo` double NOT NULL DEFAULT '0',
  `IdUsuario` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `mobiliario` */

DROP TABLE IF EXISTS `mobiliario`;

CREATE TABLE `mobiliario` (
  `Id` double NOT NULL AUTO_INCREMENT,
  `Folio` varchar(20) NOT NULL,
  `IdSucursal` int(11) NOT NULL,
  `Sucursal` varchar(50) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Servicios` text NOT NULL,
  `Costo` double NOT NULL DEFAULT '0',
  `Proveedor` varchar(150) NOT NULL,
  `TiempoEntrega` int(2) NOT NULL DEFAULT '0',
  `Fecha` varchar(8) NOT NULL,
  `Hora` varchar(12) NOT NULL,
  `Autorizado` char(2) NOT NULL,
  `Consecutivo` double NOT NULL,
  `IdUsuario` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Table structure for table `papeleria` */

DROP TABLE IF EXISTS `papeleria`;

CREATE TABLE `papeleria` (
  `Id` double NOT NULL AUTO_INCREMENT,
  `Folio` varchar(20) NOT NULL,
  `IdSucursal` int(11) NOT NULL,
  `Sucursal` varchar(50) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Pedido` text NOT NULL,
  `Costo` double NOT NULL DEFAULT '0',
  `Proveedor` varchar(150) NOT NULL,
  `TiempoEntrega` int(2) NOT NULL DEFAULT '0',
  `Fecha` varchar(8) NOT NULL,
  `Hora` varchar(12) NOT NULL,
  `Autorizado` char(2) NOT NULL,
  `Consecutivo` double NOT NULL,
  `IdUsuario` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
