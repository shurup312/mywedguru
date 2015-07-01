<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 06.05.15
 * Time: 20:53
 */


namespace system\core\base;

use system\core\Controller;

class Action extends Object{
	/**
	 * @var Controller $owner
	 */
	protected $owner;

	public function setOwner(Controller $owner)
	{
		$this->owner = $owner;
	}
}
