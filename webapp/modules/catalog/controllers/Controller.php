<?php

/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 03.08.2015
 * Time: 17:44
 */
namespace webapp\modules\catalog\controllers;
use system\core\App;
use system\core\base\View;
use system\core\behaviors\AccessBehavior;
use webapp\modules\cabinet\models\UserExtendsBase;
use webapp\modules\users\models\User;

class Controller extends \system\core\Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class'  => AccessBehavior::className(),
				'rights' => [
					User::ADMIN_RIGHTS,
					User::USER_RIGHTS,
					User::SUPER_RIGHTS,
				],
			]
		];
	}

	protected function init()
	{
		View::setDesign('blank');
	}
	public function actionIndex()
	{
		return View::withDesign($this->getViewPath('index'));
	}

	private function getViewPath($viewName)
	{
		$prefix = UserExtendsBase::getPrefixByType(App::get('user')->user_type);
		return $prefix.'.'.$viewName;
	}
}
