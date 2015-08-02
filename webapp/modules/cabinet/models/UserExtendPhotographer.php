<?php
namespace webapp\modules\cabinet\models;
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 01.07.2015
 * Time: 15:36
 */

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
class UserExtendPhotographer extends UserExtend
{
	public static $table = 'user_extends_photographers';
}
