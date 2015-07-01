<?php
namespace system\core\base;

abstract class Service {

	protected $results = null;
	protected $totalCount = null;

	public function __construct () {
	}
	/**
	 * Процесс поиска и заполнение его результатами аттрибута results
	 * @return array
	 */
	abstract public function body();
	/**
	 * #@deprecated
	 * Процесс поиска и заполнение его результатами аттрибута results
	 */
	protected function loadResults (){

	}

	/**
	 * @return mixed
	 * @deprecated
	 */
	protected function loadTotalCount (){

	}


	/**
	 * @return array
	 */
	public function run () {
		if ($this->results===null) {
			if(method_exists($this, 'body')){
				$this->body();
			} else {
				$this->loadResults();
			}
		}
		return $this->results;
	}

	/**
	 * #@deprecated
	 * @return null
	 */
	public function getTotalCount () {
		if ($this->totalCount===null) {
			$this->loadTotalCount();
		}
		return $this->totalCount;
	}

	/**
	 * Задание значений свойств сервиса
	 * @param array $array
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
	}
}
