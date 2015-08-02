<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 27.03.2015
 * Time: 15:43
 */

namespace system\core\base;

use \system\core\App;
use system\core\helpers\ArrayHelper;
use Exception;
use system\core\response\Response;

class View
{
	protected static $design;
	protected static $designParams = [];
	/**
	 * @return mixed
	 */
	public static function getDesign()
	{
		return self::$design;
	}

	/**
	 * @param mixed $design
	 */
	public static function setDesign($design)
	{
		self::$design = $design;
	}

	/**
	 * Создание объекта View с указанием алиаса для view-файла\

	 *
*@param string $alias имя view-файла
	 * @param array $data данные для view-файла
	 * @param array|bool $designParams данные для дизайна

	 *
*@return string
	 * @throws Exception
	 */
	public static function withDesign($alias, $data = [], $designParams = false)
	{
		$content = self::withoutDesign($alias, $data);
		if ($designParams){
			self::setDesignParams($designParams);
		}
		$designParams = ArrayHelper::merge(self::getDesignParams(), ['content'=>$content]);
		if(!self::$design){
			throw new Exception('Не указан дизайн для отображения.');
		}
		$designFile = _ROOT_PATH_.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.self::$design.DIRECTORY_SEPARATOR.self::$design.'.php';
		if(!file_exists($designFile)){
			throw new Exception('не найден файл дизайна '.self::$design);
		}
		return self::render($designFile, $designParams);
	}

	public static function render($file, array $data = array ())
	{

		ob_start();
		func_num_args(1) > 1 && extract((array)func_get_arg(1));
		if (is_readable(func_get_arg(0))) {
			require $file;
		} else{
			throw new Exception("Шаблон ".$file." не найден");
		}
		return ob_get_clean();
	}

	public static function withoutDesign($alias, $data = [])
	{
		App::response()->setStatus(Response::STATUS_200);
		$moduleAliasPath = App::module()->path;
		$moduleAlias = 'moduleAlias';
		App::setAlias($moduleAlias, $moduleAliasPath);
		$viewAliasInViewsFolder = $moduleAlias.'.views.'.$alias;
		$viewFile = App::getPathOfAlias($viewAliasInViewsFolder);
		return self::render($viewFile, $data);
	}

	/**
	 * @return array
	 */
	public static function getDesignParams()
	{
		return self::$designParams;
	}

	/**
	 * @param array $designParams
	 */
	public static function setDesignParams($designParams)
	{
		self::$designParams = ArrayHelper::merge(self::$designParams, $designParams);
	}
}

?>
