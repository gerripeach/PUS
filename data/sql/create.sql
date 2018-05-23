DROP DATABASE IF EXISTS `pus`;
CREATE DATABASE IF NOT EXISTS `pus` DEFAULT CHARACTER SET utf8;
USE `pus`;

CREATE TABLE `redirects` (
  `name` VARCHAR(9) NOT NULL,
  `url` VARCHAR(10000) NOT NULL,
  `hits` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `hits` (
  `name` VARCHAR(10) NOT NULL,
  `ip` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`name`, `ip`),
  CONSTRAINT `FK_hits_redirects` FOREIGN KEY (`name`) REFERENCES `redirects` (`name`) ON UPDATE CASCADE ON DELETE CASCADE
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;
