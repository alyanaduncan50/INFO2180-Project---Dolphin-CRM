DROP DATABASE IF EXISTS project;
CREATE DATABASE project;
USE project;

-- Table structure for table `user`
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL auto_increment,
  `firstname` varchar(35) NOT NULL default '',
  `lastname` varchar(35) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `role` varchar(35) NOT NULL default '',
  `created_at` datetime,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4080 DEFAULT CHARSET=utf8mb4;




-- INSERT INTO `user` VALUES (1,'Joseph','Hylton','password','admin@project2.com','Administrator');
-- INSERT INTO `user` (firstname,lastname,password,email,role) VALUES('Joseph','Hylton','password123','admin@project2.com','Administrator',SYSDATETIME());

