<?php
namespace webapp\modules\pages\controllers;

use system\core\App;
use system\core\behaviors\AccessBehavior;
use system\core\exceptions\NotFoundException;
use system\core\helpers\StringHelper;
use webapp\modules\pages\forms\PagesForm;
use webapp\modules\pages\models\Pages;
use webapp\modules\users\models\User;

class Controller extends \system\core\Controller
{

	public $design = 'admin';
	private $redirectURL = '/pages/';

	public function init()
	{
		App::html()->header = 'Страницы';
	}

	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessBehavior::className(),
				'rules' => [
					[
						'actions' => [
							'index',
							'add',
							'edit',
							'delete',
							'togglevisibility',
						],
						'rights'  => [User::ADMIN_RIGHTS],
					],
				],
			]
		];
	}

	public function actionIndex()
	{
		$list = Pages::factory()
					 ->whereNull('date_deleted')
					 ->findArray();
		return $this->render('index.php', ['list' => $list]);
	}
	public function actionLoadPages()
	{
		$pages = Pages::factory()->whereNull('date_deleted')->findMany()->asArray();
		echo json_encode($pages);
		die();
	}
	public function actionShow($alias)
	{
		$model        = Pages::where(['url' => $alias])
							 ->whereNull('date_deleted')
							 ->where('is_visible', 1)
							 ->findOne();
		if ($model) {
			App::html()->metaDescription = $model->meta_description;
			App::html()->metaKeywords    = $model->meta_keywords;
			App::html()->title           = $model->meta_title;
			return $this->render('page.php', ['article' => $model]);
		}
		throw new NotFoundException();
	}

	public function actionAdd()
	{
		$formName = 'addpages';
		$form     = new PagesForm($formName);
		if (isset($_POST[$formName])) {
			$url = $_POST[$formName]['url'];
			if ($url!='') {
				$url = StringHelper::makeAlias($url);
			} else {
				$url = $_POST[$formName]['title'];
				$url = StringHelper::makeAlias($url);
			}
			$_POST[$formName]['url'] = $url;
			if (Pages::addRoute($url)) {
				Pages::factory()
					 ->create($_POST[$formName])
					 ->save();
				$this->redirect($this->redirectURL);
			} else {
				return 'err';
			}
		}
		return $this->render(
			'form.php', [
				'form' => $form,
			]
		);
	}

	public function actionEdit($id)
	{
		$item = Pages::findOne($id);
		if (!$item) {
			return 'not found';
			$this->redirect($this->redirectURL);
		}
		$alias_old = $item->url;
		$formName  = 'editpages_'.$id;
		$form      = new PagesForm($formName);
		$form->load($item->asArray());
		if (isset($_POST[$formName])) {
			$url = $_POST[$formName]['url'];
			unset($_POST[$formName]['url']);
			if ($url!='') {
				$url = StringHelper::makeAlias($url);
			} else {
				$url = $_POST[$formName]['title'];
				$url = StringHelper::makeAlias($url);
			}
			if (Pages::editRoute($alias_old, $url)) {
				$_POST[$formName]['url'] = $url;
				$item->set($_POST[$formName])
					 ->save();
				echo 'ok';
				die();
				//$this->redirect($this->redirectURL);
			}
		}
		return $this->render(
			'form.php', [
				'form' => $form,
			]
		);
	}

	public function actionLoadPageData($id)
	{
		$page     = Pages::factory()
						 ->findOne($id);
		$formName = 'editpages_'.$id;
		if ($page) {
			$form = new PagesForm($formName);
			$form->load($page->asArray());
			return $this->renderPartial('_pageForm.php', ['pageData' => $page, 'form'=>$form]);
		}
		return new NotFoundException();
	}

	public function actionDelete($id)
	{
		$news = Pages::findOne($id);
		if ($news) {
			if (Pages::removeRoute($news->url)) {
				$news->set('date_deleted', date('Y-m-d H:i:s'));
				$news->save();
				echo 'ok';
				die();
			}
		}
		$this->redirect($this->redirectURL);
	}

	public function actionToggleVisibility($id)
	{
		$page = Pages::findOne($id);
		if (!$page) {
			echo 'err/not found';
			die();
		}
		$page->is_visible = !$page->is_visible;
		if ($page->save()) {
			echo 'ok';
		} else {
			echo 'err';
		}
		die();
	}
}
