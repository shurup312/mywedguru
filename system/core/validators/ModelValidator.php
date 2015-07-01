<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 01.06.15
 * Time: 14:31
 */
namespace system\core\validators;

use system\core\App;
use system\core\base\Object;
use system\core\helpers\ArrayHelper;
use Exception;

abstract class ModelValidator extends Object
{

	public function __construct(Object $object)
	{
		$this->object = $object;
		return $this;
	}

	/**
	 * Список правил для валидации полей
	 * @return array
	 */
	abstract public function rules();

	public function __invoke($object)
	{
		$this->isValid($object);
	}

	/**
	 * Валидатция объекта
	 */
	public function isValid()
	{
		foreach ($this->rules() as $rule) {
			if (isset($rule['scenario']) && $this->scenario!=$rule['scenario']) {
				continue;
			}
			$this->isValidForOneRule($rule);
		}
		return $this->isValid;
	}

	/**
	 * Сеттер для указания валидируемого объекта
	 *
	 * @param \system\core\base\Object $object
	 *
	 * @return $this
	 */
	public function setObject(Object $object)
	{
		$this->object = $object;
		return $this;
	}

	public function setScenario($name)
	{
		$this->scenario = $name;
		return $this;
	}

	protected $isValid = true;
	protected $errors = [];
	protected $object;
	protected $scenario;

	/**
	 * @param array $rule
	 *
	 * @throws \Exception
	 */
	private function isValidForOneRule($rule)
	{
		try {
			App::getPathOfAlias('system.core.validators.'.ucfirst($rule[1]));
		} catch (Exception $e){
			throw new Exception('Не найден валидатор '.$rule[1]);
		}
		/**
		 * @var AbstractValidator $validator
		 */
		$validator = App::createObject('\system\core\validators\\'.ucfirst($rule[1]), array_slice($rule, 2));
		if(!is_array($rule[0])){
			$rule[0] = [$rule[0]];
		}
		foreach ($rule[0] as $field) {
			if(!isset($this->object->getAttributes()[$field])){
				throw new Exception('Не найдено поле '.$field.' в валидируемой модели.');
			}
			if(!$validator->isValid($this->object->{$field})){
				$this->isValid = false;
				$errorsArray          = isset($this->errors[$field])?$this->errors[$field]:[];
				$this->errors[$field] = ArrayHelper::merge($validator->getMessages(), $errorsArray);
			}
			$result[$field][] = [
				'isValid'=>$validator->isValid($this->object->{$field}),
				'errors'=>$validator->getMessages(),
			];
		}

	}

	/**
	 * Список ошибок валидации
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}
} 
