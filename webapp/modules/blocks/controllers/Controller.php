<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 14.05.15
 * Time: 12:37
 */
namespace webapp\modules\blocks\controllers;

use system\core\App;
use system\core\base\View;
use system\core\behaviors\AccessBehavior;
use webapp\modules\blocks\models\Block;
use webapp\modules\blocks\forms\BlocksForm;
use webapp\modules\users\models\User;

class Controller extends \system\core\Controller
{
	public $design = 'admin';
	private $redirectURL = '/blocks/admin/';
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessBehavior::className(),
				'rights'  => [User::ADMIN_RIGHTS],
			]
		];
	}
	public function init()
	{
		View::setDesignParams(['header' => 'Текстовые блоки']);
		View::setDesign('admin');
	}
	public function actionIndex()
	{
		$listBlocks = Block::findMany();
		return View::withDesign('index',['listBlocks'=>$listBlocks]);
	}

	public function actionEdit($id)
	{
		$block = Block::findOne($id);
		if (!$block) {
			$this->redirect($this->redirectURL);
		}
		$formName = 'editNews';
		$form     = new BlocksForm($formName);
		$form->load($block->asArray());
		if (isset($_POST[$formName])) {
			/**
			 * TODO: справить дублирование
			 */
			$newValues = $_POST[$formName];
			$block->set($newValues)
				   ->save();
			$this->redirect($this->redirectURL);
		}
		return View::withDesign('form',['form'=>$form,]);
	}
} 
