<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 02.08.2015
 * Time: 19:05
 */
namespace webapp\modules\cabinet\models;

use Exception;
use system\core\App;
use system\core\Model;

class UserExtendsBase extends Model
{
	const USER_BRIDE = 1;
	const USER_PHOTOGRAPGER = 2;

	public static function getCurrentModel()
	{
		return static::getModelNameByType(App::get('user')->user_type);
	}

	public static function getPrefixByType($typeID)
	{
		switch($typeID){
			case self::USER_BRIDE :
				return 'brides';
			case self::USER_PHOTOGRAPGER :
				return 'photographers';
			default:
				throw new Exception('Не найден префикс для указанного типа.');
		}
	}
}
