<?php
namespace webapp\modules\cabinet\models;
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 01.07.2015
 * Time: 15:36
 */
use Exception;
use system\core\App;

/**
 * Class UserExtend
 * @property integer id
 * @property integer user_id
 * @property string first_name
 * @property string last_name
 * @property string phone
 * @property string avatar
 * @property string work_phone
 * @property string passport
 * @property string passport_ext
 */
class UserExtendHistory extends UserExtendsAbstract
{
	const NOT_APPROVED_STATUS = 1;
	const REWRITED_STATUS = 2;
	const NOT_MODERATE_STATUS = 3;
	const IS_APPROVED_STATUS = 4;
	const IS_REJECTED_STATUS = 5;

	public static function getModelNameByType($typeID)
	{
		switch($typeID){
			case self::USER_BRIDE :
				return UserExtendBrideHistory::factory();
			case self::USER_PHOTOGRAPGER :
				return UserExtendPhotographerHistory::factory();
			default:
				throw new Exception('Не найдена модель для истории пользователя типа '.$typeID);
		}
	}
}
