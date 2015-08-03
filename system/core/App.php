<?php
namespace system\core;

/*
* @class app Основной класс системы, его вывзов иницирует запуск всей CMS 
* при это классы из папки system_classes при соблюдениии правила CLASSNAME.class.php
* грузятся автоматом!!!
*/
use system\core\base\Component;
use system\core\base\Request;
use system\core\base\Route;
use system\core\base\Session;
use system\core\response\Response;
use webapp\modules\realty\models\User;
use system\core\base\ExceptionHandler;
use Exception;
use ReflectionClass;
use stdClass;

/**
 * Class App
 * @package Core
 * @property Html $html   instance of system\core\Html class
 * @property User $user   instance of system\core\fs class
 * @static  Request   $request   instance of system\core\Request class
 */
class App
{

	private static $aliases = [];
	const BEFORE_CONTROLLER = 'beforeController';
	private static $html;
	private static $response;
	private static $session;
	// ну и конечно ссылка!
	public $url;
	// все ошибки!
	public static $ControllerAsContent = false;
	public static $request;
	private static $instance;
	private static $config;
	private static $components = [];
	private static $module = false;

	/**
	 * @return array
	 */
	public static function module()
	{
		return self::$module;
	}

	/**
	 * @return array
	 */
	public static function getConfig()
	{
		return self::$config;
	}

	public static function go()
	{
		if (!self::$instance) {
			self::$request          = new Request();
			self::$instance         = new self;
			self::$instance->design = "default";
			self::$config           = require_once(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
		}
		return self::$instance;
	}

	/**
	 * @return mixed
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @deprecated
	 */
	public function SetControllerAsContent()
	{
		self::$ControllerAsContent = true;
		ob_start();
	}

	public function findController()
	{
		$namespace        = 'modules';
		$controllerParams = $this->findControllerByNamespace($namespace);
		if (!$this->existsControllerFile($controllerParams)) {
			$controllerParams->namespace = App::getConfig()['defaultController'];
			$controllerParams->url       = App::getConfig()['webappFolder'].'..'.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $controllerParams->namespace).'.php';
		}
		if (!$this->existsControllerFile($controllerParams)) {
			throw new Exception('Не найден контроллер для страницы.');
		}
		$controllerParams->controller = App::createObject($controllerParams->namespace);

		return $controllerParams;
	}

	public function run()
	{
		App::session();
		$this->registerHandlers();
		$this->url = (new Route())->setUrl($_SERVER['REQUEST_URI'])
								  ->getURLFromRoutes();
		$this->beforeController();
		$controllerParams       = $this->findController();
		$controller             = $controllerParams->controller;
		$controller->action     = $controllerParams->method;
		$controller->parameters = $controllerParams->arguments;
		$this->setModuleConfig($controller);
		$content = $controller->run();
		echo Response::run($content);
	}

	/**
	 * @param string $className
	 *
	 * @return object
	 */
	public static function createObject($className, $args = false)
	{
		$reflection = new ReflectionClass($className);
		if ($args) {
			return $reflection->newInstance($args);
		}
		return $reflection->newInstance();
	}

	private function registerHandlers()
	{
		$exceptionHandler = new ExceptionHandler();
		set_exception_handler(
			[
				$exceptionHandler,
				'handler'
			]
		);
		set_error_handler(
			[
				$exceptionHandler,
				'error'
			]
		);
	}

	/**
	 * @param ControllerParams $controllerParams
	 *
	 * @return bool
	 */
	private function existsControllerFile(ControllerParams $controllerParams)
	{
		$controllerFile = $controllerParams->url;
		if (!file_exists($controllerFile)) {
			return false;
		}
		$controllerParams->reflector = new ReflectionClass($controllerParams->namespace);
		if (!$controllerParams->reflector->isSubclassOf('system\core\\Controller')) {
			return false;
		}
		return $controllerParams;
	}

	/**
	 * @param $namespace
	 *
	 * @return ControllerParams
	 */
	private function findControllerByNamespace($namespace)
	{
		$config        = App::getConfig();
		$urlController = $config['webappFolder'].'modules'.DIRECTORY_SEPARATOR;
		$trim          = trim($this->url, '/');
		if ($trim) {
			$urlArray = explode('/', $trim);
		} else {
			$urlArray = [];
		}
		foreach ($urlArray as $key => $module) {
			if (isset($config['modules'][$module])) {
				$config = $config['modules'][$module];
				if ($this->isSubModule($key)) {
					$urlController .= 'modules'.DIRECTORY_SEPARATOR;
					$namespace .= "\\modules";
				}
				$urlController .= $module.DIRECTORY_SEPARATOR;
				$namespace .= '\\'.$module;
				array_shift($urlArray);
			} else {
				break;
			}
		}
		$arguments         = $urlArray;
		$result            = new ControllerParams();
		$result->url       = $urlController.'controllers'.DIRECTORY_SEPARATOR.'Controller.php';
		$result->namespace = 'webapp\\'.$namespace.'\\controllers\\Controller';
		$result->arguments = $arguments;
		return $result;
	}

