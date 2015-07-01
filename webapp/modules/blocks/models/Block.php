<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 14.05.15
 * Time: 12:40
 */


namespace webapp\modules\blocks\models;

use system\core\Model;

/**
 * Class Block
 * @property integer id
 * @property string title
 * @property string content
 * @package webapp\modules\blocks\models
 */
class Block extends Model{
	public static $table = 'blocks';
} 
