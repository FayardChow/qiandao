# Host: localhost  (Version 5.5.53)
# Date: 2018-04-02 19:11:59
# Generator: MySQL-Front 6.0  (Build 2.20)


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
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='事件属性';

#
# Data for table "item"
#

/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` VALUES (1,'抽烟',10,'500wn'),(26,'吃饭',30,'500wn'),(27,'厕所',20,'500wn'),(28,'抽烟',10,'zfxx'),(29,'吃饭',30,'zfxx'),(30,'厕所',20,'zfxx'),(31,'吃饭',30,'500xx'),(32,'厕所',20,'500xx'),(33,'抽烟',10,'500xx');
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
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

#
# Data for table "log"
#

/*!40000 ALTER TABLE `log` DISABLE KEYS */;
INSERT INTO `log` VALUES (21,3468935316,'火花','吃饭+厕所','2018-04-02 19:01:33','2018-04-02 19:01:36',0,0,'500wn'),(22,3468935316,'火花','抽烟','2018-04-02 19:03:06','2018-04-02 19:05:54',3,0,'500wn');
/*!40000 ALTER TABLE `log` ENABLE KEYS */;

#
# Structure for table "setting"
#

DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) DEFAULT NULL COMMENT '每日用时',
  `company` varchar(255) DEFAULT NULL COMMENT '所属公司',
  `max_time` int(11) DEFAULT '40' COMMENT '每次出去最大时间',
  `max_times` int(11) DEFAULT '8' COMMENT '每天最多出去次数',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='网站其他设置';

#
# Data for table "setting"
#

/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
INSERT INTO `setting` VALUES (1,120,'500wn',50,10),(8,120,'zfxx',50,10),(9,120,'500xx',50,8);
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
  `pass` varchar(11) DEFAULT '123' COMMENT '密码',
  `md5str` varchar(255) DEFAULT NULL COMMENT 'md5字符串，用于账户验证',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=utf8 COMMENT='员工信息';

#
# Data for table "stuff"
#

/*!40000 ALTER TABLE `stuff` DISABLE KEYS */;
INSERT INTO `stuff` VALUES (1,3468935316,'火花','500wn','23456',''),(219,2437383150,'左宇','500wn','123',NULL),(220,157359239,'阿祖','500wn','123',NULL),(221,3028227964,'小婵','500wn','123',NULL),(222,2943398523,'开心','500wn','123',NULL),(223,1093880289,'小广','500wn','123',NULL),(224,3588757786,'小胖','500wn','123',NULL),(225,1656189072,'小静','500wn','123',''),(226,867927557,'小磊','500wn','123',NULL),(227,759368898,'阿肖','500wn','123',NULL),(228,2743953812,'老二','500wn','123',NULL),(229,3592914097,'娜娜','500wn','123',NULL),(230,1831488128,'小强','500wn','123',NULL),(231,3391215900,'阿永','500wn','123',NULL),(232,1875429283,'阿言','500wn','123',NULL),(233,3477036585,'小涛','500wn','123',NULL),(234,2215784159,'甜甜','500wn','123',NULL),(235,3458351135,'阿忠','500wn','123',NULL),(236,1291268361,'小万','500wn','123',NULL),(237,982682334,'四组蒙恩','zfxx','123',NULL),(238,3238317247,'五组阿玄','zfxx','123',NULL),(239,569731348,'一组小雅','zfxx','123',NULL),(240,153823515,'八组天亿','zfxx','123',NULL),(241,2561351169,'后援小江','zfxx','123',NULL),(242,202786583,'六组小梦','zfxx','123',NULL),(243,513597675,'六组小小','zfxx','123',NULL),(244,819831618,'二组石头','zfxx','123',NULL),(245,482600495,'四组大伟','zfxx','123',NULL),(246,3447746407,'四组西西','zfxx','123',NULL),(247,331698040,'四组夏天','zfxx','123',NULL),(248,483923991,'五组阿琦','zfxx','123',NULL),(249,157606693,'五组小刚','zfxx','123',NULL),(250,927843471,'五组小军','zfxx','123',NULL),(251,1714957603,'一组小广','zfxx','123',NULL),(252,484983491,'一组小梁','zfxx','123',NULL),(253,1279157143,'一组小强','zfxx','123',NULL),(254,846140298,'六组小康','zfxx','123',NULL),(255,329472352,'二组若麟','zfxx','123',NULL),(256,892402823,'二组小敏','zfxx','123',NULL),(257,805013941,'六组小河','zfxx','123',NULL),(258,2604044632,'二组大圣','zfxx','123',NULL),(259,872481081,'四组阿炳','zfxx','123',NULL),(260,3237076486,'三组小王','zfxx','123',NULL),(261,729490604,'七组马宏','zfxx','123',NULL),(262,1711594839,'七组小喜','zfxx','123',NULL),(263,824250913,'七组渐渐','zfxx','123',NULL),(264,805401952,'七组兵仔','zfxx','123',NULL),(265,813021637,'三组小刘','zfxx','123',NULL),(266,963853009,'一组白杨','zfxx','123',NULL),(267,770387460,'三组小志','zfxx','123',NULL),(268,859584927,'六组小群','zfxx','123',NULL),(269,778246449,'新人--安逸','zfxx','123',NULL),(270,205372838,'新人---老三','zfxx','123',NULL),(271,715817410,'新人---小门','zfxx','123',NULL),(272,2290406429,'新人--小果','zfxx','123',NULL),(273,1169044056,'新人---阿昌','zfxx','123',NULL),(274,3465983041,'八组---小天','zfxx','123',NULL),(275,3386998208,'六组--小洲','zfxx','123',NULL),(276,1957146107,'新人---小钰','zfxx','123',NULL),(277,3466412868,'六组---小城','zfxx','123',NULL),(278,2487049864,'新人---三林','zfxx','123',NULL),(279,1970940658,'新人---啊强','zfxx','123',NULL),(280,2421892190,'新人---陆橙','zfxx','123',NULL),(281,3258245043,'六组---中哥','zfxx','123',NULL),(282,2752273033,'新人---老王','zfxx','123',NULL),(283,1276195395,'新人---啊天','zfxx','123',NULL),(284,2899776041,'新人---小煌','zfxx','123',NULL),(285,2915279057,'新人---小林','zfxx','123',NULL),(286,1580326810,'新人---老表','zfxx','123',NULL),(287,1485652563,'新人---啊浩','zfxx','123',NULL),(288,1497386473,'三组---小阳','zfxx','123',NULL),(289,1651376199,'三组---阿成','zfxx','123',NULL),(290,1643473675,'三组---小苏','zfxx','123',NULL),(291,1697830433,'新人---阿俊','zfxx','123',NULL),(292,2302897584,'新人---东北','zfxx','123',NULL),(293,2755927845,'新人 ---小涛','zfxx','123',NULL),(294,2802715510,'新人---小蕊','zfxx','123',NULL),(295,921257541,'阿峰','500xx','123',NULL),(296,349474686,'阿乐','500xx','123',NULL),(297,649082429,'阿南','500xx','123',NULL),(298,873481057,'阿忠','500xx','123',NULL),(299,846528535,'阿B','500xx','123',NULL),(300,912079452,'馒头','500xx','123',NULL),(301,653542035,'其乐','500xx','123',NULL),(302,995112954,'小程','500xx','123',NULL),(303,876241899,'小虎','500xx','123',NULL),(304,921512421,'小五','500xx','123',NULL),(305,823857719,'小娴','500xx','123',NULL),(306,764361478,'小汪','500xx','123',NULL),(307,524012772,'小宝','500xx','123',NULL),(308,851055124,'小帅','500xx','123',NULL),(309,924826422,'阿彪','500xx','123',NULL),(310,983870812,'阿熊','500xx','123',NULL),(311,876425815,'小马','500xx','123',NULL),(312,635923216,'大帅','500xx','123',NULL),(313,329619108,'小猪','500xx','123',NULL),(314,916212590,'阿泰','500xx','123',NULL),(315,975728358,'四哇','500xx','123',NULL),(316,719160388,'小林','500xx','123',NULL),(317,951987304,'小孙','500xx','123',NULL);
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='每日用时记录';

#
# Data for table "time"
#

/*!40000 ALTER TABLE `time` DISABLE KEYS */;
INSERT INTO `time` VALUES (1,'火花',3468935316,82,18,'2018-04-02',0,'500'),(2,'火花',3468935316,3,2,'2018-04-02',0,'500wn');
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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='管理员';

#
# Data for table "user"
#

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'500xx','168558','500xx'),(7,'zfxx','168558','zfxx'),(8,'500wn','168558','500wn');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
