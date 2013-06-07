-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 24, 2013 at 03:57 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.10-2ubuntu6.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE IF NOT EXISTS `admin_user` (
  `usu_id` int(11) NOT NULL AUTO_INCREMENT,
  `usu_nick` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `usu_password` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `usu_email` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `usu_level` int(11) NOT NULL,
  PRIMARY KEY (`usu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE IF NOT EXISTS `characters` (
  `cha_id` int(11) NOT NULL AUTO_INCREMENT,
  `cha_name` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `cha_fullname` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `cha_avatar` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cha_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `com_id` int(11) NOT NULL AUTO_INCREMENT,
  `com_fk_vid_id` int(11) NOT NULL,
  `com_fk_use_id` int(11) NOT NULL,
  `com_comment` text CHARACTER SET utf8 NOT NULL,
  `com_date` datetime NOT NULL,
  PRIMARY KEY (`com_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=40 ;

-- --------------------------------------------------------

--
-- Table structure for table `credit`
--

CREATE TABLE IF NOT EXISTS `credit` (
  `cre_id` int(11) NOT NULL AUTO_INCREMENT,
  `cre_name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `cre_role` varchar(16) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`cre_id`),
  UNIQUE KEY `cre_id` (`cre_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3882 ;

-- --------------------------------------------------------

--
-- Table structure for table `movie`
--

CREATE TABLE IF NOT EXISTS `movie` (
  `mov_released` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nayik`
--

CREATE TABLE IF NOT EXISTS `nayik` (
  `nay_id` int(11) NOT NULL AUTO_INCREMENT,
  `nay_fk_cha_id` int(11) NOT NULL,
  `nay_date` datetime NOT NULL,
  `nay_comment` text COLLATE utf8_spanish_ci NOT NULL,
  `nay_parent_id` int(11) DEFAULT NULL,
  `nay_image` varchar(128) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`nay_id`),
  KEY `nay_pos_character_id` (`nay_fk_cha_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Table structure for table `quote`
--

CREATE TABLE IF NOT EXISTS `quote` (
  `quo_id` int(11) NOT NULL AUTO_INCREMENT,
  `quo_en` varchar(128) CHARACTER SET utf8 NOT NULL,
  `quo_es` varchar(128) CHARACTER SET utf8 NOT NULL,
  `quo_author_es` varchar(64) CHARACTER SET utf8 NOT NULL,
  `quo_author_en` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`quo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `remix`
--

CREATE TABLE IF NOT EXISTS `remix` (
  `rem_id` int(11) NOT NULL AUTO_INCREMENT,
  `rem_title` varchar(128) CHARACTER SET utf8 NOT NULL,
  `rem_url` varchar(128) CHARACTER SET utf8 NOT NULL,
  `rem_author` varchar(128) CHARACTER SET utf8 NOT NULL,
  `rem_channel_url` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `rem_highlight` tinyint(1) NOT NULL,
  PRIMARY KEY (`rem_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `response`
--

CREATE TABLE IF NOT EXISTS `response` (
  `res_id` int(11) NOT NULL AUTO_INCREMENT,
  `res_fk_com_id` int(11) NOT NULL,
  `res_fk_adm_id` int(11) NOT NULL,
  `res_comment` text CHARACTER SET utf8 NOT NULL,
  `res_date` datetime NOT NULL,
  PRIMARY KEY (`res_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `use_id` int(11) NOT NULL AUTO_INCREMENT,
  `use_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`use_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `vid_id` int(11) NOT NULL AUTO_INCREMENT,
  `vid_title_es` varchar(128) COLLATE utf8_spanish_ci DEFAULT NULL,
  `vid_title_en` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `vid_url_en` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vid_url_es` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `vid_protected` tinyint(1) NOT NULL,
  `vid_release` datetime NOT NULL,
  `vid_synopsis_short_en` varchar(512) COLLATE utf8_spanish_ci DEFAULT NULL,
  `vid_synopsis_short_es` varchar(512) COLLATE utf8_spanish_ci DEFAULT NULL,
  `vid_synopsis_long_en` text COLLATE utf8_spanish_ci NOT NULL,
  `vid_synopsis_long_es` text COLLATE utf8_spanish_ci NOT NULL,
  `vid_duration` int(11) NOT NULL,
  `vid_order` int(2) unsigned DEFAULT NULL,
  `vid_category` varchar(16) COLLATE utf8_spanish_ci NOT NULL,
  `vid_thumbnail` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `vid_cover` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `vid_slug` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`vid_id`),
  UNIQUE KEY `vid_url` (`vid_url_en`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=97 ;

-- --------------------------------------------------------

--
-- Table structure for table `visualization`
--

CREATE TABLE IF NOT EXISTS `visualization` (
  `vis_id` int(11) NOT NULL AUTO_INCREMENT,
  `vis_fk_vid_id` int(11) NOT NULL,
  `vis_fk_use_id` int(11) NOT NULL,
  `vis_date` datetime NOT NULL,
  PRIMARY KEY (`vis_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ;

