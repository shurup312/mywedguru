<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 01.04.15
 * Time: 18:32
 */


namespace system\core\base;

class Behavior extends Object{
	private $owner;

	/**
	 * @return mixed
	 */
	public function getOwner()
	{
		return $this->owner;
	}

	/**
	 * @param mixed $owner
	 */
	public function setOwner($owner)
	{
		$this->owner = $owner;
	}

}
