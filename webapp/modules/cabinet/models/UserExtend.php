<?php
namespace webapp\modules\cabinet\models;

/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 01.07.2015
 * Time: 15:36
 */
use system\core\Model;

/**
 * Class UserExtend
 * @property integer id
 * @property string  first_name
 * @property string  last_name
 * @property string  phone
 */
class UserExtend extends Model
{

	protected static $table = 'user_extends';
}
