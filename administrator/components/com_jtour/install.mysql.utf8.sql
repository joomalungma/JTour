CREATE TABLE IF NOT EXISTS `#__jtour_tours`(
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `duration` INT(11) UNSIGNED NOT NULL,
  `currencyId` INT(2) UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
   `price` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `excursions` TEXT DEFAULT NULL,
  `created` DATETIME NOT NULL,
  `modified` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `checked_out` INT(11) NOT NULL DEFAULT 0,
  `checked_out_time` DATETIME DEFAULT NULL,
  `ordering` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `published` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `#__jtour_excursions`(
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(10, 0) UNSIGNED NOT NULL,
  `duration`  VARCHAR(30) NOT NULL,
  `workdays` CHAR(100) NOT NULL,
  `schedule` TEXT NOT NULL,
  `created` DATETIME NOT NULL,
  `modified` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `checked_out` INT(11) NOT NULL DEFAULT 0,
  `checked_out_time` DATETIME DEFAULT NULL,
  `ordering` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `published` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `#__jtour_reports`(
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `report` TEXT NOT NULL,
  `price` VARCHAR(255) NOT NULL,
  `status` VARCHAR(255) NOT NULL,
  `create_on` DATETIME NOT NULL,
  `update_on` TIMESTAMP DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8;



CREATE TABLE IF NOT EXISTS `#__jtour_excursion_to_tour` (
  `tour_id` INT(11) UNSIGNED NOT NULL,
  `excursion_id` INT(11) UNSIGNED NOT NULL
)
ENGINE = INNODB
CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `#__jtour_configuration`(
  `name` VARCHAR(255) NOT NULL,
  `value` TEXT NOT NULL,
)
ENGINE = INNODB
CHARACTER SET utf8;

INSERT INTO `#__jtour_configuration` VALUES (`currencyDefault`, ``);