CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rights` int(11) NOT NULL,
  `site` int(11) NOT NULL,
  `socialid` varchar(64) NOT NULL,
  `token` varchar(128) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_deleted` timestamp,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
