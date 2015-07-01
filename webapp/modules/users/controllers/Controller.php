<?php
namespace webapp\modules\users\controllers;

use system\core\App;
use system\core\behaviors\AccessBehavior;
use system\core\helpers\StringHelper;
use webapp\modules\users\forms\UsersForm;
use webapp\modules\users\models\User;

class Controller extends \system\core\Controller
{

	public $design = 'admin';
	private $redirectURL = '/users_control/';

	public function init()
	{
		App::html()->header = 'Пользователи';
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
		$list = User::factory()
					 ->findArray();
		return $this->render('index.php', ['list' => $list]);
	}

	public function actionAdd()
	{
		$formName = 'addusers_control';
		$form     = new UsersForm($formName);
		if (isset($_POST[$formName])) {
			if (!isset($_POST[$formName]['pass'])) {
				$_POST[$formName]['pass'] = StringHelper::randomString(5); //без пароля вообще никак нельзя
			}
			$_POST[$formName]['pass'] = md5($_POST[$formName]['pass']);
			User::factory()
				 ->create($_POST[$formName])
				 ->save();
			$this->redirect($this->redirectURL);
		}
		$form->set('pass', StringHelper::randomString(8));
		return $this->render(
			'form.php', [
				'form' => $form,
			]
		);
	}

	public function actionEdit($id)
	{
		$item = User::findOne($id);
		if (!$item) {
			$this->redirect($this->redirectURL);
		}
		$formName = 'editusers_control';
		if (isset($_POST[$formName])) {
			if ($_POST[$formName]['pass']=="") {
				unset($_POST[$formName]['pass']);
			} else {
				$_POST[$formName]['pass'] = md5($_POST[$formName]['pass']);
			}
			$item->set($_POST[$formName])
				 ->save();
			$this->redirect($this->redirectURL);
		}
		$item->set('pass', ''); //чтоб не заполнялось поле пароль при редактировании
		$form = new UsersForm($formName);
		$form->load($item->asArray());
		return $this->render(
			'form.php', [
				'form' => $form,
			]
		);
	}

	public function actionDelete($id)
	{
		$news = User::findOne($id);
		if ($news) {
			$news->delete();
		}
		$this->redirect($this->redirectURL);
	}
}
