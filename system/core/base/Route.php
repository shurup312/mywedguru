<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 22.03.15
 * Time: 19:27
 */
namespace system\core\base;

use system\core\App;
use system\core\helpers\ArrayHelper;
use Exception;

/**
 * Class Route
 * @package core
 * @property string $url             URL для проверки по роутингам
 * @property string $templateURL     URL для проверки по роутингам
 * @property string $arrayVars       URL для проверки по роутингам
 * @property string $pathExpression  URL для проверки по роутингам
 * @property string $pregMatchResult URL для проверки по роутингам
 */
class Route
{

	private $url = null;
	private $templateURL;
	private $arrayVars = [];
	private $arrayReplace;

	/**
	 * @param null $url
	 *
	 * @return $this
	 */
	public function setUrl($url)
	{
		$url       = $this->trimSlashes($url);
		$url = parse_url($url);
		$this->url = $url['path'];
		return $this;
	}

	/**
	 * Входная точка
	 * @return string
	 */
	public function getURLFromRoutes()
	{
		$url = $this->url;

		$this->checkURLParam();
		$this->checkPathParamInConfigs();
		$this->findTemplateForURL();

		if($this->existsTemplateForURL()){
			$url = $this->getChangedURL();
		}
		$url = $this->trimSlashes($url);
		return $url;

	}

	/**
	 * Проверяем, что указан парамет URL
	 * @throws \Exception
	 */
	private function checkURLParam()
	{
		if ($this->url === null) {
			throw new Exception('Не указан URL для проверки.');
		}
	}

	/**
	 * Проверяем, что в конфигах есть шаблоны для роутингов
	 * @throws \Exception
	 */
	private function checkPathParamInConfigs()
	{
		$config = App::getConfig();
		if (!isset($config['paths'])) {
			throw new Exception('Необходимо указать в найтроках роутинги.');
		}
	}

	/**
	 * Ищем для URLа шаблон в массиве роутингов
	 */
	private function findTemplateForURL()
	{
		foreach (App::getConfig()['paths'] as $pathExpression => $templateURL) {
			$this->templateURL = $templateURL;
			$pathExpression    = $this->trimSlashes($pathExpression);
			$pathExpression    = $this->getPathExpression($pathExpression);
			$valid             = $this->checkURLOntoExpressionAndSetArrayReplace($pathExpression);
			if ($valid) {
				break;
			}
		}
	}

	/**
	 * Получить для правила роутингов регулярное выражение.
	 *
	 * @param string $template правило роутинга, то что в конфигас - ключ.
	 *
	 * @return string
	 */
	private function getPathExpression($template)
	{
		$arrayRules       = [];
		$arrayExpressions = [];
		preg_match_all('/\<([0-9a-zA-Z]+?):([\s\S]+?)\>/', $template, $result);
		foreach ($result[0] as $key => $item) {
			$arrayRules[]       = $item;
			$this->arrayVars[]  = '<'.$result[1][$key].'>';
			$arrayExpressions[] = '('.$result[2][$key].'?'.')';
		}
		$expression = str_replace(ArrayHelper::merge($arrayRules, ['/']), ArrayHelper::merge($arrayExpressions, ['\/']), $template);
		return '/^'.$expression.'$/';
	}

	/**
	 * Проверяем URL на соответствие правилу роутинга и, если URL соответствует правилу, то заполняем массив,
	 * что на что надо поменять в URLе на основании правила роутинга.
	 *
	 * @param string $pathExpression регулярное выражение для проверки пути по нему
	 *
	 * @return bool
	 */
	private function checkURLOntoExpressionAndSetArrayReplace($pathExpression)
	{
		$pregMatchResult = $this->checkURLOntoExpression($pathExpression);
		if($valid = sizeof($pregMatchResult[0]) > 0){
			$this->getArrayReplace($pregMatchResult);
		}
		return $valid;
	}

	/**
	 * Удаление у переданной строки слева и справа слэшей
	 *
	 * @param string $param строка
	 *
	 * @return string
	 */
	private function trimSlashes($param)
	{
		$url = trim($param, '/');
		return '/'.$url;
	}

	/**
	 * Получение URLа после того, как по нему пройдемся найденным правилом роутинга
	 * @return mixed
	 */
	private function getChangedURL()
	{
		return str_replace($this->arrayVars, $this->arrayReplace, $this->templateURL);
	}

	/**
	 * Проверяем, что URL подходит под регулярное выражение
	 *
	 * @param string $pathExpression регулярное выражение
	 *
	 * @return array результат прохода регулярки по пути, что именно найдено в пути по этой регулярке
	 */
	private function checkURLOntoExpression($pathExpression)
	{
		$pregMatchResult = [];
		preg_match_all($pathExpression, $this->url, $pregMatchResult);
		return $pregMatchResult;
}

	/**
	 * Сеттер для массива на что надо заменить в шаблоне роутинга переменные
	 *
	 * @param array $pregMatchResult то, что вернула регулярка, после того, как через нее прошел URL
	 */
	private function getArrayReplace($pregMatchResult)
	{
		$this->arrayReplace = [];
		foreach ($pregMatchResult as $key => $value) {
			if ($key==0) {
				continue;
			}
			$this->arrayReplace[] = $value[0];
		}
	}

	/**
	 * Возвращает, найдено ли правило роутинга для URLа
	 * @return bool
	 */
	private function existsTemplateForURL()
	{
		return !is_null($this->arrayReplace);
	}
}
