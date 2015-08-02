ALTER TABLE `users`
	ADD COLUMN `user_type` int NULL DEFAULT NULL AFTER `status`;
