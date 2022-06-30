/*
SQLyog Ultimate v12.08 (64 bit)
MySQL - 5.7.26 : Database - traditional_culture_exchange_network
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`traditional_culture_exchange_network` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `traditional_culture_exchange_network`;

/*Table structure for table `fun_category` */

DROP TABLE IF EXISTS `fun_category`;

CREATE TABLE `fun_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(12) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Table structure for table `fun_post` */

DROP TABLE IF EXISTS `fun_post`;

CREATE TABLE `fun_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(20) NOT NULL COMMENT '内容标题',
  `content` text NOT NULL COMMENT '内容文本',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `reply` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回复量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

/*Table structure for table `fun_reply` */

DROP TABLE IF EXISTS `fun_reply`;

CREATE TABLE `fun_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '回复内容',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '回复时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Table structure for table `fun_session` */

DROP TABLE IF EXISTS `fun_session`;

CREATE TABLE `fun_session` (
  `id` varchar(255) NOT NULL COMMENT 'SessionID',
  `expires` int(10) unsigned NOT NULL COMMENT '过期时间',
  `data` varchar(255) DEFAULT NULL COMMENT '数据',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `fun_user` */

DROP TABLE IF EXISTS `fun_user`;

CREATE TABLE `fun_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` enum('admin','user') NOT NULL DEFAULT 'user' COMMENT '用户组',
  `name` varchar(10) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(32) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(32) NOT NULL DEFAULT '' COMMENT '密钥',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像地址',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
