# Host: localhost  (Version: 5.5.53)
# Date: 2018-01-09 15:43:47
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "item"
#

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '事件名称',
  `time` int(11) DEFAULT NULL COMMENT '超时分钟数',
  `company` varchar(255) DEFAULT NULL COMMENT '所属公司',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='事件属性';

#
# Data for table "item"
#

/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` VALUES (1,'厕所',20,'500'),(4,'抽烟',10,'500'),(9,'吃饭',30,'500'),(10,'吃饭',3,'wns'),(11,'厕所',20,'wns'),(12,'抽烟',10,'wns');
/*!40000 ALTER TABLE `item` ENABLE KEYS */;

#
# Structure for table "log"
#

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `qq` bigint(20) DEFAULT NULL COMMENT 'qq号',
  `name` varchar(255) DEFAULT NULL COMMENT '姓名',
  `item` varchar(20) DEFAULT NULL COMMENT '事件',
  `add_time` datetime DEFAULT NULL COMMENT '记录时间',
  `back_time` datetime DEFAULT NULL COMMENT '签回时间',
  `use_time` int(11) DEFAULT NULL COMMENT '使用时间',
  `over_time` int(10) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL COMMENT '所属公司',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

#
# Data for table "log"
#

/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;

#
# Structure for table "setting"
#

DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `group` bigint(20) DEFAULT NULL COMMENT '群号',
  `time` int(11) DEFAULT NULL COMMENT '每日用时',
  `company` varchar(255) DEFAULT NULL COMMENT '所属公司',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='网站其他设置';

#
# Data for table "setting"
#

/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
INSERT INTO `setting` VALUES (1,140198968,120,'500'),(3,655088742,120,'wns'),(4,690524789,120,'zhenzhu');
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;

#
# Structure for table "stuff"
#

DROP TABLE IF EXISTS `stuff`;
CREATE TABLE `stuff` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `qq` bigint(20) DEFAULT NULL COMMENT 'qq号',
  `name` varchar(255) DEFAULT NULL COMMENT '员工名称',
  `company` varchar(255) DEFAULT NULL COMMENT '所属公司',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='员工信息';

#
# Data for table "stuff"
#

/*!40000 ALTER TABLE `stuff` DISABLE KEYS */;
INSERT INTO `stuff` VALUES (1,3468935316,'火花','500'),(2,2437383150,'左宇','500'),(3,157359239,'阿祖','500'),(4,3028227964,'小婵','500'),(5,2943398523,'开心','500'),(6,2175649502,'小广','500'),(7,3588757786,'小胖','500'),(8,1656189072,'小静','500'),(9,867927557,'小磊','500'),(10,759368898,'阿肖','500'),(11,2743953812,'老二','500'),(12,3592914097,'娜娜','500'),(13,1831488128,'小强','500'),(14,2398925046,'阿言','500'),(15,3296950378,'小九','zhenzhu'),(16,2926783348,'小李','zhenzhu'),(17,609345030,'小梁','zhenzhu'),(18,3238317247,'阿玄','zhenzhu'),(19,1279157143,'小强','zhenzhu'),(20,157606693,'小刚','zhenzhu'),(21,3237076486,'小王','zhenzhu'),(22,483923991,'阿琦','zhenzhu'),(23,480656074,'若麟','zhenzhu'),(24,2779794857,'天亿','zhenzhu'),(25,773238008,'小河','zhenzhu'),(26,927843471,'小军','zhenzhu'),(27,912079452,'馒头','zhenzhu'),(28,2859177519,'小喜','zhenzhu'),(29,990297369,'大圣','zhenzhu'),(30,916335091,'小陈','zhenzhu'),(31,524012772,'小宝','zhenzhu'),(32,205571514,'小金','zhenzhu'),(33,203744138,'大伟','zhenzhu'),(34,846528535,'阿b','zhenzhu'),(35,798157299,'阿庄','zhenzhu'),(36,569731348,'小雅','zhenzhu'),(37,349474686,'阿乐','zhenzhu'),(38,876241899,'小虎','zhenzhu'),(39,865798715,'阿峰','zhenzhu'),(40,625136657,'蒙恩','zhenzhu'),(41,610866096,'胖子','zhenzhu'),(42,2834577191,'小伟','zhenzhu'),(43,873481057,'阿忠','zhenzhu'),(44,649082429,'阿南','zhenzhu'),(45,609471151,'阿飞','zhenzhu'),(46,921512421,'小五','zhenzhu'),(47,819831618,'石头','zhenzhu'),(48,3447746407,'西西','zhenzhu'),(49,756089170,'夏天','zhenzhu'),(50,564681612,'阿泰','zhenzhu'),(51,924826422,'阿彪','zhenzhu'),(52,717677244,'小安','zhenzhu'),(53,672657124,'小小','zhenzhu'),(54,202786583,'小梦','zhenzhu'),(55,434575641,'芳姐','zhenzhu'),(56,963853009,'逸龙','zhenzhu'),(57,876193348,'小猪','zhenzhu'),(58,653542035,'其乐','zhenzhu'),(59,2561351169,'刘小江','zhenzhu'),(60,997137390,'阿熊','zhenzhu'),(61,635923216,'大帅','zhenzhu'),(62,924649635,'小丁','zhenzhu'),(63,851055124,'小帅','zhenzhu'),(64,1334942989,'小柒','zhenzhu');
/*!40000 ALTER TABLE `stuff` ENABLE KEYS */;

#
# Structure for table "time"
#

DROP TABLE IF EXISTS `time`;
CREATE TABLE `time` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '姓名',
  `qq` bigint(20) DEFAULT NULL COMMENT 'qq号',
  `use_time` int(11) DEFAULT NULL COMMENT '用时',
  `times` int(11) DEFAULT NULL COMMENT '签到次数',
  `date` date DEFAULT NULL COMMENT '日期',
  `over_time` int(11) DEFAULT NULL COMMENT '超时',
  `company` varchar(255) DEFAULT NULL COMMENT '所属公司',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='每日用时记录';

#
# Data for table "time"
#

/*!40000 ALTER TABLE `time` DISABLE KEYS */;
INSERT INTO `time` VALUES (11,'火花',3468935316,51,NULL,'2018-01-06',0,'wns');
/*!40000 ALTER TABLE `time` ENABLE KEYS */;

#
# Structure for table "user"
#

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '用户名',
  `pass` varchar(255) DEFAULT NULL COMMENT '密码',
  `company` varchar(255) DEFAULT NULL COMMENT '所属公司',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员';

#
# Data for table "user"
#

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin500','168558','500'),(2,'adminwns','168558','wns'),(3,'adminzz','168558','zhenzhu');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
