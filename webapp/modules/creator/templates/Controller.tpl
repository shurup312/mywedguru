<?php
namespace webapp\modules\{ModuleName}\controllers;


use system\core\base\View;
use system\core\behaviors\AccessBehavior;
use webapp\modules\{ModuleName}\forms\{FormName};
use webapp\modules\{ModuleName}\models\{ModelName};
use webapp\modules\users\models\User;

class Controller extends \system\core\Controller
{

	public $design = 'admin';
	private $redirectURL = '/{ModuleName}/';

	public function init()
	{
		View::setDesignParams(['header' => '{Title}']);
		View::setDesign('admin');
	}

	public function behaviors()
	{
		return [
			'access' => [
				'class'  => AccessBehavior::className(),
				'rights' => [User::ADMIN_RIGHTS],
			]
		];
	}

	public function actionIndex()
	{
		$list = {ModelName}::factory()->whereNull('date_deleted')->findArray();
		return View::withDesign('index', ['list' => $list]);
	}

	public function actionAdd()
	{
		$formName = 'add{ModuleName}';
		$form     = new {FormName}($formName);
		if (isset($_POST[$formName])) {
                {ModelName}::factory()
				->create($_POST[$formName])
				->save();
			$this->redirect($this->redirectURL);
		}
		return View::withDesign('form', ['form' => $form,]);
	}

	public function actionEdit($id)
	{
		$item     = {ModelName}::findOne($id);
		if(!$item){
			$this->redirect($this->redirectURL);
		}
		$formName = 'edit{ModuleName}';
		$form     = new {FormName}($formName);
		$form->load($item->asArray());
		if (isset($_POST[$formName])) {
			$item->set($_POST[$formName])->save();
			$this->redirect($this->redirectURL);
		}
		return View::withDesign('form', ['form' => $form,]);
	}

	public function actionDelete($id)
	{
		$news = {ModelName}::findOne($id);
		if($news){
			$news->set('date_deleted', date('Y-m-d H:i:s'));
			$news->save();
		}
		$this->redirect($this->redirectURL);
	}
}
