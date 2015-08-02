<?php
namespace system\core;

use system\core\base\Action;
use system\core\base\Object;
use system\core\base\View;
use system\core\exceptions\NotFoundException;
use system\core\helpers\ArrayHelper;
use Exception;

class Controller extends Object
{

	public $design = false;
	const BEFORE_ACTION = 'beforeAction';
	/**
	 * @var array параметры из url
	 */
	public $parameters;
	/**
	 * @var string действие, которое необходимо выполнить, определяется при разборе url
	 */
	public $action;
	public $actionClass;
	/**
	 * @var bool создавался ли экземпляр данного класса
	 */
	protected static $initialized = false;
	private $handlers = [];
	private $defaultAction = 'index';

	public function __construct()
	{
		$this->init();
		if (!static::$initialized) {
			$this->initOnce();
			static::$initialized = true;
		}
	}

	/**
	 * метод вызывается при создании контроллера
	 */
	protected function init()
	{
	}

	/**
	 * метод вызывается однократно при создании первого экземпляра данного контроллера
	 */
	protected function initOnce()
	{
	}

	/**
	 * @return mixed|string
	 */
	public function run()
	{
		$this->registerEventHandlers();
		return $this->runAction();
	}

	/**
	 * Выполняет указанный action и передает ему параметры из url. Недостающие параметры будут заменены null
	 * @throws \Exception
	 * @return string
	 */
	protected function runAction()
	{
		$this->setMethodForRun();
		$this->checkMethod();
		$this->checkActionClass();
		$this->prepareParams();
		if(!$this->action && !$this->actionClass){
			throw new Exception('Страница не найдена.');
		}
		$this->trigger(self::BEFORE_ACTION);
		$result = null;
		if ($this->action) {
			$result = call_user_func_array(
				[
					$this,
					$this->action
				], $this->parameters
			);
		}
		if ($this->actionClass) {
			$actionClass = App::createObject($this->actionClass['class']);
			$actionClass->setOwner($this);
			$result = call_user_func_array(
				[
					$actionClass,
					'run'
				], $this->parameters
			);
		}
		return $result;
	}

