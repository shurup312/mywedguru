<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 24.03.15
 * Time: 15:27
 */
namespace system\core\HTML;

use system\core\App;
use system\core\base\Object;
use system\core\helpers\ArrayHelper;

abstract class Asset extends Object
{

	private $css = [];
	private $js = [];
	protected $priority = 0;

	/**
	 * Метод запускает присоединение списка скриптов
	 */
	public static function init()
	{
		/**
		 * @var Asset $self
		 */
		$self = new static;
		$self->getFiles();
		$self->sortByPriority();
		$self->insertFilesInHTML();

	}

	/**
	 * Список скриптов и css для подключения
	 * @return array ['css' => array, 'js' => array]
	 */
	abstract public function package();

	/**
	 * Массив других объектов-asset'ов, которые нужны этому в плане зависимости
	 * @return array
	 */
	abstract public function dependency();

	protected function getPackage()
	{
		$result = $this->package();
		if (isset($result['css']) && is_array($result['css'])) {
			$this->addFilesToArray($result['css'], 'css');
		}
		if (isset($result['js']) && is_array($result['js'])) {
			$this->addFilesToArray($result['js'], 'js');
		}
	}

	protected function getDependencies()
	{
		$dependencies = $this->dependency();
		if (empty($dependencies)) {
			return true;
		}
		foreach ($dependencies as $class) {
			$class           = App::createObject($class);
			$class->priority = $this->priority + 10;
			$class->getFiles();
			$this->css = ArrayHelper::merge($this->css, $class->css);
			$this->js  = ArrayHelper::merge($this->js, $class->js);
		}
	}

	private function addFilesToArray($files, $arrayName)
	{
		foreach ($files as $file) {
			$this->{$arrayName}[] = [
				'name'     => $file,
				'priority' => $this->priority
			];
		}
	}

	public function getFiles()
	{
		$this->getPackage();
		$this->getDependencies();
	}

	public function sortByPriority()
	{
		usort(
			$this->js, [
						 $this,
						 'sortByDesc'
					 ]
		);
		usort(
			$this->css, [
						  $this,
						  'sortByDesc'
					  ]
		);
	}

	public function sortByDesc($a, $b)
	{
		if ($a['priority'] > $b['priority']) {
			return -1;
		}
		if ($a['priority'] < $b['priority']) {
			return 1;
		}
		return 0;
	}

	private function insertFilesInHTML()
	{
		foreach ($this->js as $js) {
			App::html()->setJs($js['name']);
		}
		foreach ($this->css as $css) {
			App::html()->setCss($css['name']);
		}
	}
}
