<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 24.03.2015
 * Time: 9:56
 */

namespace system\core;

class Validation
{

	public static function isEmail($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public static function allowChars($string, $pattern)
	{
		if (preg_match($pattern, $string)) {
			return true;
		} else {
			return false;
}
	}
	public static function allowLetters($string)
	{
		return self::allowChars($string, '/^[a-zA-Zа-яА-ЯёЁ ]+$/u');
	}

	public static function isPhone($string)
	{
		$pattern = '/^[0-9()\-\+ ]+$/';
		if (preg_match($pattern, $string)) {
			if(strlen($string) >=7)
			{
				return true;
			}else{
				return false;
			}
		} else {
			return false;
		}
	}
}

?>
