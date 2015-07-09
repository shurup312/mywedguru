CREATE TABLE IF NOT EXISTS `user_extends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(128) NULL COMMENT 'Фамилия',
  `last_name` varchar(128) NULL COMMENT 'Имя',
  `phone` varchar(64) NULL COMMENT 'Телефон',
  `avatar` varchar(64) NULL COMMENT 'Аватарка',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
  `date_deleted` timestamp NULL DEFAULT NULL COMMENT 'Дата удаления',
  PRIMARY KEY (`id`),
  FOREIGN KEY (user_id) REFERENCES users(id)
	ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8;