	private function beforeController()
	{
		App::html()->design = '@admin';
		$filename           = App::getConfig()['systemFolder'].'modules'.str_replace('/', DIRECTORY_SEPARATOR, $this->url).'.php';
		if (!file_exists($filename)) {
			$filename = App::getConfig()['systemFolder'].'modules'.str_replace('/', DIRECTORY_SEPARATOR, $this->url).DIRECTORY_SEPARATOR.'index.php';
		}
		if (file_exists($filename)) {
			ob_start();
			require $filename;
			$this->html->content = ob_get_contents();
			ob_end_clean();
			$this->html->render();
			die();
		}
	}

	public static function get($componentClassName)
	{
		$config = App::getConfig()['components'];
		if (!isset($config[$componentClassName])) {
			throw new Exception('Не найден компонент '.$componentClassName);
		}
		if (!isset($config[$componentClassName]['class'])) {
			throw new Exception('Не определен класс для компонента '.$componentClassName);
		}
		if (!(new ReflectionClass($config[$componentClassName]['class']))->isSubclassOf(Component::className())) {
			throw new Exception('Файл компонента должен быть унаследован от '.Component::className());
		}
		if (!isset(self::$components[$componentClassName])) {
			self::$components[$componentClassName] = App::createObject($config[$componentClassName]['class']);
		}
		return self::$components[$componentClassName];
	}

	public static function html()
	{
		if (!self::$html) {
			self::$html = new Html();
		}
		return self::$html;
	}

	public static function request()
	{
		if (!self::$request) {
			self::$request = new Request();
		}
		return self::$request;
	}

	public static function session()
	{
		if (!self::$session) {
			self::$session = new Session();
		}
		return self::$session;
	}

	public static function response()
	{
		if (!self::$response) {
			self::$response = new Response();
		}
		return self::$response;
	}

	/**
	 * @param $key
	 *
	 * @return bool
	 */
	private function isSubModule($key)
	{
		return $key > 0;
	}

	/**
	 * Регистрация нового алиаса
	 *
	 * @param string $alias
	 * @param string $path
	 */
	public static function setAlias($alias, $path)
	{
		$path                  = rtrim($path, DIRECTORY_SEPARATOR);
		self::$aliases[$alias] = $path;
	}

	/**
	 * Регистрация нового алиаса
	 *
	 * @param string $alias
	 * @param string $path
	 */
	public static function removeAlias($alias)
	{
		unset(self::$aliases[$alias]);
	}

	/**
	 * Получение пути по алиасу. Алиас может задаваться как до папки, так и до файла.
	 * То есть для алиаса system.master будет проверяться существование папки master в алиасе system, а затем существование
	 * файла master.php в алиасе system.
	 *
	 * @param string $alias
	 *
	 * @return bool|string
	 * @throws \Exception
	 */
	public static function getPathOfAlias($alias)
	{
		$path   = '';
		$result = false;
		$alias  = explode('.', $alias);
		if (!isset($alias[0])) {
			return $path;
		}
		if (!isset(self::$aliases[$alias[0]])) {
			throw new Exception('Не найден алиас '.$alias[0]);
		}
		$path = self::$aliases[$alias[0]];
		$path = $path.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, array_slice($alias, 1));
		if (file_exists($path)) {
			$result = $path.DIRECTORY_SEPARATOR;
		}
		if (file_exists($path.'.php')) {
			$result = $path.'.php';
		}
		if (!$result) {
			//throw new Exception('Не найдено определение для алиаса '.implode('.', $alias));
		}
		$result = str_replace('\\\\', '\\', $result);
		return $result;
	}

	private function setModuleConfig(Controller $controller)
	{
		$reflector   = new ReflectionClass($controller);
		$modulesList = explode('\\modules\\', $reflector->name);
		array_shift($modulesList);
		$modulesList[sizeof($modulesList) - 1] = str_replace('\controllers\Controller', '', $modulesList[sizeof($modulesList) - 1]);
		$config                                = App::getConfig();
		$module                                = null;
		foreach ($modulesList as $module) {
			if (!isset($config['modules'][$module])) {
				return;
			}
			$config = $config['modules'][$module];
		}
		unset($config['modules']);
		self::$module         = new StdClass();
		self::$module->path   = dirname(dirname($reflector->getFileName())).DIRECTORY_SEPARATOR;
		self::$module->name   = $module;
		self::$module->config = $config;
	}
}

class ControllerParams
{

	public $namespace;
	public $url;
	public $controller;
	public $method;
	public $arguments;
	/**
	 * @var ReflectionClass $reflector
	 */
	public $reflector;
}
