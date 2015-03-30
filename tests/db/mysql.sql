DROP TABLE IF EXISTS `user_group`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `group`;

CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

INSERT INTO `user` VALUES (1,'user1@mail.com'),(2,'user2@mail.com');

CREATE TABLE `group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

INSERT INTO `group` VALUES (1,'admin'),(2,'reporter'),(3,'manager'),(4,'developer');

CREATE TABLE `user_group` (
  `user_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `fk_user_group_user_id` (`user_id`),
  KEY `fk_user_group_group_id` (`group_id`),
  CONSTRAINT `fk_user_group_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_group_group_id` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

INSERT INTO `user_group` VALUES (2,2),(2,3);