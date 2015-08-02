<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 02.08.2015
 * Time: 19:05
 */
namespace webapp\modules\cabinet\models;

use system\core\App;
use system\core\Model;

abstract class UserExtendsAbstract extends Model
{
	const USER_BRIDE = 1;
	const USER_PHOTOGRAPGER = 2;

	abstract public static function getModelNameByType($typeId);

	public static function getCurrentModel()
	{
		return self::getModelNameByType(App::get('user')->user_type);
	}
}
