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
 * @property string first_name
 * @property string last_name
 * @property string phone
 */
class UserExtendHistory extends Model
{

	const NOT_APPROVED_STATUS = 1;
	const REWRITED_STATUS = 2;
	const NOT_MODERATE_STATUS = 3;
	const IS_APPROVED_STATUS = 4;
	const IS_REJECTED_STATUS = 5;
	public static $table = 'user_extends_history';

}
