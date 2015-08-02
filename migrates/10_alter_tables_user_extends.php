ALTER TABLE `user_extends_brides`
	CHANGE COLUMN `wedding_date` `date_wedding` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Дата свадьбы' AFTER `avatar`;
ALTER TABLE `user_extends_brides_history`
	CHANGE COLUMN `wedding_date` `date_wedding` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Дата свадьбы' AFTER `avatar`;
