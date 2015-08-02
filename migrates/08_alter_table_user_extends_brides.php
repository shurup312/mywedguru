ALTER TABLE `user_extends_brides`
	ADD COLUMN `fiande_first_name` VARCHAR(128) NULL DEFAULT NULL COMMENT 'Фамилия жениха' AFTER `first_name`,
	ADD COLUMN `fiande_last_name` VARCHAR(128) NULL DEFAULT NULL COMMENT 'Имя жениха' AFTER `last_name`,
	ADD COLUMN `wedding_date` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Дата свадьбы' AFTER `avatar`,
	DROP COLUMN `kind`,
	DROP COLUMN `phone`;
