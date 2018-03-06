/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : cnis

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-03-06 10:36:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for printersetup
-- ----------------------------
DROP TABLE IF EXISTS `printersetup`;
CREATE TABLE `printersetup` (
  `PcID` varchar(50) NOT NULL,
  `PrinterType` int(4) NOT NULL COMMENT '1、标签打印机，2、肠内医嘱打印机',
  `PrinterName` varchar(255) DEFAULT NULL,
  `Orient` int(4) DEFAULT NULL,
  `PageName` varchar(255) DEFAULT NULL,
  `PageWidth` varchar(20) DEFAULT NULL,
  `PageHeigth` varchar(20) DEFAULT NULL,
  UNIQUE KEY `PcID_PrinterType_Unique` (`PcID`,`PrinterType`)
) ENGINE=MyISAM DEFAULT CHARSET=utf16;
