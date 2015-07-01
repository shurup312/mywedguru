<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 08.05.15
 * Time: 15:49
 */


namespace system\core\HTML;

use system\core\base\Object;

abstract class Tag extends Object{

	/**
	 * Возвращает html код элемента
	 *
	 * @param array $attributes массив аттрибутов в виде ключ-значение
	 * @param bool|string $content контент внутри тэга, если тэг может закрывающийся
	 *
	 * @return string
	 */
	abstract public function run($attributes, $content = false);
	public function generateAttributes($attributes)
	{
		$result = [];
		foreach ($attributes as $name => $value) {
			$result[] = $name.'="'.$value.'"';
		}
		return implode(' ',$result);
	}
} 
