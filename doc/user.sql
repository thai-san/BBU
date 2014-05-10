/*
Navicat MySQL Data Transfer

Source Server         : MySQL
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : bbudb

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2014-05-10 19:01:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
