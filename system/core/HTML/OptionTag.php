<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 08.05.15
 * Time: 14:13
 */
namespace system\core\HTML;

class OptionTag extends Tag
{

	/**
	 * Возвращает html код элемента
	 *
	 * @param array       $attributes массив аттрибутов в виде ключ-значение
	 * @param bool|string $content    контент внутри тэга, если тэг может закрывающийся
	 *
	 * @return string
	 */
	public function run($attributes, $content = false)
	{
		return "<option ".$this->generateAttributes($attributes).'>'.$content.'</option>';
	}
}
