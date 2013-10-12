/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50095
Source Host           : localhost:3306
Source Database       : templi

Target Server Type    : MYSQL
Target Server Version : 50095
File Encoding         : 65001

Date: 2013-03-21 09:33:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `templi_admin`
-- ----------------------------
DROP TABLE IF EXISTS `templi_admin`;
CREATE TABLE `templi_admin` (
  `adminid` mediumint(9) NOT NULL auto_increment,
  `adminname` varchar(30) default NULL,
  `password` varchar(32) default NULL,
  `encrypt` varchar(6) default NULL,
  PRIMARY KEY  (`adminid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of templi_admin
-- ----------------------------
INSERT INTO `templi_admin` VALUES ('1', 'admin', '48487e1cfd1e4f7ba28d12b05f8c9c8c', 'FYmW2H');
