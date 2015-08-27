<?php
namespace frontend\models\abstracts;

use frontend\models\UserExtendsBride;
use frontend\models\UserExtendsPhotographer;
use frontend\models\UserType;
use yii\base\ErrorException;
use yii\base\Object;

/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 11.08.2015
 * Time: 0:08
 */
class UserFactory extends Object
{

	/**
	 * @return UserExtendsBride|UserExtendsPhotographer
	 * @throws ErrorException
	 */
	public static function getCurrentModel()
	{
		return static::getModelByType(\yii::$app->getUser()->identity->user_type);
	}

	public static function getModelByType($typeID)
	{
		switch($typeID){
			case UserType::USER_BRIDE:
				return new UserExtendsBride();
			case UserType::USER_PHOTOGRAPGER:
				return new UserExtendsPhotographer();
			default:
				throw new ErrorException('Не найдена модель для типа пользователя '.$typeID.'.');
		}
	}

}
