<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 08.05.15
 * Time: 17:45
 */
namespace webapp\modules\adm\controllers;

use system\core\App;
use system\core\base\View;
use system\core\behaviors\AccessBehavior;
use webapp\modules\adm\forms\LoginForm;
use webapp\modules\users\models\User;
use SimpleXMLElement;

class Controller extends \system\core\Controller
{

	public function init()
	{
		View::setDesignParams(['header'=>'Панель управления']);
		View::setDesign('admin');
	}

	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessBehavior::className(),
				'rights'  => [User::ADMIN_RIGHTS],
			]
		];
	}

	public function actionIndex()
	{
		$site       = $_SERVER['SERVER_NAME'];
		$tic        = new SimpleXMLElement("http://bar-navig.yandex.ru/u?ver=2&url=http://".$site."&show=1", null, true);
		$diskSpace  = number_format(((disk_total_space($_SERVER['DOCUMENT_ROOT']))/1024/1024), 2, ".", " ");
		$freeSpace  = number_format((disk_free_space($_SERVER['DOCUMENT_ROOT'])/1024/1024), 2, ".", " ");
		$usedSpace  = number_format((memory_get_usage()/1024/1024), 2, ".", " ");
		$memoryMax  = number_format((memory_get_peak_usage()/1024/1024), 2, ".", " ");
		$extensions = get_loaded_extensions();
		return View::withDesign(
			'index', [
				'tic'        => $tic->tcy['value'],
				'diskSpace'  => $diskSpace,
				'freeSpace'  => $freeSpace,
				'usedSpace'  => $usedSpace,
				'memoryMax'  => $memoryMax,
				'extensions' => $extensions,
			]
		);
	}

	public function actionRobots()
	{
		App::html()->header = 'robots.txt';
		$robots_file        = $_SERVER['DOCUMENT_ROOT']."/robots.txt";
		if (isset($_POST['submit'])) {
			$name = $_POST['text'];
			ob_start();
			echo $name;
			$menu = ob_get_contents();
			ob_end_clean();
			if (!$fp = @fopen($robots_file, "w", _FILE_R_)) {
				die ("НЕ УДАЕТСЯ СОЗДАТЬ ФАЙЛ");
			} else {
				@fwrite($fp, html_entity_decode($menu));
				@fclose($fp);
				header("Location: /adm/robots");
			}
		}
		if (!file_exists($robots_file)) {
			$h           = fopen($robots_file, "w+");
			$robots_text = file_get_contents($robots_file);
			fclose($h);
		} else {
			$robots_text = file_get_contents($robots_file);
		}
		return $this->render('robots.php', ['robots_text' => $robots_text]);
	}
}