	/**
	 * Возвращает полный путь к файлу
	 *
	 * @param string $file имя файла относительно папки контроллера
	 * @deprecated
	 * @return string
	 */
	protected function path($file = __FILE__)
	{
		return _SYS_PATH_.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, (new \ReflectionClass($this))->getNamespaceName()).DIRECTORY_SEPARATOR.$file;
	}

	/**
	 * @param string $file
	 * @param array  $parameters
	 * @deprecated
	 * @return string
	 */
	public function render($file, array $parameters = array ())
	{
		if ($this->design) {
			App::html()->design = $this->design;
		}
		if(!App::html()->design){
			App::html()->design = 'realty';
		}
		App::html()->design = trim(App::html()->design, '@');
		View::setDesign(App::html()->design);
		$file = $this->getLinkToViewFile($file);
		$file = pathinfo($file);
		$designData = [];
		if(isset(App::html()->header)){
			$designData['header'] = App::html()->header;
		}
		if(isset(App::html()->left_menu)){
			$designData['left_menu'] = App::html()->left_menu;
		}
		return View::withDesign($file['filename'], $parameters, $designData);
	}

	/**
	 * @param       $file
	 * @param array $parameters
	 * @deprecated
	 * @return string
	 * @throws Exception
	 */
	public function renderPartial($file, array $parameters = array ())
	{
		$file = $this->getLinkToViewFile($file);
		$file = pathinfo($file);
		return View::withoutDesign($file['filename'], $parameters);
	}

	public function redirect($url)
	{
		App::response()->redirect($url);
	}

	public function behaviors()
	{
		return [];
	}

	/**
	 * @return array
	 */
	public function actions()
	{
		return [
		];
	}

	/**
	 * Метод выполняется перед любым экшеном. Сюда можно поместить какие-то проверки.
	 *
	 * @param string $actionName
	 *
	 * @throws \Exception
	 * @return bool
	 */
	protected function beforeAction($actionName)
	{
		return true;
	}

	private function registerEventHandlers()
	{
		$object = null;
		if (!is_array($this->behaviors())) {
			return false;
		}
		foreach ($this->behaviors() as $handler) {
			if (!isset($handler['class'])) {
				throw new Exception('Не указан класс для поведения.');
			}
			$object = App::createObject($handler['class']);
			$object->setOwner($this);
			foreach ($handler as $name => $param) {
				if ($name!='class') {
					if ($object instanceof Object) {
						$object->$name = $param;
					}
				}
			}
			$this->handlers[] = $object;
		}
	}

	private function trigger($actionName)
	{
		foreach ($this->handlers as $object) {
			if (!method_exists($object, $actionName)) {
				continue;
			}
			$object->$actionName();
		}
	}

	/**
	 * @param string $action
	 * @deprecated
	 * @return array|string
	 */
	public function url($action = '')
	{
		$out = explode('\\', (new \ReflectionClass($this))->getNamespaceName());
		array_shift($out);
		$out[] = $action;
		$out   = '/'.implode('/', array_filter($out));
		return $out;
	}

	/**
	 * @param $file
	 *
	 * @return string
	 */
	private function getLinkToViewFile($file)
	{
		$reflection = new \ReflectionClass($this);
		$folder     = dirname($reflection->getFileName());
		$folder     = rtrim($folder, DIRECTORY_SEPARATOR);
		$folder     = $folder.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR;
		$file       = $folder.$file;
		return $file;
	}

	protected function setMethodForRun()
	{
		$this->getIndexMethod();
		$this->getRequestNameMethod();
		$this->getActionNameMethod();
		$this->getActionNameClass();
		$this->getDefaultMethod();
		$this->getDefaultNameClass();
	}

	protected function checkMethod()
	{
		if ($this->action) {
			$isSubclassOf = (new \ReflectionClass($this))->getName();
			if (!is_callable(
				[
					$this,
					$this->action
				]
			) && $isSubclassOf == App::getConfig()['defaultController']
			) {
				throw new Exception('Страница не найдена.');
			}
			$reflectionClass      = (new \ReflectionClass($this));
			$numberRequiredParams = $reflectionClass->getMethod($this->action)
													->getNumberOfRequiredParameters();
			$methodParams         = $reflectionClass->getMethod($this->action)
													->getParameters();
			if ($numberRequiredParams>0 && sizeof($this->parameters)<$numberRequiredParams || sizeof($methodParams)>0 && sizeof($this->parameters)>sizeof($methodParams)) {
				throw new NotFoundException();
			}
		}
	}

	/**
	 * @return bool
	 */
	private function getIndexMethod()
	{
		if (!$this->parameters) {
			$method = 'action'.ucfirst($this->defaultAction);
			if(is_callable([$this, $method])){
				$this->action = $method;
			} else {
				if(isset($this->actions()[$this->defaultAction])){
					$this->actionClass = ArrayHelper::merge(['action'=>$this->defaultAction], $this->actions()[$this->defaultAction]);
				}
			}
		}
	}

	private function getRequestNameMethod()
	{
		if (!$this->isSetMethod()) {
			$methodName = strtolower($_SERVER['REQUEST_METHOD']).ucfirst($this->parameters[0]);
			if (is_callable(
				[
					$this,
					$methodName,
				]
			)
			) {
				array_shift($this->parameters);
				$this->action = $methodName;
			}
		}
	}

	private function getActionNameMethod()
	{
		if (!$this->isSetMethod()) {
			$methodName = 'action'.ucfirst($this->parameters[0]);
			if (is_callable(
				[
					$this,
					$methodName,
				]
			)
			) {
				array_shift($this->parameters);
				$this->action = $methodName;
			}
		}
	}

	private function getDefaultMethod()
	{
		if (!$this->isSetMethod()) {
			$methodName = 'action'.ucfirst($this->defaultAction);
			if (is_callable(
				[
					$this,
					$methodName
				]
			)
			) {
				$this->action = $methodName;
			}
		}
	}

	/**
	 * @return bool
	 */
	private function isSetMethod()
	{
		return $this->action || $this->actionClass;
	}

	private function getActionNameClass()
	{
		if (!$this->isSetMethod()) {
			if (isset($this->actions()[$this->parameters[0]])) {
				$this->actionClass = ArrayHelper::merge(
												['action' => $this->parameters[0]], $this->actions()[$this->parameters[0]]
				);
				array_shift($this->parameters);
			}
		}
	}

	private function getDefaultNameClass()
	{
		if (!$this->isSetMethod()) {
			if (isset($this->actions()[$this->defaultAction])) {
				$this->actionClass = $this->actions()[$this->defaultAction];
			}
		}
	}

	private function checkActionClass()
	{
		if ($this->actionClass) {
			$reflectionClass = new \ReflectionClass($this->actionClass['class']);
			if (!$reflectionClass->isSubclassOf(Action::className())) {
				throw new Exception('Класс '.$reflectionClass->getName().', указанный в качестве обработчика для метода '.$this->actionClass['action'].', не является расширением класса '.Action::className().'.');
			}
			try {
				$method = $reflectionClass->getMethod('run');
			} catch(Exception $e) {
				throw new Exception('В классе '.$reflectionClass->getName().' не найден метод run.');
			}
			if($method->getNumberOfParameters()!=sizeof($this->parameters) && $method->getNumberOfRequiredParameters() != sizeof($this->parameters)){
				throw new Exception('Метод run класса '.$this->actionClass['class'].' содержит количество параметров, отличное от переданного в адресной строке количества.');
			}
		}
	}

	public function getAction()
	{
		$result = '';
		if($this->action){
			$result = strtolower(trim($this->action,'action'));
		}
		if($this->actionClass){
			$result = $this->actionClass['action'];
		}
		return $result;
	}

	private function prepareParams()
	{
		foreach ($this->parameters as $key => $item) {
			$this->parameters[$key] = urldecode($item);
		}
	}
}
