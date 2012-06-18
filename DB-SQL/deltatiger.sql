-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 18, 2012 at 04:46 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `deltatiger`
--

-- --------------------------------------------------------

--
-- Table structure for table `dt_blog_comments`
--

CREATE TABLE IF NOT EXISTS `dt_blog_comments` (
  `blog_post_id` int(7) NOT NULL,
  `blog_post_comment_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `blog_post_comment_author` text NOT NULL,
  `blog_post_comment_ip` text NOT NULL,
  `blog_post_comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dt_blog_posts`
--

CREATE TABLE IF NOT EXISTS `dt_blog_posts` (
  `blog_post_id` int(7) NOT NULL AUTO_INCREMENT,
  `blog_post_title` text NOT NULL,
  `blog_post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `blog_post_body` text NOT NULL,
  `blog_post_comment_count` int(3) NOT NULL,
  `blog_post_picture_id` int(6) NOT NULL,
  `blog_post_picture_ext` text NOT NULL,
  PRIMARY KEY (`blog_post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=107 ;

--
-- Dumping data for table `dt_blog_posts`
--

INSERT INTO `dt_blog_posts` (`blog_post_id`, `blog_post_title`, `blog_post_time`, `blog_post_body`, `blog_post_comment_count`, `blog_post_picture_id`, `blog_post_picture_ext`) VALUES
(106, 'New Site logo !', '2012-03-25 09:58:25', 'Okay after some simple photo shopping i have created a cool new logo for the site. Based solely on my favorite colors black and blue . But should probably make this better cause at current condition this ain''t that good.<br /><br />Also Added a small 4px white border between the header and the nav bar so it looks a little better. But will probably have to replace that idea with something cooler. Probably like a new navbar style or something. <br /><br />Final verdict: I still suck in design. I can create the most complex of scripts yet fail over html and css. Epic fail.', 0, 90, 'jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `dt_cache_info`
--

CREATE TABLE IF NOT EXISTS `dt_cache_info` (
  `cache_time` text NOT NULL,
  `cache_page_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dt_cache_info`
--

INSERT INTO `dt_cache_info` (`cache_time`, `cache_page_name`) VALUES
('1339985032', 'index'),
('1339985035', 'login_form'),
('1338484408', 'blog'),
('1338959216', 'admin_index');

-- --------------------------------------------------------

--
-- Table structure for table `dt_config`
--

CREATE TABLE IF NOT EXISTS `dt_config` (
  `config_name` text NOT NULL,
  `config_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dt_config`
--

INSERT INTO `dt_config` (`config_name`, `config_value`) VALUES
('site_on', '1'),
('theme', 'Enigma'),
('Default User Group', 'Guest User');

-- --------------------------------------------------------

--
-- Table structure for table `dt_cookie_info`
--

CREATE TABLE IF NOT EXISTS `dt_cookie_info` (
  `set_time` text NOT NULL,
  `last_active_time` int(11) NOT NULL,
  `user_agent` text NOT NULL,
  `create_ip` text NOT NULL,
  `cookie_id` text NOT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dt_error_log`
--

CREATE TABLE IF NOT EXISTS `dt_error_log` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `page` text NOT NULL,
  `desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dt_error_log`
--

INSERT INTO `dt_error_log` (`time`, `page`, `desc`) VALUES
('0000-00-00 00:00:00', 'test', 'This is a test'),
('0000-00-00 00:00:00', 'test', 'This is a test'),
('2012-03-02 04:24:20', 'test', 'This is a test');

-- --------------------------------------------------------

--
-- Table structure for table `dt_project_info`
--

CREATE TABLE IF NOT EXISTS `dt_project_info` (
  `project_name` text NOT NULL,
  `project_id` int(10) NOT NULL AUTO_INCREMENT,
  `project_type` int(1) NOT NULL,
  `project_view_count` int(5) NOT NULL,
  `project_rating` int(1) NOT NULL,
  `project_rating_count` int(5) NOT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dt_session_info`
--

CREATE TABLE IF NOT EXISTS `dt_session_info` (
  `session_id` text NOT NULL,
  `user_id` int(10) NOT NULL,
  `user_group` int(5) NOT NULL,
  `create_time` text NOT NULL,
  `last_active_time` int(15) NOT NULL,
  `create_ip` text NOT NULL,
  `last_browser` text NOT NULL,
  `login_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dt_session_info`
--

INSERT INTO `dt_session_info` (`session_id`, `user_id`, `user_group`, `create_time`, `last_active_time`, `create_ip`, `last_browser`, `login_status`) VALUES
('b6933970062aa0677e402dbc8653c5bad9168ac7', 0, 0, '1338484681', 1339987578, '::1', 'Firefox', 0),
('b6933970062aa0677e402dbc8653c5bad9168ac7', 0, 0, '1338558402', 1339987578, '::1', 'Firefox', 0),
('b6933970062aa0677e402dbc8653c5bad9168ac7', 0, 0, '1338729473', 1339987578, '::1', 'Firefox', 0),
('b6933970062aa0677e402dbc8653c5bad9168ac7', 0, 0, '1338733857', 1339987578, '::1', 'Firefox', 0),
('b6933970062aa0677e402dbc8653c5bad9168ac7', 0, 0, '1338959175', 1339987578, '::1', 'Firefox', 0),
('b6933970062aa0677e402dbc8653c5bad9168ac7', 0, 0, '1339985035', 1339987578, '::1', 'Firefox', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dt_user_groups`
--

CREATE TABLE IF NOT EXISTS `dt_user_groups` (
  `group_name` text NOT NULL,
  `group_id` int(4) NOT NULL AUTO_INCREMENT,
  `group_admin` int(4) NOT NULL,
  `group_color` text NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `dt_user_groups`
--

INSERT INTO `dt_user_groups` (`group_name`, `group_id`, `group_admin`, `group_color`) VALUES
('Admin', 1, 101, '#580000'),
('Guest User', 2, 101, '#000000');

-- --------------------------------------------------------

--
-- Table structure for table `dt_user_info`
--

CREATE TABLE IF NOT EXISTS `dt_user_info` (
  `user_id` int(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `username_clean` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `create_time` text NOT NULL,
  `user_group` int(5) NOT NULL,
  `user_email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dt_user_info`
--

INSERT INTO `dt_user_info` (`user_id`, `username`, `username_clean`, `password`, `create_time`, `user_group`, `user_email`) VALUES
(101, 'DeltaTiger', 'deltatiger', '20c6c8de8a68db4232e26b316dc4695204156354', '', 1, 'srihare.gr@gmail.com');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
