<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 07.05.15
 * Time: 21:42
 */
namespace system\core\base;

use system\core\App;
use system\core\helpers\ArrayHelper;
use system\core\HTML\FormTag;

abstract class Form extends Object
{

	private $__defaultParams = [];
	protected $attributes;

	public function __construct($name)
	{
		$this->setDefaultParams();
		$this->setFormName($name);
		$this->initAttributes();
	}

	/**
	 * Метод, в котором определяется список полей формы
	 * @return array
	 */
	abstract function elements();

	public function load($array)
	{
		foreach ($this->getAttributes() as $key => $value) {
			if (isset($array[$key])) {
				$this->set($key, htmlspecialchars($array[$key], ENT_QUOTES));
			}
		}
	}

	/**
	 * Установить какойаттрибуту значение
	 *
	 * @param string $name
	 * @param mixed  $value
	 */
	public function set($name, $value)
	{
		$this->attributes[$name] = $value;
	}

	/**
	 * Магический геттер для аттрибутов
	 *
	 * @param string $name
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function __get($name)
	{
		if (!isset($this->elements()[$name])) {
			throw new \Exception('Не найдено свойство '.$name);
		}
		if (!isset($this->attributes[$name])) {
			return false;
		}
		return $this->attributes[$name];
	}

	/**
	 * Магический сеттер для аттрибутов
	 *
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @throws \Exception
	 */
	public function __set($name, $value)
	{
		if (!isset($this->elements()[$name])) {
			throw new \Exception('Не найдено свойство '.$name);
		}
		$this->attributes[$name] = $value;
	}

	/**
	 * Получить все аттрибуты
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Получить HTML одного тэга
	 *
	 * @param string $name          имя элемента в масиве описанных элементов
	 * @param array  $tagAttributes аттрибуты тэга
	 *
	 * @throws \Exception
	 * @return string
	 */
	public function getElement($name, $tagAttributes = [])
	{
		$error    = '';
		$elements = $this->elements();
		if (!isset($elements[$name])) {
			throw new \Exception('Не найден элемент '.$name.' в описании формы.');
		}
		$element    = $elements[$name];
		$attributes = $element['attributes'];
		if (isset($element['attributes']['name'])) {
			$attributes = ArrayHelper::merge($attributes, ['name' => $this->getFormName().'['.$element['attributes']['name'].']',]);
		}
		$attributes = ArrayHelper::merge($attributes, $tagAttributes);
		if ($this->$name) {
			$attributes = ArrayHelper::merge($attributes, ['value' => $this->$name,]);
		}
		$html = $this->renderTag($element['type'], $attributes);
		return $this->replaceByTemplate($element['label'], $html, $error);
	}

	/**
	 * Возвращает полностью отрендеренную форму
	 *
	 * @param array $attributes список аттрибутов формы
	 *
	 * @return string
	 */
	public function getForm($attributes = [])
	{
		$result = [];
		foreach ($this->elements() as $nameTag => $params) {
			$result[] = $this->getElement($nameTag);
		}
		$attributes = $this->getFormAttributes($attributes);
		return $this->renderTag(FormTag::className(), $attributes, "\r\n".implode("\r\n", $result)."\r\n");
	}

	/**
	 * Установка нового шаблона вывода полей
	 *
	 * @param string $template
	 */
	public function setTemplate($template)
	{
		$this->__defaultParams['template'] = $template;
	}

	/**
	 * Открытие формы
	 *
	 * @param array $attributes аттрибуты формы
	 *
	 * @return string
	 */
	public function open($attributes = [])
	{
		$attributes = $this->getFormAttributes($attributes);
		return (new FormTag())->open($attributes);
	}

	/**
	 * Закрытие формы
	 * @return string
	 */
	public function close()
	{
		return (new FormTag())->close();
	}

	/**
	 * Возвращает тэг, откомпилированный в соответствии с шаблонизатором
	 *
	 * @param string $label   описание поля
	 * @param string $element html тэга
	 * @param string $error   ошибка валидации
	 *
	 * @return string
	 */
	private function replaceByTemplate($label, $element, $error)
	{
		$result = $this->__defaultParams['template'];
		$result = str_replace('{label}', $label, $result);
		$result = str_replace('{element}', $element, $result);
		return str_replace('{error}', $error, $result);
	}

	/**
	 * @param string      $class      имя класса для рендеринга тэга
	 * @param array       $attributes массив аттрибутов тэга
	 * @param bool|string $content    контент внутри тэга, если он может открваться/закрываться
	 *
	 * @return string
	 */
	private function renderTag($class, $attributes, $content = false)
	{
		$element = App::createObject($class);
		$html    = $element->run($attributes, $content);
		return $html;
	}

	/**
	 * Дефолторвые значения
	 */
	private function setDefaultParams()
	{
		$this->__defaultParams = [
			'template'       => '{label}{element}{error}',
			'formAttributes' => [
				'name'    => null,
				'enctype' => 'application/x-www-form-urlencoded',
				'method'  => 'get',
			]
		];
	}

	/**
	 * Инициализация аттрибутов объекта формы
	 */
	private function initAttributes()
	{
		foreach ($this->elements() as $name => $element) {
			if (!isset($element['attributes']) || !isset($element['attributes']['name'])) {
				continue;
			}
			$this->attributes[$element['attributes']['name']] = null;
		}
	}

	/**
	 * @param array $attributes массив аттрибутов формы
	 *
	 * @return array
	 */
	private function getFormAttributes($attributes)
	{
		$attributes = ArrayHelper::merge($this->__defaultParams['formAttributes'], $attributes);
		$attributes = ArrayHelper::merge($attributes, ['name' => $this->getFormName()]);
		return $attributes;
	}

	private function setFormName($name)
	{
		$this->__defaultParams['formAttributes']['name'] = $name;
	}

	private function getFormName()
	{
		return $this->__defaultParams['formAttributes']['name'];
	}
}
