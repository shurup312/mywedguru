<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 08.05.15
 * Time: 16:12
 */
namespace system\core\HTML;

class FormTag extends Tag
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
		return $this->open($attributes).$content.$this->close();
	}

	public function open($attributes){
		return "<form ".$this->generateAttributes($attributes).'>';
	}

	public function close()
	{
		return '</form>';
	}
}
