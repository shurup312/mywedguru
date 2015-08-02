ALTER TABLE `user_extends_brides_history`
	CHANGE COLUMN `fiande_first_name` `fiance_first_name` VARCHAR(128) NULL DEFAULT NULL COMMENT 'Фамилия жениха' AFTER `first_name`,
	CHANGE COLUMN `fiande_last_name` `fiance_last_name` VARCHAR(128) NULL DEFAULT NULL COMMENT 'Имя жениха' AFTER `last_name`;

ALTER TABLE `user_extends_brides`
	CHANGE COLUMN `fiande_first_name` `fiance_first_name` VARCHAR(128) NULL DEFAULT NULL COMMENT 'Фамилия жениха' AFTER `first_name`,
	CHANGE COLUMN `fiande_last_name` `fiance_last_name` VARCHAR(128) NULL DEFAULT NULL COMMENT 'Имя жениха' AFTER `last_name`;
