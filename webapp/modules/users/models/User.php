<?php
namespace webapp\modules\users\models;

use system\core\Model;

/**
 * Class Users
 */
class User extends Model
{
	const SUPER_RIGHTS = 0b00001;
	const ADMIN_RIGHTS = 0b00010;
	const USER_RIGHTS = 0b00100;
	const GUEST_RIGHTS = 0b01000;

	const SITE_VK = 1;

	const STATUS_SOCIAL_APPROVE = 1;
	const SITE_OK = 2;
	public static $table = 'users';
}
