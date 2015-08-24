<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 10.08.2015
 * Time: 23:49
 */
namespace common\services;

abstract class Service {

	protected $results = null;

	public function __construct () {
	}
	/**
	 * Процесс поиска и заполнение его результатами аттрибута results
	 * @return array
	 */
	abstract public function body();

	/**
	 * @return array
	 */
	public function run () {
		if ($this->results===null) {
			$this->body();
		}
		return $this->results;
	}

	/**
	 * Задание значений свойств сервиса
	 *
	 * @param array $array
	 *
	 * @return $this
	 */
	public function load(array $array)
	{
		$reflector = new \ReflectionClass($this);
		$properties = $reflector->getProperties();
		foreach ($properties as $property) {
			if(isset($array[$property->name])){
				$this->{$property->name} = $array[$property->name];
			}
		}
		return $this;
	}
}
