CREATE DATABASE IF NOT EXISTS `arvici_test` DEFAULT CHARACTER SET utf8 ;


USE `arvici_test`;


CREATE TABLE IF NOT EXISTS `arvici_test`.`posts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `author` INT NOT NULL,
  `content` MEDIUMTEXT NOT NULL,
  `publishdate` TIMESTAMP NOT NULL,
  `active` INT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`));

