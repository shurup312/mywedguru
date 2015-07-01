<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 15.05.2015
 * Time: 13:26
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
		if(isset($attributes['value'])){
			$selected = $attributes['value'];
			$values = $attributes['values'];
			unset($attributes['value']);
			unset($attributes['values']);
			$options = $this->makeOptsList($values, $selected);
		}
		return "<select ".$this->generateAttributes($attributes).' class="form-control">'.$options.'</select>';
	}

	private function makeOptsList($options, $selected)
	{
		$html = '';
		foreach($options as $opt)
		{
			$selectedAttr = '';
			if($opt['value'] == $selected)
			{
				$selectedAttr = 'selected';
			}
			$html .= '<option value="'.$opt['value'].'" '.$selectedAttr.'>'.$opt['name'].'</option>';
		}
		return $html;
	}
}
?>
