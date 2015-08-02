<?php
namespace webapp\modules\cabinet\controllers;

use system\core\App;
use system\core\base\View;
use system\core\behaviors\AccessBehavior;
use webapp\assets\BootstrapAsset;
use webapp\modules\cabinet\forms\UserForm;
use webapp\modules\cabinet\models\UserExtend;
use webapp\modules\cabinet\models\UserExtendHistory;
use webapp\modules\cabinet\models\UserExtendsBase;
use webapp\modules\cabinet\services\ApproveUserChangeService;
use webapp\modules\cabinet\services\RejectUserChangeService;
use webapp\modules\cabinet\services\SendInviteService;
use webapp\modules\cabinet\services\UpdateUserDataService;
use webapp\modules\users\models\User;

/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 01.07.2015
 * Time: 13:55
 */
class Controller extends \system\core\Controller
{

	public $formName = 'userCabinet';

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
		BootstrapAsset::init();
		View::setDesign('blank');
		View::setDesignParams(
			[
				'header' => '',
				'title'  => '',
			]
		);
	}

	public function actionIndex()
	{
		$text = 'Данные пользователя';
		View::setDesignParams(
			[
				'header' => $text,
				'title'  => $text,
			]
		);
		$existModerate = UserExtendHistory::getCurrentModel()
										  ->where('status', UserExtendHistory::NOT_APPROVED_STATUS)
										  ->where('user_id', App::get('user')->id)
										  ->findOne();
		$userExtend    = UserExtend::getCurrentModel()
								   ->where('user_id', App::get('user')->id)
								   ->findOne();
		return View::withDesign(
			$this->getViewPath('index'), [
										   'user'          => $userExtend,
										   'existModerate' => $existModerate,
									   ]
		);
	}

	public function actionEdit()
	{
		$text = 'Редактирование данных пользователя';
		View::setDesignParams(
			[
				'header' => $text,
				'title'  => $text,
			]
		);
		$formName = $this->formName;
		$form     = new UserForm($formName);
		$userData = UserExtend::getCurrentModel()
							  ->where('user_id', App::get('user')->id)
							  ->findArray();
		if ($userData) {
			$userData = $userData[0];
			$form->load($userData);
		}
		return View::withDesign(
			'edit', [
					  'user' => $userData,
					  'form' => $form,
				  ]
		);
	}

	public function actionSave()
	{
		if (App::request()
			   ->post($this->formName)
		) {
			$serviceDataArray = [
				'userData'  => App::request()
								  ->post($this->formName),
				'userType'  => App::get('user')->user_type,
				'userFiles' => App::request()
								  ->files($this->formName),
			];
			(new UpdateUserDataService())->load($serviceDataArray)
										 ->run();
		}
		App::response()
		   ->redirect('/cabinet');
	}

	public function actionListChanges()
	{
		$text = 'Список новых заяков на изменение личных данных';
		View::setDesignParams(
			[
				'header' => $text,
				'title'  => $text,
			]
		);
		$userHistoryTable       = UserExtendHistory::$table;
		$userTable              = UserExtend::$table;
		$listNotApprovedChanges = UserExtendHistory::factory()
												   ->rawSelect($userHistoryTable.'.id')
												   ->rawSelect($userHistoryTable.'.first_name new_first_name ,'.$userTable.'.first_name old_first_name')
												   ->rawSelect($userHistoryTable.'.last_name new_last_name ,'.$userTable.'.last_name old_last_name')
												   ->rawSelect($userHistoryTable.'.phone new_phone ,'.$userTable.'.phone old_phone')
												   ->rawSelect($userHistoryTable.'.work_phone new_work_phone ,'.$userTable.'.work_phone old_work_phone')
												   ->rawSelect($userHistoryTable.'.passport new_passport ,'.$userTable.'.passport old_passport')
												   ->rawSelect($userHistoryTable.'.passport_ext new_passport_ext ,'.$userTable.'.passport_ext old_passport_ext')
												   ->rawSelect($userHistoryTable.'.avatar new_avatar ,'.$userTable.'.avatar old_avatar')
												   ->where($userHistoryTable.'.status', UserExtendHistory::NOT_APPROVED_STATUS)
												   ->leftOuterJoin($userTable, $userTable.'.user_id = '.$userHistoryTable.'.user_id')
												   ->findMany();
		return View::withDesign(
			'listChanges', [
							 'list' => $listNotApprovedChanges,
						 ]
		);
	}

	public function actionApprove($id)
	{
		(new ApproveUserChangeService())->load(
			[
				'id'            => $id,
				'approveUserID' => App::get('user')->id,
			]
		)
										->run();
		App::response()
		   ->redirect('/cabinet/listchanges');
	}

	public function actionReject($id)
	{
		(new RejectUserChangeService())->load(
			[
				'id'           => $id,
				'rejectUserID' => App::get('user')->id,
			]
		)
									   ->run();
		App::response()
		   ->redirect('/cabinet/listchanges');
	}

	private function getViewPath($viewName)
	{
		$prefix = UserExtendsBase::getPrefixByType(App::get('user')->user_type);
		return $prefix.'.'.$viewName;
	}
}
