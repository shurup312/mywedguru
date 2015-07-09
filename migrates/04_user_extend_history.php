CREATE TABLE `user_extends_history` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`user_id` INT(11) NOT NULL,
	`kind` INT(11) NOT NULL,
	`action_user_id` INT(11) NOT NULL,
	`first_name` VARCHAR(128) NULL DEFAULT NULL COMMENT 'Имя',
	`last_name` VARCHAR(128) NULL DEFAULT NULL COMMENT 'Фамилия',
	`phone` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Телефон',
	`work_phone` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Рабочий телефон',
	`passport` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Серия и номер паспорта',
	`passport_ext` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Кем и когда выдан',
	`avatar` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Аватарка',
	`status` INT(11) NULL DEFAULT NULL COMMENT 'Статус записи - на одобрении, архивная или отклоненная',
	`date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
	`date_deleted` TIMESTAMP NULL DEFAULT NULL COMMENT 'Дата удаления',
	PRIMARY KEY (`id`),
	INDEX `user_id` (`user_id`),
	CONSTRAINT `user_extends_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=7;
