<?php
namespace webapp\modules\auth\models;

use system\core\base\Object;

/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 09.07.2015
 * Time: 18:13
 */
class UserKind extends Object
{

	const BRIDE_KIND = 1;
	const PROTOGRAPGER_KIND = 2;
	public static $kinds = [
		self::BRIDE_KIND        => 'Невеста',
		self::PROTOGRAPGER_KIND => 'Фотограф',
	];
}
