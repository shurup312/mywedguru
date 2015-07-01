<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 16.03.15
 * Time: 15:55
 */
namespace system\core\HTML;

/**
 * Class MaterialDesign
 * @package system\core\HTML
 *          параметры у элементов записываются в виде массива
 *          [
 *          'name' => ...
 *          'require' => ...
 *          ]
 */
class MaterialDesign
{

	private $params;
	private $label;
	private $modelName;
	private $formName;



	public static function begin($name, $params)
	{
		$form           = new self();
		$form->formName = $name;
		$form->params   = $params;
		echo '<form name="'.$form->formName.'" '.$form->getAttributes().'>';
		return $form;
	}

	public function end()
	{
		echo '</form>';
	}

	/**
	 * @param string $label
	 * @param array  $params
	 *
	 * @return string
	 */
	public function input($label, $params = [])
	{
		$this->params = $params;
		$this->label  = $label;
		echo '
		<md-input-container>
			<label>'.$this->label.'</label>
			<input'.$this->getAttributes().' ng-model="'.$this->formName.'Data.'.$this->getModelName().'">
			<div ng-messages="'.$this->formName.'.'.$this->getParam('name').'.$error">
				<div ng-message="required">Необходимо заполнить</div>
				<div ng-message="number">Введите число</div>
				<div ng-message="min">Маленькое значение</div>
				<div ng-message="max">Большое значение</div>
			</div>
		</md-input-container>';
	}

	/**
	 * @param string $label
	 * @param array  $params
	 *
	 * @return string
	 */
	public function textarea($label, $params = [])
	{
		$this->params = $params;
		$this->label  = $label;
		echo '
		<md-input-container>
			<label>'.$this->label.'</label>
			<textarea '.$this->getAttributes().' ng-model="'.$this->formName.'Data.'.$this->getModelName().'">'.$this->getParam('value').'</textarea>
			<div ng-messages="'.$this->formName.'.'.$this->getParam('name').'.$error">
				<div ng-message="required">Необходимо заполнить</div>
				<div ng-message="number">Введите число</div>
				<div ng-message="min">Маленькое значение</div>
				<div ng-message="max">Большое значение</div>
			</div>
		</md-input-container>';
	}

	public function autocomplete($params = [])
	{
		$this->params = $params;
		$values = '[]';
		if(isset($params['values'])){
			if(is_array($params['values'])){
				$items = implode(', ',array_keys($params['values']));
				$values = '\''.$items.'\'';
			}
			if(is_string($params['values'])){
				$values = $params['values'];
			}
		}
		echo '
			<md-autocomplete style="width: 80%; margin-top: 30px"
			  	md-selected-item="selectedItem"
			  	md-search-text="'.$this->formName.'Data.'.$this->getModelName().'"
				placeholder="выберите значение"
			  	md-items="item in querySearch('.$this->formName.'Data.'.$this->getModelName().', '.$values.')"
			  	md-item-text="item.display"
			  	md-floating-label="Favorite state"
			  	'.$this->getAttributes().'>
				<span ng-bind-template="{{item.display}}"></span>
		  	</md-autocomplete>
		  	<input type="hidden" '.$this->getAttributes().' ng-value="'.$this->formName.'Data.'.$this->getModelName().'">
		';
	}

	/**
	 * @param string $label
	 * @param array  $params
	 *
	 * @return string
	 */
	public function select($label, $params = [])
	{
		$this->params = $params;
		$this->label  = $label;
		echo '

		<input type="text" '.$this->getAttributes().' ng-value="'.$this->formName.'Data.'.$this->getModelName().'" style="visibility:hidden; width:0; height:0;">
		<md-select placeholder="выберите значение" ng-model="'.$this->formName.'Data.'.$this->getModelName().'" '.$this->getAttributes().'>'.$this->getSelectOptions().'</md-select>
		<div ng-messages="'.$this->formName.'.'.$this->getParam('name').'.$error">
			<div ng-message="required">Необходимо заполнить</div>
		</div>
		';
	}

	public function checkbox($label, $params = [])
	{
		$this->params = $params;
		$this->label  = $label;
		echo '
  		<md-checkbox ng-model="'.$this->formName.'Data.'.$this->getModelName().'" ng-true-value="\'1\'" ng-false-value="\'0\'">
			'.$this->label.'
		</md-checkbox><input name="'.$this->getParam('name').'" type="hidden"  ng-value="'.$this->formName.'Data.'.$this->getModelName().'">';
	}

	private function getAttributes()
	{
		$result = [];
		foreach ($this->params as $attr => $value) {
			if (!is_array($value)) {
				$result[] = $attr.'="'.$value.'"';
			}
		}
		return ' '.implode(' ', $result);
	}

	private function getSelectOptions()
	{
		$result = '';
		$values = $this->getParam('values');
		if (is_array($values)) {
			foreach ($values as $value => $name) {
				$result .= '<md-option value="'.$value.'" >'.$name.'</md-option>';
			}
		}
		return $result;
	}

	/**
	 * @return string
	 */
	private function getModelName()
	{
		$this->modelName = isset($this->params['name']) && $this->params['name']?$this->params['name']:'model'.mt_rand(1000, 10000).microtime(true)*10000;
		return $this->modelName;
	}

	private function getParam($name, $defaultValue = '')
	{
		return !isset($this->params[$name])?$defaultValue:$this->params[$name];
	}
}
