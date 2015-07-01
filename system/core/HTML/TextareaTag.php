<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 08.05.15
 * Time: 14:13
 */
namespace system\core\HTML;

class TextareaTag extends Tag
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
		$value = '';
		if(isset($attributes['value'])){
			$value = $attributes['value'];
			unset($attributes['value']);
		}
		return "<textarea ".$this->generateAttributes($attributes).'>'.$value.$content.'</textarea>';
	}
}
