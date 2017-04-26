/*
 Navicat Premium Data Transfer

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 50538
 Source Host           : localhost
 Source Database       : wdu_dev

 Target Server Type    : MySQL
 Target Server Version : 50538
 File Encoding         : utf-8

 Date: 04/26/2017 12:01:47 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `albums`
-- ----------------------------
DROP TABLE IF EXISTS `albums`;
CREATE TABLE `albums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vimeo_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100954 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `contact`
-- ----------------------------
DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `fullname` text,
  `email` varchar(255) DEFAULT NULL,
  `message` text,
  `response` text,
  `response_time` int(11) DEFAULT NULL,
  `archived` int(11) DEFAULT '0',
  `creation` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `roles`
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `user_ips`
-- ----------------------------
DROP TABLE IF EXISTS `user_ips`;
CREATE TABLE `user_ips` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `ip` int(16) DEFAULT NULL,
  `creation` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(255) DEFAULT NULL,
  `paypal_token` varchar(255) DEFAULT NULL,
  `paypal_status` varchar(12) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `archived` int(11) DEFAULT '0',
  `creation` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_idx` (`id`) USING BTREE,
  KEY `email_idx` (`email`) USING BTREE,
  KEY `firstname_idx` (`firstname`) USING BTREE,
  KEY `lastname_idx` (`lastname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `users_d`
-- ----------------------------
DROP TABLE IF EXISTS `users_d`;
CREATE TABLE `users_d` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `rowcol` char(2) NOT NULL,
  `creation` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `videos`
-- ----------------------------
DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vimeo_id` int(11) DEFAULT NULL,
  `definition` varchar(4) DEFAULT NULL,
  `key` varchar(40) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `album` varchar(100) DEFAULT NULL,
  `thumbnail` varchar(100) DEFAULT NULL,
  `featured` int(1) DEFAULT NULL,
  `plays` int(10) unsigned DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  `creation` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=557 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `videos_viewed`
-- ----------------------------
DROP TABLE IF EXISTS `videos_viewed`;
CREATE TABLE `videos_viewed` (
  `id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `video_id` int(11) DEFAULT NULL,
  `creation` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;
