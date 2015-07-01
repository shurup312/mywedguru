<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 27.03.2015
 * Time: 13:47
 */
namespace system\core\base;

use system\core\helpers\StringHelper;

abstract class Widget extends View
{

	protected $id;
	protected  $autoIdPrefix = 'w';

	abstract protected function run();

	private function generateId()
	{
		$this->id = $this->autoIdPrefix.'_'.StringHelper::randomString(4, '1234567890');
	}

	public function widget(array $params = [])
	{
		foreach($params as $name=>$val)
		{
			$this->$name = $val;
		}
		/**
		 * TODO: new self
		 */

		$this->generateId();

		$this->begin();
		echo $this->run();
		$this->end();
	}
	protected function begin()
	{
		echo '<div id="'.$this->id.'">';
	}
	protected function end()
	{
		echo '</div>';
	}
}

?>
