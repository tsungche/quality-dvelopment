-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 04 月 01 日 06:50
-- 服务器版本: 5.5.20
-- PHP 版本: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `sztz`
--

-- --------------------------------------------------------

--
-- 表的结构 `t_administrator`
--

CREATE TABLE IF NOT EXISTS `t_administrator` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `user_id` int(11) NOT NULL COMMENT '工号',
  `name` varchar(32) NOT NULL COMMENT '名称',
  `created_by` varchar(32) NOT NULL COMMENT '创建人',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='管理员表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `t_administrator`
--

INSERT INTO `t_administrator` (`id`, `user_id`, `name`, `created_by`, `created_at`) VALUES
(1, 100, '学院教指委', 'H', '0000-00-00 00:00:00'),
(2, 1000, '学生工作处', 'H', '0000-00-00 00:00:00'),
(3, 1, '超级管理员', 'H', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `t_grade`
--

CREATE TABLE IF NOT EXISTS `t_grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `name` varchar(16) NOT NULL COMMENT '名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='年级表' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `t_grade`
--

INSERT INTO `t_grade` (`id`, `name`) VALUES
(1, '2012'),
(2, '2013'),
(3, '2014'),
(4, '2015');

-- --------------------------------------------------------

--
-- 表的结构 `t_notice`
--

CREATE TABLE IF NOT EXISTS `t_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `content` text NOT NULL COMMENT '公告内容',
  `created_by` varchar(32) NOT NULL COMMENT '创建人',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_by` varchar(32) NOT NULL COMMENT '更新人',
  `updated_at` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='公告表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `t_notice`
--

INSERT INTO `t_notice` (`id`, `content`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, '　　(newss)2001年11月22日上午，共青团中央、教育部、文化部、国务院新闻办公室、全国青联、全国学联、全国少工委、中国青少年网络协会在人民大学联合召开网上发布大会，向社会正式发布《全国青少年网络文明公约》。10时整，全国人大常委会副委员长许嘉璐郑重按动键盘，正式揭开了中国青少年计算机信息服务网上的《全国青少年网络文明公约》主页面，宣告《全国青少年网络文明公约》正式发布。这标志着我国青少年有了较为完备的网络行为道德规范。这是我国青少年网络生活中具有里程碑意义的一件大事，必将在今后的网络使用中产生深远的影响。<br/>\r\n　　据悉，发布《全国青少年网络文明公约》，是团中央贯彻江泽民总书记“三个代表”重要思想，落实《公民道德建设实施纲要》的重要举措，旨在推动网络道德建设，进一步提高青少年道德水平。截止2001年末，上网人数已达2650万，并且还在以每年翻一番的速度增长，其中青少年占80%以上。团中央等部门在调查中发现，网络已成为青少年学习知识、交流思想、休闲娱乐的重要平台，增强了青少年与外界的沟通和交流，有利于创造出全新的生活方式和社会互动关系，有利于青少年的发展。同时，青少年也最容易受到网络不健康现象的侵害。例如，有的青少年在网上浏览不良信息，有的超时无节制上网，有的因缺乏自护意识而上当受骗等等。2001年10月，上海两名女中学生因约见网友离家30多天，身心受到严重伤害。在武汉、广州、天津、重庆等城市，中小学生因上网而夜不归宿已经成为一个严重的社会问题。据调查，青少年性犯罪与网络色情内容有着比较大的关系。所有这些现象说明，网络秩序亟待规范，网络道德亟待加强。为了增强青少年自律意识和自护能力，保障他们健康成长，经过认真研究，反复论证，多方征求意见，团中央等部门正式推出了《全国青少年网络文明公约》。<br/>　　日前，共青团中央等八单位已发出通知，将在全国范围内开展学习、宣传公约的系列活动，增强青少年网络安全防范意识和网络道德意识，倡导全社会关注青少年网络环境，荡涤网络不良现象。', 'H', '2015-12-26 18:28:20', 'H', '2015-12-31 18:28:20'),
(2, '　　(new)2001年11月22日上午，共青团中央、教育部、文化部、国务院新闻办公室、全国青联、全国学联、全国少工委、中国青少年网络协会在人民大学联合召开网上发布大会，向社会正式发布《全国青少年网络文明公约》。10时整，全国人大常委会副委员长许嘉璐郑重按动键盘，正式揭开了中国青少年计算机信息服务网上的《全国青少年网络文明公约》主页面，宣告《全国青少年网络文明公约》正式发布。这标志着我国青少年有了较为完备的网络行为道德规范。这是我国青少年网络生活中具有里程碑意义的一件大事，必将在今后的网络使用中产生深远的影响。<br/>\r\n　　日前，共青团中央等八单位已发出通知，将在全国范围内开展学习、宣传公约的系列活动，增强青少年网络安全防范意识和网络道德意识，倡导全社会关注青少年网络环境，荡涤网络不良现象。', 'H', '2015-12-21 07:13:09', 'H', '2015-12-30 03:13:09');

-- --------------------------------------------------------

--
-- 表的结构 `t_profession`
--

CREATE TABLE IF NOT EXISTS `t_profession` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `name` varchar(32) NOT NULL COMMENT '名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='专业表' AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `t_profession`
--

INSERT INTO `t_profession` (`id`, `name`) VALUES
(1, '信息管理与信息系统'),
(2, '市场营销'),
(3, '公共事业管理'),
(4, '工程管理'),
(5, '国际经济与贸易'),
(6, '国际商务'),
(7, '物流管理');

-- --------------------------------------------------------

--
-- 表的结构 `t_record`
--

CREATE TABLE IF NOT EXISTS `t_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `student_teacher_id` int(11) NOT NULL COMMENT '学生教师关系ID',
  `type_id` int(11) NOT NULL COMMENT '素质拓展类型',
  `title` varchar(64) NOT NULL COMMENT '标题',
  `url` varchar(512) NOT NULL COMMENT '附件路径',
  `remark` varchar(512) NOT NULL COMMENT '说明',
  `feedback` varchar(1024) NOT NULL COMMENT '反馈信息',
  `point` decimal(4,1) NOT NULL DEFAULT '0.0' COMMENT '得分',
  `status` varchar(32) NOT NULL DEFAULT '待审批' COMMENT '状态',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_by` varchar(32) NOT NULL COMMENT '处理人',
  `updated_at` datetime NOT NULL COMMENT '处理时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='素质拓展记录表' AUTO_INCREMENT=66 ;

--
-- 转存表中的数据 `t_record`
--

INSERT INTO `t_record` (`id`, `student_teacher_id`, `type_id`, `title`, `url`, `remark`, `feedback`, `point`, `status`, `created_at`, `updated_by`, `updated_at`) VALUES
(11, 1, 2, '测试', '/SZTZ/Uploads/test/test.pdf', '测试测试', '测试测试测试', '1.0', '已通过', '2015-12-30 12:00:00', '韩娜', '2015-12-09 22:00:55'),
(14, 1, 5, 'ACA', '/SZTZ/Uploads/10000/20151207/8ca0ac62d13579dade93fb60e1a84d24.0x.pdf', 'aawaa啊啊是', '无', '1.0', '已通过', '2015-12-07 14:39:47', '韩娜', '2015-12-09 20:27:35'),
(24, 1, 10, 'CCCC', '/SZTZ/Uploads/10000/20151209/207fd091ca4a1b8e2543a3431c17651e.0x.pdf', 'CCCC', '', '0.0', '待商议', '2015-12-09 19:12:49', '韩娜', '2015-12-11 00:14:54'),
(25, 1, 10, 'KKK', '/SZTZ/Uploads/10000/20151209/8ca0ac62d13579dade93fb60e1a84d24.0x.pdf', 'KKK', '', '0.0', '待商议', '2015-12-09 19:13:01', '学院教指委', '2015-12-11 00:08:16'),
(26, 1, 8, 'ABA', '/SZTZ/Uploads/10000/20151209/8ca0ac62d13579dade93fb60e1a84d24.0x.pdf', 'ABABA', 'aa', '0.0', '不通过', '2015-12-09 19:17:20', '韩娜', '2015-12-09 23:31:16'),
(28, 1, 8, '11', '/SZTZ/Uploads/10000/20151209/8ca0ac62d13579dade93fb60e1a84d24.0x.pdf', '23', '无', '0.8', '已通过', '2015-12-09 19:21:40', '韩娜', '2015-12-09 19:22:16'),
(29, 1, 2, 'AACCAA', '/SZTZ/Uploads/10000/20151209/8ca0ac62d13579dade93fb60e1a84d24.0x.pdf', 'AAA', '无', '1.0', '已通过', '2015-12-09 19:24:37', '韩娜', '2015-12-09 19:26:35'),
(30, 1, 1, 'ADAS', '/SZTZ/Uploads/10000/20151209/8ca0ac62d13579dade93fb60e1a84d24.0x.pdf', 'sada', 'aqaa ', '0.0', '不通过', '2015-12-09 20:54:03', '韩娜', '2015-12-09 23:29:36'),
(31, 1, 1, '1饿阿萨斯', '/SZTZ/Uploads/10000/20151209/8ca0ac62d13579dade93fb60e1a84d24.0x.pdf', '啊啊啊啊', '阿阿萨', '0.0', '不通过', '2015-12-09 22:54:15', '韩娜', '2015-12-09 23:21:25'),
(32, 1, 1, '阿斯顿啊啊啊', '/SZTZ/Uploads/10000/20151209/207fd091ca4a1b8e2543a3431c17651e.0x.pdf', '阿姆斯特朗炮洒洒梦似透绿', '11', '1.0', '不通过', '2015-12-09 22:54:51', '学院教指委', '2015-12-11 00:03:34'),
(33, 1, 1, '啊啊啊啊', '/SZTZ/Uploads/10000/20151209/207fd091ca4a1b8e2543a3431c17651e.0x.pdf', '撒上大', '0111', '1.0', '不通过', '2015-12-09 22:55:01', '学院教指委', '2015-12-11 00:03:23'),
(34, 1, 2, 'aaaa', '/SZTZ/Uploads/10000/20151209/207fd091ca4a1b8e2543a3431c17651e.0x.pdf', 'sd', '111', '0.0', '不通过', '2015-12-09 23:30:11', '学院教指委', '2015-12-10 22:52:09'),
(35, 1, 6, 'qqq', '/SZTZ/Uploads/10000/20151211/8ca0ac62d13579dade93fb60e1a84d24.0x.pdf', '2311', '无', '0.5', '已通过', '2015-12-11 23:48:19', '韩娜', '2015-12-11 23:49:27'),
(36, 1, 6, 'as', '/SZTZ/Uploads/10000/20151211/8ca0ac62d13579dade93fb60e1a84d24.0x.pdf', 'aasda', '无', '0.5', '已通过', '2015-12-11 23:48:28', '韩娜', '2015-12-11 23:49:39'),
(37, 1, 6, '1231', '/SZTZ/Uploads/10000/20151211/8ca0ac62d13579dade93fb60e1a84d24.0x.pdf', 'esafaa', '无', '1.0', '已通过', '2015-12-11 23:48:37', '李玉敏', '2015-12-11 23:49:23'),
(38, 2, 6, 'AAAA', '/SZTZ/Uploads/1000/20151212/207fd091ca4a1b8e2543a3431c17651e.0x.pdf', '学生工作处录入', '', '1.5', '已通过', '2015-12-12 09:58:24', '学生工作处', '2015-12-12 09:58:24'),
(40, 4, 6, 'AA', '/SZTZ/Uploads/1000/20151212/207fd091ca4a1b8e2543a3431c17651e.0x.pdf', '学生工作处录入', '', '1.0', '已通过', '2015-12-12 10:06:03', '学生工作处', '2015-12-12 10:06:03'),
(41, 1, 6, '1AAV', '/SZTZ/Uploads/test/default.pdf', '学生工作处录入', '无', '0.5', '已通过', '2015-12-12 11:56:00', '学生工作处', '2015-12-12 11:56:00'),
(42, 2, 6, '2AAV', '/SZTZ/Uploads/test/default.pdf', '学生工作处录入', '无', '0.5', '已通过', '2015-12-12 11:56:00', '学生工作处', '2015-12-12 11:56:00'),
(43, 3, 6, '3AAV', '/SZTZ/Uploads/test/default.pdf', '学生工作处录入', '无', '0.5', '已通过', '2015-12-12 11:56:00', '学生工作处', '2015-12-12 11:56:00'),
(44, 4, 6, '4AAV', '/SZTZ/Uploads/test/default.pdf', '学生工作处录入', '无', '0.5', '已通过', '2015-12-12 11:56:00', '学生工作处', '2015-12-12 11:56:00'),
(45, 5, 6, '5AAV', '/SZTZ/Uploads/test/default.pdf', '学生工作处录入', '无', '0.5', '已通过', '2015-12-12 11:56:00', '学生工作处', '2015-12-12 11:56:00'),
(46, 6, 6, '6AAV', '/SZTZ/Uploads/test/default.pdf', '学生工作处录入', '无', '0.5', '已通过', '2015-12-12 11:56:01', '学生工作处', '2015-12-12 11:56:01'),
(47, 7, 6, '7AAV', '/SZTZ/Uploads/test/default.pdf', '学生工作处录入', '无', '0.5', '已通过', '2015-12-12 11:56:01', '学生工作处', '2015-12-12 11:56:01'),
(48, 8, 6, '8AAV', '/SZTZ/Uploads/test/default.pdf', '学生工作处录入', '无', '0.5', '已通过', '2015-12-12 11:56:01', '学生工作处', '2015-12-12 11:56:01'),
(49, 9, 6, '9AAV', '/SZTZ/Uploads/test/default.pdf', '学生工作处录入', '无', '0.5', '已通过', '2015-12-12 11:56:01', '学生工作处', '2015-12-12 11:56:01'),
(50, 10, 6, '10AAV', '/SZTZ/Uploads/test/default.pdf', '学生工作处录入', '无', '0.5', '已通过', '2015-12-12 11:56:01', '学生工作处', '2015-12-12 11:56:01'),
(51, 11, 6, '11AAV', '/SZTZ/Uploads/test/default.pdf', '学生工作处录入', '无', '0.5', '已通过', '2015-12-12 11:56:01', '学生工作处', '2015-12-12 11:56:01'),
(52, 12, 6, '12AAV', '/SZTZ/Uploads/test/default.pdf', '学生工作处录入', '无', '0.5', '已通过', '2015-12-12 11:56:01', '学生工作处', '2015-12-12 11:56:01'),
(54, 1, 3, '啊啊啊啊', '/SZTZ/Uploads/10000/20151212/8ca0ac62d13579dade93fb60e1a84d24.0x.pdf', 'sada', '', '0.0', '待审批', '2015-12-12 14:06:34', '', '0000-00-00 00:00:00'),
(56, 1, 1, 'bbb', '/2012/10000/20160330/default.pdf', 'bbb', '', '0.0', '已通过', '2016-03-30 10:34:38', 'aaa', '2016-03-30 10:34:38'),
(57, 1, 1, 'bbb', '/2012/10000/20160330/default.pdf', 'aa', 'aa', '0.0', '已通过', '2016-03-30 10:34:55', 'aa', '2016-03-30 10:34:55'),
(65, 1, 1, 'sdas', '/2012/10000/20160330/default.pdf', 'aaas', '', '0.0', '待审批', '2016-03-30 13:16:18', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `t_student`
--

CREATE TABLE IF NOT EXISTS `t_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `user_id` int(11) NOT NULL COMMENT '学号',
  `name` varchar(32) NOT NULL COMMENT '名称',
  `clazz` varchar(8) NOT NULL COMMENT '班别',
  `grade` varchar(16) NOT NULL COMMENT '年级',
  `profession` varchar(32) NOT NULL COMMENT '专业',
  `mobile` varchar(16) NOT NULL COMMENT '联络方式',
  `required_point` decimal(4,1) NOT NULL DEFAULT '0.0' COMMENT '所需学分',
  `updated_count` tinyint(4) NOT NULL DEFAULT '0' COMMENT '更新数量',
  `feedback_count` tinyint(4) NOT NULL DEFAULT '0' COMMENT '反馈数量',
  `created_by` varchar(32) NOT NULL COMMENT '创建人',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='学生表' AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `t_student`
--

INSERT INTO `t_student` (`id`, `user_id`, `name`, `clazz`, `grade`, `profession`, `mobile`, `required_point`, `updated_count`, `feedback_count`, `created_by`, `created_at`) VALUES
(1, 10000, '韩永豪', '1', '2012', '信息管理与信息系统', '13600000000', '8.0', 2, 0, 'H', '2015-12-01 13:30:17'),
(2, 1201, '学生A', '1', '2012', '信息管理与信息系统', '110', '8.0', 0, 0, 'H', '0000-00-00 00:00:00'),
(3, 1202, '学生B', '1', '2012', '国际经济与贸易', '110', '8.0', 0, 0, '', '0000-00-00 00:00:00'),
(4, 1203, '学生C', '2', '2012', '信息管理与信息系统', '110', '8.0', 0, 0, '', '0000-00-00 00:00:00'),
(5, 1204, '学生D', '2', '2012', '公共事业管理', '110', '8.0', 0, 0, '', '0000-00-00 00:00:00'),
(6, 1301, '学生E', '2', '2013', '信息管理与信息系统', '110', '9.0', 0, 0, '', '0000-00-00 00:00:00'),
(7, 1302, '学生F', '2', '2013', '工程管理', '110', '9.0', 0, 0, '', '0000-00-00 00:00:00'),
(8, 1401, '学生G', '3', '2014', '信息管理与信息系统', '110', '9.0', 0, 0, '', '0000-00-00 00:00:00'),
(9, 1402, '学生H', '3', '2014', '信息管理与信息系统', '110', '9.0', 0, 0, '', '0000-00-00 00:00:00'),
(10, 1403, '学生I', '3', '2014', '市场营销', '110', '9.0', 0, 0, '', '0000-00-00 00:00:00'),
(11, 1501, '学生J', '3', '2015', '市场营销', '110', '9.0', 0, 0, '', '0000-00-00 00:00:00'),
(12, 1502, '学生K', '3', '2015', '信息管理与信息系统', '110', '9.0', 0, 0, '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `t_student_teacher`
--

CREATE TABLE IF NOT EXISTS `t_student_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `student_id` int(11) NOT NULL COMMENT '教师ID',
  `teacher_id` int(11) NOT NULL COMMENT '学生ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='学生老师关系表' AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `t_student_teacher`
--

INSERT INTO `t_student_teacher` (`id`, `student_id`, `teacher_id`) VALUES
(1, 10000, 10),
(2, 1201, 10),
(3, 1202, 10),
(4, 1203, 10),
(5, 1204, 10),
(6, 1301, 10),
(7, 1302, 10),
(8, 1401, 10),
(9, 1402, 10),
(10, 1403, 10),
(11, 1501, 10),
(12, 1502, 10);

-- --------------------------------------------------------

--
-- 表的结构 `t_teacher`
--

CREATE TABLE IF NOT EXISTS `t_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `user_id` int(11) NOT NULL COMMENT '工号',
  `name` varchar(32) NOT NULL COMMENT '名称',
  `mobile` varchar(16) NOT NULL COMMENT '联络方式',
  `feedback_count` tinyint(4) NOT NULL COMMENT '反馈数量（默认为0）',
  `created_by` varchar(32) NOT NULL COMMENT '创建人',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='教师表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `t_teacher`
--

INSERT INTO `t_teacher` (`id`, `user_id`, `name`, `mobile`, `feedback_count`, `created_by`, `created_at`) VALUES
(1, 10, '李玉敏', '13600000000', 0, 'H', '2015-12-02 18:18:10'),
(2, 11, '韩娜', '13600000000', 0, 'H', '2015-12-09 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `t_type`
--

CREATE TABLE IF NOT EXISTS `t_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `name` varchar(32) NOT NULL COMMENT '名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='素质拓展类型表' AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `t_type`
--

INSERT INTO `t_type` (`id`, `name`) VALUES
(1, '学科竞赛'),
(2, '科研活动'),
(3, '职业技能'),
(4, '境内外交流研习'),
(5, '社团文体竞赛'),
(6, '公益劳动'),
(7, '学术讲座'),
(8, '社会实践'),
(9, '发明创造'),
(10, '其他');

-- --------------------------------------------------------

--
-- 表的结构 `t_type_grade_profession`
--

CREATE TABLE IF NOT EXISTS `t_type_grade_profession` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `type_id` int(11) NOT NULL COMMENT '类型',
  `grade` varchar(16) NOT NULL COMMENT '年级',
  `profession` varchar(32) NOT NULL COMMENT '专业',
  `max_point` decimal(4,1) DEFAULT '0.0' COMMENT '最大分值',
  `min_point` decimal(4,1) NOT NULL DEFAULT '0.0' COMMENT '最小分值',
  `property` varchar(16) NOT NULL COMMENT '性质',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='拓展类型&专业&年级表' AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `t_type_grade_profession`
--

INSERT INTO `t_type_grade_profession` (`id`, `type_id`, `grade`, `profession`, `max_point`, `min_point`, `property`) VALUES
(1, 1, '2012', '信息管理与信息系统', '3.0', '0.0', '选修'),
(2, 2, '2012', '信息管理与信息系统', '3.0', '1.0', '必修'),
(3, 3, '2012', '信息管理与信息系统', '4.0', '0.0', '必修'),
(4, 4, '2012', '信息管理与信息系统', '1.0', '0.0', '选修'),
(5, 5, '2012', '信息管理与信息系统', '2.0', '0.0', '选修'),
(6, 6, '2012', '信息管理与信息系统', '2.0', '0.0', '选修'),
(7, 7, '2012', '信息管理与信息系统', '2.0', '0.0', '选修'),
(8, 8, '2012', '信息管理与信息系统', '2.0', '1.0', '必修'),
(9, 9, '2012', '信息管理与信息系统', '2.0', '0.0', '选修'),
(10, 10, '2012', '信息管理与信息系统', '1.0', '0.0', '选修');

-- --------------------------------------------------------

--
-- 表的结构 `t_user`
--

CREATE TABLE IF NOT EXISTS `t_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '学号',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `type` varchar(16) NOT NULL COMMENT '类型表名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=10001 ;

--
-- 转存表中的数据 `t_user`
--

INSERT INTO `t_user` (`id`, `password`, `type`) VALUES
(1, 'admin', 'administrator'),
(10, 'admin', 'teacher'),
(100, 'admin', 'administrator'),
(1000, 'admin', 'administrator'),
(1201, 'admin', 'student'),
(1202, 'admin', 'student'),
(1203, 'admin', 'student'),
(1204, 'admin', 'student'),
(1301, 'admin', 'student'),
(1302, 'admin', 'student'),
(1401, 'admin', 'student'),
(1402, 'admin', 'student'),
(1403, 'admin', 'student'),
(1501, 'admin', 'student'),
(1502, 'admin', 'student'),
(10000, '21232f297a57a5a743894a0e4a801fc3', 'student');

-- --------------------------------------------------------

--
-- 表的结构 `t_user_control`
--

CREATE TABLE IF NOT EXISTS `t_user_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(32) NOT NULL COMMENT '操作名',
  `user_ip` varchar(32) NOT NULL COMMENT '客户端IP',
  `created_by` varchar(32) NOT NULL COMMENT '创建人',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户操作记录表' AUTO_INCREMENT=77 ;

--
-- 转存表中的数据 `t_user_control`
--

INSERT INTO `t_user_control` (`id`, `user_id`, `name`, `user_ip`, `created_by`, `created_at`) VALUES
(1, 10, '登录系统', '127.0.0.1', '韩娜', '2015-12-12 23:34:13'),
(2, 1, '登录系统', '127.0.0.1', '超级管理员', '2015-12-12 23:35:46'),
(3, 1, '登录系统', '127.0.0.1', '超级管理员', '2015-12-12 23:46:41'),
(4, 1, '登录系统', '127.0.0.1', '超级管理员', '2015-12-13 00:26:31'),
(5, 1, '登录系统', '127.0.0.1', '超级管理员', '2015-12-13 00:51:37'),
(6, 10000, '登录系统', '127.0.0.1 ', '韩永豪', '2016-03-01 07:57:28'),
(7, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-25 21:04:29'),
(8, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-25 21:04:42'),
(9, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-25 21:06:15'),
(10, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-25 21:13:14'),
(11, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-25 21:14:06'),
(12, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 08:52:11'),
(13, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 09:09:05'),
(14, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 10:01:59'),
(15, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 11:02:40'),
(16, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 12:02:38'),
(17, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 13:08:07'),
(18, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 13:10:25'),
(19, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 13:23:31'),
(20, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 14:14:24'),
(21, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 17:16:40'),
(22, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 19:46:20'),
(23, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 20:24:55'),
(24, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 20:55:32'),
(25, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 21:59:51'),
(26, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-26 23:02:23'),
(27, 10000, '登录系统', '::1', '韩永豪', '2016-03-26 23:29:48'),
(28, 10000, '登录系统', '::1', '韩永豪', '2016-03-27 10:45:37'),
(29, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-27 10:57:40'),
(30, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-27 13:21:23'),
(31, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-27 14:23:22'),
(32, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-27 14:27:28'),
(33, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-27 15:24:40'),
(34, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-27 16:24:50'),
(35, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-27 18:27:04'),
(36, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-27 19:48:55'),
(37, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-27 20:50:01'),
(38, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-27 21:50:25'),
(39, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-27 23:09:53'),
(40, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-28 08:50:40'),
(41, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-28 09:39:49'),
(42, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-28 10:51:58'),
(43, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-28 11:53:23'),
(44, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-28 12:59:04'),
(45, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-28 14:08:23'),
(46, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-28 15:26:04'),
(47, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-28 15:37:31'),
(48, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-28 16:59:07'),
(49, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-28 19:27:52'),
(50, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-28 22:06:07'),
(51, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-29 08:22:30'),
(52, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-29 13:25:35'),
(53, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-29 14:30:20'),
(54, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-29 15:30:41'),
(55, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-29 19:33:15'),
(56, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-29 20:33:32'),
(57, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-29 21:48:39'),
(58, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-29 22:48:58'),
(59, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-30 08:12:17'),
(60, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-30 09:12:26'),
(61, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-30 10:31:51'),
(62, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-30 13:04:53'),
(63, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-30 14:14:46'),
(64, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-30 15:24:55'),
(65, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-30 15:32:57'),
(66, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-30 16:41:37'),
(67, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-30 19:26:25'),
(68, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-30 21:21:38'),
(69, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-30 22:32:23'),
(70, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-30 23:32:44'),
(71, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-31 06:55:15'),
(72, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-31 19:39:29'),
(73, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-31 20:55:31'),
(74, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-03-31 22:39:49'),
(75, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-04-01 11:48:09'),
(76, 10000, '登录系统', '127.0.0.1', '韩永豪', '2016-04-01 14:02:22');

-- --------------------------------------------------------

--
-- 表的结构 `t_user_message`
--

CREATE TABLE IF NOT EXISTS `t_user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `record_id` int(11) NOT NULL COMMENT '记录ID',
  `title` varchar(64) NOT NULL COMMENT '标题',
  `content` varchar(1024) NOT NULL COMMENT '内容',
  `type` varchar(32) NOT NULL COMMENT '消息类型',
  `created_by` varchar(32) NOT NULL COMMENT '创建人',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户消息表' AUTO_INCREMENT=42 ;

--
-- 转存表中的数据 `t_user_message`
--

INSERT INTO `t_user_message` (`id`, `user_id`, `record_id`, `title`, `content`, `type`, `created_by`, `created_at`) VALUES
(22, 10000, 31, '系统通知', '你的<strong class=''color-pink''>1饿阿萨斯</strong>已被韩娜审批完毕，结果为<strong class=''color-pink''>不通过</strong>。<br/><strong>反馈建议：</strong>阿阿萨', '导师消息', '韩娜', '2015-12-09 23:21:25'),
(23, 10000, 30, '系统通知', '你的<strong class=''color-pink''>ADAS</strong>已被韩娜审批完毕，结果为<strong class=''color-pink''>不通过</strong>。<br/><strong>反馈建议：</strong>aqaa ', '导师消息', '韩娜', '2015-12-09 23:29:36'),
(26, 10000, 26, '系统通知', '你的<strong class=''color-pink''>ABA</strong>已被韩娜审批完毕，结果为<strong class=''color-pink''>不通过</strong>。<br/><strong>反馈建议：</strong>aa', '导师消息', '韩娜', '2015-12-09 23:31:16'),
(30, 10000, 34, '系统通知', '你的<strong class=''color-pink''>aaaa</strong>已被学院教指委审批完毕，结果为<strong class=''color-pink''>不通过</strong>。<br/><strong>反馈建议：</strong>111', '导师消息', '学院教指委', '2015-12-10 22:52:09'),
(37, 10000, 33, '系统通知', '你的<strong class=''color-pink''>啊啊啊啊</strong>已被学院教指委审批完毕，结果为<strong class=''color-pink''>不通过</strong>。<br/><strong>反馈建议：</strong>0111', '导师消息', '学院教指委', '2015-12-11 00:03:23'),
(38, 10000, 32, '系统通知', '你的<strong class=''color-pink''>阿斯顿啊啊啊</strong>已被学院教指委审批完毕，结果为<strong class=''color-pink''>不通过</strong>。<br/><strong>反馈建议：</strong>11', '导师消息', '学院教指委', '2015-12-11 00:03:34'),
(39, 10000, 37, '系统通知', '你的<strong class=''color-pink''>1231</strong>已被韩娜审批完毕，结果为<strong class=''color-pink''>已通过</strong>，得分：<strong class=''color-pink''>1</strong>分。', '导师消息', '韩娜', '2015-12-11 23:49:23'),
(40, 10000, 35, '系统通知', '你的<strong class=''color-pink''>qqq</strong>已被韩娜审批完毕，结果为<strong class=''color-pink''>已通过</strong>，得分：<strong class=''color-pink''>0.5</strong>分。', '导师消息', '韩娜', '2015-12-11 23:49:27'),
(41, 10000, 36, '系统通知', '你的<strong class=''color-pink''>as</strong>已被韩娜审批完毕，结果为<strong class=''color-pink''>已通过</strong>，得分：<strong class=''color-pink''>0.5</strong>分。', '导师消息', '韩娜', '2015-12-11 23:49:39');

-- --------------------------------------------------------

--
-- 表的结构 `t_user_role`
--

CREATE TABLE IF NOT EXISTS `t_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识符',
  `role` varchar(32) NOT NULL COMMENT '角色',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户角色表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `t_user_role`
--

INSERT INTO `t_user_role` (`id`, `role`, `user_id`) VALUES
(1, 'superAdmin', 1),
(2, 'teacherAdmin', 100),
(3, 'studentAdmin', 1000);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
