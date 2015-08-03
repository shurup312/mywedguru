<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 20.04.15
 * Time: 12:41
 */
namespace system\core\base;

use Exception;
use system\core\App;

class ExceptionHandler extends Object
{

	const STRINGS_FOR_ERROR = 5;

	public function handler(Exception $e)
	{
		if (ob_get_contents()) {
			ob_end_clean();
		}
		$errorFile = [];
		foreach (array_slice(file($e->getFile()), $e->getLine() - self::STRINGS_FOR_ERROR, (self::STRINGS_FOR_ERROR - 1)*2 + 1) as $key => $item) {
			$errorFile[$key + $e->getLine() - self::STRINGS_FOR_ERROR + 1] = $item;
		}
		$errorStack = [];
		foreach ($e->getTrace() as $key => $item) {
			if (!isset($item['file'])) {
				continue;
			}
			$errorStack [$item['file'].$key]['file']   = $item['file'];
			$errorStack [$item['file'].$key]['line']   = $item['line'];
			$errorStack [$item['file'].$key]['method'] = (isset($item['class'])?$item['class'].$item['type']:'').$item['function'].'()';
			foreach (array_slice(file($item['file']), $item['line'] - self::STRINGS_FOR_ERROR, (self::STRINGS_FOR_ERROR - 1)*2 + 1) as $line => $code) {
				$errorStack [$item['file'].$key]['code'][$line + $item['line'] - self::STRINGS_FOR_ERROR + 1] = $code;
			}
		}
		if (App::getConfig()['debug']) {
			$this->render(
				'exception.php', [
								   'exception'  => $e,
								   'errorFile'  => $errorFile,
								   'errorStack' => $errorStack
							   ]
			);
		} else {
			$this->render(
				'exceptionProduction.php', [
											 'exception'  => $e,
											 'errorFile'  => $errorFile,
											 'errorStack' => $errorStack
										 ]
			);
		}
	}

	/**
	 * @param       $file
	 * @param array $data
	 *
	 * @throws Exception
	 * @return string
	 */
	public function render($file, array $data = [])
	{
		$file = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$file;
		func_num_args(1) > 1 && extract((array)func_get_arg(1));
		require $file;
	}

	public function error()
	{
		throw new Exception('Ошибка PHP: '.debug_backtrace()[0]['args'][1]);
	}
}
