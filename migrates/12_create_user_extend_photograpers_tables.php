CREATE TABLE IF NOT EXISTS `user_extends_photographers` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`user_id` int(11) NOT NULL,
	`first_name` varchar(128) DEFAULT NULL COMMENT 'Фамилия',
	`last_name` varchar(128) DEFAULT NULL COMMENT 'Имя',
	`studio_name` varchar(128) DEFAULT NULL COMMENT 'Название студии',
	`site_name` varchar(128) DEFAULT NULL COMMENT 'Сайт',
	`e-mail` varchar(128) DEFAULT NULL COMMENT 'e-mail',
	`phone` varchar(128) DEFAULT NULL COMMENT 'phone',
	`avatar` varchar(64) DEFAULT NULL COMMENT 'Аватарка',
	`date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
	`date_deleted` timestamp NULL DEFAULT NULL COMMENT 'Дата удаления',
	PRIMARY KEY (`id`),
	KEY `user_id` (`user_id`),
	CONSTRAINT `user_extends_photographers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_extends_photographers_history` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`user_id` int(11) NOT NULL,
	`action_user_id` int(11) NOT NULL,
	`first_name` varchar(128) DEFAULT NULL COMMENT 'Фамилия',
	`last_name` varchar(128) DEFAULT NULL COMMENT 'Имя',
	`studio_name` varchar(128) DEFAULT NULL COMMENT 'Название студии',
	`site_name` varchar(128) DEFAULT NULL COMMENT 'Сайт',
	`e-mail` varchar(128) DEFAULT NULL COMMENT 'e-mail',
	`phone` varchar(128) DEFAULT NULL COMMENT 'phone',
	`avatar` varchar(64) DEFAULT NULL COMMENT 'Аватарка',
	`status` int(11) DEFAULT NULL COMMENT 'Статус записи - на одобрении, архивная или отклоненная',
	`date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
	`date_deleted` timestamp NULL DEFAULT NULL COMMENT 'Дата удаления',
	PRIMARY KEY (`id`),
	KEY `user_id` (`user_id`),
	CONSTRAINT `user_extends_photographers_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
