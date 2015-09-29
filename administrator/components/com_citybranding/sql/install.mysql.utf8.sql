CREATE TABLE IF NOT EXISTS `#__citybranding_pois` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`title` VARCHAR(255)  NOT NULL ,
`stepid` TEXT NOT NULL ,
`catid` INT(11)  NOT NULL ,
`regnum` VARCHAR(128)  NOT NULL ,
`regdate` DATETIME NOT NULL ,
`responsible` TEXT  NOT NULL ,
`description` TEXT NOT NULL ,
`address` TEXT NOT NULL ,
`latitude` VARCHAR(255)  NOT NULL ,
`longitude` VARCHAR(255)  NOT NULL ,
`photo` TEXT  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`moderation` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`access` INT(11)  NOT NULL ,
`created` DATETIME NOT NULL ,
`updated` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`updated_by` INT(11)  NOT NULL ,
`language` VARCHAR(255)  NOT NULL ,
`hits` MEDIUMINT(8)  NOT NULL ,
`note` VARCHAR(512)  NOT NULL ,
`extra` TEXT  NOT NULL ,
`votes` MEDIUMINT(8)  NOT NULL ,
`modality` SMALLINT(6)  NOT NULL ,
`subgroup` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__citybranding_steps` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`title` VARCHAR(255)  NOT NULL ,
`description` TEXT NOT NULL ,
`stepcolor` VARCHAR(10) NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created` DATETIME NOT NULL ,
`updated` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`updated_by` INT(11)  NOT NULL ,
`language` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__citybranding_keys` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`title` VARCHAR(255)  NOT NULL ,
`skey` VARCHAR(255)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created` DATETIME NOT NULL ,
`updated` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`updated_by` INT(11)  NOT NULL ,
`quota` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__citybranding_log` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`poiid` INT NOT NULL ,
`stepid` TEXT NOT NULL ,
`description` TEXT NOT NULL ,
`action` VARCHAR(512) NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created` DATETIME NOT NULL ,
`updated` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`updated_by` INT(11)  NOT NULL ,
`language` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__citybranding_votes` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`poiid` INT NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created` DATETIME NOT NULL ,
`updated` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`updated_by` INT(11)  NOT NULL ,
`modality` SMALLINT(6)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__citybranding_comments` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`poiid` INT NOT NULL ,
`parentid` INT(11)  NOT NULL ,
`description` TEXT NOT NULL ,
`photo` VARCHAR(2048)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created` DATETIME NOT NULL ,
`updated` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`updated_by` INT(11)  NOT NULL ,
`language` VARCHAR(255)  NOT NULL ,
`modality` SMALLINT(6)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__citybranding_tokens` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `json_size` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `method` VARCHAR(7)  NOT NULL ,
  `token` VARCHAR(512)  NOT NULL ,
  `unixtime` VARCHAR(12) NOT NULL ,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;