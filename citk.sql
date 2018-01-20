-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 28, 2017 at 03:01 PM
-- Server version: 5.7.19
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `citk.cf`
--

-- --------------------------------------------------------

--
-- Table structure for table `acl`
--

DROP TABLE IF EXISTS `acl`;
CREATE TABLE IF NOT EXISTS `acl` (
  `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `action_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`ai`),
  KEY `action_id` (`action_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acl_actions`
--

DROP TABLE IF EXISTS `acl_actions`;
CREATE TABLE IF NOT EXISTS `acl_actions` (
  `action_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `action_code` varchar(100) NOT NULL COMMENT 'No periods allowed!',
  `action_desc` varchar(100) NOT NULL COMMENT 'Human readable description',
  `category_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`action_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acl_categories`
--

DROP TABLE IF EXISTS `acl_categories`;
CREATE TABLE IF NOT EXISTS `acl_categories` (
  `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_code` varchar(100) NOT NULL COMMENT 'No periods allowed!',
  `category_desc` varchar(100) NOT NULL COMMENT 'Human readable description',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_code` (`category_code`),
  UNIQUE KEY `category_desc` (`category_desc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_sessions`
--

DROP TABLE IF EXISTS `auth_sessions`;
CREATE TABLE IF NOT EXISTS `auth_sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `modified_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_sessions`
--

INSERT INTO `auth_sessions` (`id`, `user_id`, `login_time`, `modified_at`, `ip_address`, `user_agent`) VALUES
('pu68lmqjeb8imkus2ndaiarqprhi0pgv', 122357832, '2017-12-28 13:37:06', '2017-12-28 14:56:36', '::1', 'Chrome 63.0.3239.84 on Windows 10');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `code` varchar(3) NOT NULL,
  `name` varchar(100) NOT NULL,
  `classes` text NOT NULL COMMENT 'comma-separated list of class ids',
  `icon` varchar(20) NOT NULL DEFAULT 'university' COMMENT 'font awesome icon name',
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`code`, `name`, `classes`, `icon`) VALUES
('CSE', 'Computer Science and Engineering', '111,112,121,122,131,132,141,142', 'code'),
('ECE', 'Electronics and Communication Engineering', '111,112,121,122,131,132,141,142', 'bolt'),
('IE', 'Instrumentation Engineering', '111,112,121,122,131,132,141,142', 'university'),
('CE', 'Civil Engineering', '111,112,121,122,131,132,141,142', 'road'),
('FET', 'Food Engineering and Technology', '111,112,121,122,131,132,141,142', 'flask'),
('IT', 'Information Technology', '111,112,121,122,131,132,141,142', 'desktop');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `denied_access`
--

DROP TABLE IF EXISTS `denied_access`;
CREATE TABLE IF NOT EXISTS `denied_access` (
  `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `reason_code` tinyint(1) UNSIGNED DEFAULT '0',
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `denied_access`
--

INSERT INTO `denied_access` (`ai`, `ip_address`, `time`, `reason_code`) VALUES
(2, '::1', '2017-12-26 15:09:58', 1);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `name` varchar(200) NOT NULL,
  `note_id` int(11) NOT NULL,
  `uploaded_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`name`, `note_id`, `uploaded_on`) VALUES
('joining.jpg', 1, '2017-12-07 13:28:27'),
('NO_OBJECTION_CERTIFICATE1.docx', 1, '2017-12-07 13:36:20'),
('pdf.png', 1, '2017-12-11 16:31:12'),
('anjali-xii-result.pdf', 1, '2017-12-11 16:45:28'),
('AntiRagging_snehanshu_ref_num.JPG', 1, '2017-12-11 17:01:30'),
('AntiRagging_snehanshu_ref_num1.JPG', 1, '2017-12-11 17:03:32'),
('drone-2724257_1920.jpg', 8, '2017-12-28 14:01:14'),
('75bd7c39a30d0e85de0975deb28bb906.jpg', 8, '2017-12-28 14:07:22'),
('a51aacb75865d1eeac0154310d71c1fc.jpg', 8, '2017-12-28 14:08:06'),
('eeb2afa3c076be0963de11687d4273e0.jpg', 8, '2017-12-28 14:16:17'),
('fb_cr_MeenaPeluce.jpg', 8, '2017-12-28 14:16:23'),
('maxresdefault_(3).jpg', 8, '2017-12-28 14:17:21'),
('fdc1ab958b377f047116889df73f3ae9.jpg', 8, '2017-12-28 14:17:40'),
('13f3a2cc647b3e1407087c08aa018560.jpg', 8, '2017-12-28 14:19:29'),
('600x600.jpg', 8, '2017-12-28 14:19:56'),
('600x6001.jpg', 11, '2017-12-28 14:24:08');

-- --------------------------------------------------------

--
-- Table structure for table `ips_on_hold`
--

DROP TABLE IF EXISTS `ips_on_hold`;
CREATE TABLE IF NOT EXISTS `ips_on_hold` (
  `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `login_errors`
--

DROP TABLE IF EXISTS `login_errors`;
CREATE TABLE IF NOT EXISTS `login_errors` (
  `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username_or_email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `login_errors`
--

INSERT INTO `login_errors` (`ai`, `username_or_email`, `ip_address`, `time`) VALUES
(53, 'angad', '::1', '2017-12-27 07:09:28');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `unit` int(11) NOT NULL,
  `teacher` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_is_auto` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Auto means added automatically, otherwise its the date the note was given on',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `title`, `content`, `unit`, `teacher`, `date`, `date_is_auto`) VALUES
(1, 'Introduction to Thermodynamics', '<p>This is a test note. You will find nothing here. You can make some text <b>bold</b>, otherwise <i>italics</i> and everything else.<br />\r\nThis is a <a href=\"https://google.co.in\" target=\"_blank\">link to </a><img alt=\"joining.jpg\" src=\"http://[::1]/citk/uploads/joining.jpg\" style=\"height: 700px; float: right;\" /><a href=\"https://google.co.in\" target=\"_blank\">Google</a>. And a link to <a href=\"https://amazon.in\" onclick=\"window.open(this.href, \'amazon\', \'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no\'); return false;\">Amazon</a>.</p>\r\n\r\n<h2 style=\"text-align: center;\"><span style=\"color:#2980b9;\"><span style=\"font-size:48px;\"><span style=\"font-family:Lucida Sans Unicode,Lucida Grande,sans-serif;\"><span style=\"background-color:#ecf0f1;\">Editing note</span></span></span></span></h2>\r\n\r\n<h2 style=\"text-align: center;\">&nbsp;</h2>\r\n\r\n<p>tytu7i</p>\r\n\r\n<p>tyur6i76<img alt=\"pdf.png\" height=\"150\" src=\"http://[::1]/citk/uploads/pdf.png\" /></p>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<div>&nbsp;</div>\r\n', 1, 1, '2017-11-22', 0),
(2, 'Basic terms of thermodynamics', '<p>Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics Basic terms of thermodynamics</p>\r\n', 1, 1, '2017-12-09', 0),
(3, 'Brown sugar', '', 1, 1, '2017-12-14', 0),
(9, 'I know nothing', '', 1, 1, '0000-00-00', 0),
(8, 'Some name', '<div style=\"background:#eeeeee;border:1px solid #cccccc;padding:5px 10px;\">Let&#39;s put something in this note.</div>\r\n\r\n<h2 style=\"font-style:italic;\"><img alt=\"\" src=\"http://citk/uploads/drone-2724257_1920.jpg\" style=\"width: 800px; height: 368px;\" /></h2>\r\n', 1, 3, '0000-00-00', 0),
(7, 'Do you love me', '', 1, 0, '0000-00-00', 0),
(10, 'Another supernatural note', '<p>IDK why</p>\r\n', 1, 0, '0000-00-00', 0),
(11, 'Being Human', '<p>I was, am and will always be a human.</p>\r\n', 1, 1, '2017-02-14', 0),
(12, 'I am <u>XSS</u> proof', '', 1, 1, '2017-02-15', 0),
(13, 'I was hidden', '', 1, 0, '0000-00-00', 0),
(14, 'I was also hidden', '', 1, 0, '0000-00-00', 0),
(15, 'Let\'s enjoy the future', '', 1, 0, '0000-00-00', 0),
(16, 'The last named', '', 1, 0, '0000-00-00', 0),
(17, 'Dummy note supernatural', '<p>It seems like that this note is&nbsp;<strong>supernatural</strong>.</p>\r\n', 1, 0, '2016-12-19', 0);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(6) NOT NULL DEFAULT '' COMMENT 'It is the subject code',
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `classes` text NOT NULL COMMENT 'comma-separated list of class ids',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `code`, `title`, `description`, `classes`) VALUES
(1, 'PH101', 'Engineering physics', 'Learn physics basics that is required for engineering.', '111'),
(2, 'CS101', 'Introduction to computer programming', 'Learn the basics of C language.', '111,112,121,122,131,142'),
(3, 'ED101', 'Engineering drawing - I', 'The civil engineering element of the first years', '111,211');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `photo` varchar(100) NOT NULL COMMENT 'photo name in uploads folder',
  `designation` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(15) NOT NULL DEFAULT '',
  `tenure` varchar(25) NOT NULL DEFAULT '' COMMENT 'joining and leaving date',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `photo`, `designation`, `email`, `phone`, `tenure`) VALUES
(1, 'Sandeep Kumar Srivastava', 'http://{{cc_mywebsiteroot}}/assets/images/teachers/600x600.jpg', 'HOD, Physics department', 'sandeep.ks@cit.ac.in', '+91 99570 12345', '2014 - Present'),
(2, 'Hemanta Kumar Kalita', '', 'Assistant manager, CSE Dept', 'hkk.nehu@cit.ac.in', '1234567890', '2017-present'),
(3, 'B.N. Parida', '', 'Physics Teacher', 'bnp.phy@cit.ac.in', '+91 765032314', '2013 - present'),
(4, 'RLH', '', 'Physics teahcer', 'rlh.phy@cit.ac.in', '+91 9865200021', '2015 - present'),
(5, 'Bihung Brahma', '', 'HOD, humanities dept.', 'bhb.hum@cit.ac.in', '+91 8963235021', '2012 - present');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE IF NOT EXISTS `units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `subject` varchar(6) NOT NULL,
  `class` varchar(3) NOT NULL DEFAULT '' COMMENT 'Module(Deg:1, Dip:2) Year Semester',
  `branch` text NOT NULL COMMENT 'comma-seperated of branch code',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `title`, `subject`, `class`, `branch`) VALUES
(1, 'Thermodynamics', 'PH101', '111', 'CSE,ECE,IE,CE,FET,IT'),
(2, 'Rotational motion', 'PH101', '112', 'CSE,ECE,IE,CE,FET,IT'),
(3, 'Understanding our environment', 'ES101', '111', 'CSE,ECE,IE,CE,FET,IT'),
(4, 'Abaha', 'PH101', '111', 'CSE,IE,CE,FET');

-- --------------------------------------------------------

--
-- Table structure for table `username_or_email_on_hold`
--

DROP TABLE IF EXISTS `username_or_email_on_hold`;
CREATE TABLE IF NOT EXISTS `username_or_email_on_hold` (
  `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username_or_email` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(12) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `auth_level` tinyint(3) UNSIGNED NOT NULL,
  `banned` enum('0','1') NOT NULL DEFAULT '0',
  `passwd` varchar(60) NOT NULL,
  `passwd_recovery_code` varchar(60) DEFAULT NULL,
  `passwd_recovery_date` datetime DEFAULT NULL,
  `passwd_modified_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `auth_level`, `banned`, `passwd`, `passwd_recovery_code`, `passwd_recovery_date`, `passwd_modified_at`, `last_login`, `created_at`, `modified_at`) VALUES
(122357832, 'kishorTimi', 'kishor.saikia892@gmail.com', 6, '0', '$2y$11$7jYvjC1yKBRDoiHzS7kXRepSVsrJk/2wsZiK.2IWP6IzSC2uhjjBW', NULL, NULL, '2017-12-26 18:42:43', '2017-12-28 13:37:06', '2017-12-26 13:05:53', '2017-12-28 14:27:42'),
(944774104, 'admins', 'anupamsaikiajnv@gmail.com', 9, '0', '$2y$11$36vw3S6LCFrPdF7Ge2r/n..TAH5BCF2E2PYC8o1oXCdleohAMIiJi', NULL, NULL, '2017-12-26 20:46:07', '2017-12-28 13:10:44', '2017-11-30 10:29:48', '2017-12-28 13:10:44'),
(3900933762, 'angad', 'angad.singh@gmail.com', 1, '0', '$2y$11$36vw3S6LCFrPdF7Ge2r/n..TAH5BCF2E2PYC8o1oXCdleohAMIiJi', NULL, NULL, '2017-12-26 21:08:05', '2017-12-27 07:09:42', '2017-12-26 13:28:39', '2017-12-27 08:42:06');

--
-- Triggers `users`
--
DROP TRIGGER IF EXISTS `ca_passwd_trigger`;
DELIMITER $$
CREATE TRIGGER `ca_passwd_trigger` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF ((NEW.passwd <=> OLD.passwd) = 0) THEN
        SET NEW.passwd_modified_at = NOW();
    END IF;
END
$$
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acl`
--
ALTER TABLE `acl`
  ADD CONSTRAINT `acl_ibfk_1` FOREIGN KEY (`action_id`) REFERENCES `acl_actions` (`action_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acl_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `acl_actions`
--
ALTER TABLE `acl_actions`
  ADD CONSTRAINT `acl_actions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `acl_categories` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
