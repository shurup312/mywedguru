<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 08.05.15
 * Time: 14:13
 */
namespace system\core\HTML;

class SelectTag extends Tag
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
		$options = $attributes['options'];
		unset($attributes['options']);
		if(isset($attributes['value'])){
			$value = $attributes['value'];
			unset($attributes['value']);
		}
		return "<select ".$this->generateAttributes($attributes).'>'.$this->getOptions($options, $value).'</select>';
	}

	private function getOptions($options, $selectedValue)
	{
		$result = '';
		foreach ($options as $key=>$include) {
			if(is_array($include)){
				$optionsInGroup = $this->getOptions($include, $selectedValue);
				$result.= (new OptgroupTag())->run(['label'=>$key], $optionsInGroup);
			} else {
				$attributes = ['value' => $key];
				if($selectedValue == $key){
					$attributes['selected'] = 'selected';
				}
				$result.= (new OptionTag())->run($attributes, $include);
			}
		}
		return $result;
	}
}
