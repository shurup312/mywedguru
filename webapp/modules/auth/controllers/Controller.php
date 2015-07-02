<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 23.03.2015
 * Time: 15:16
 */
namespace webapp\modules\auth\controllers;
use Exception;
use system\core\base\View;
use system\core\socials\OK;
use system\core\socials\VK;
use webapp\modules\auth\forms\LoginForm;
use webapp\modules\auth\services\PasswordRetrieveService;
use webapp\modules\auth\services\TokenCheckService;
use webapp\modules\cabinet\models\UserExtend;
use webapp\modules\users\models\User;
use system\core\App;
use system\core\Tools;
use system\core\Validation;

class Controller extends \system\core\Controller
{

	protected $status;

	protected function initOnce()
	{
		App::html()->design = '@realty';
		App::html()
		   ->setJs("/templates/admin/js/jquery.dialog2.js");
		App::html()
		   ->setJs("/templates/admin/js/jquery.form.js");
		App::html()
		   ->setJs('/public/components/realty/vendor/datatables/media/js/jquery.dataTables.js');
		App::html()
		   ->setJs("/public/components/realty/vendor/bootstrap-fileinput/js/fileinput.min.js");
		App::html()
		   ->setCss("/public/components/realty/vendor/bootstrap-fileinput/css/fileinput.css");
		App::html()
		   ->setJs("/public/components/object_statuses/js/icheck-init.js");
		App::html()
		   ->setJs("/public/components/object_statuses/js/jquery.icheck.min.js");
		App::html()
		   ->setCss("/public/components/object_statuses/css/iCheck/skins/minimal/minimal.css");
		App::html()
		   ->setCss("/public/components/object_statuses/css/iCheck/skins/minimal/green.css");
		App::html()
		   ->setJs("/public/components/realty/vendor/angular/angular.js");
		App::html()
		   ->setJs("/public/components/auth/js/controller.js");
		App::html()->header = 'Изменение пароля';
	}

	public function actionIndex()
	{
		$formName  = 'auth';
		$loginForm = new LoginForm($formName);
		if (isset($_POST[$formName])) {
			$loginForm->load($_POST[$formName]);
			$md5    = md5($loginForm->pass);
			$result = User::factory()
						  ->where(
							  [
								  'email' => $loginForm->email,
								  'pass'  => $md5,
							  ]
						  )
						  ->findArray();
			if ($result) {
				$_SESSION['USER'] = $result[0];
				if (isset($_GET['redirectTo'])) {
					$this->redirect($_GET['redirectTo']);
				} else {
					$this->redirect('/adm/');
				}
			}
		}
		return View::withoutDesign('login', ['loginForm' => $loginForm,]);
	}

	public function actionLogout()
	{
		unset($_SESSION['USER']);
		$this->redirect('/auth/');
	}

	public function actionVk()
	{
		if (App::request()
			   ->get('error') || is_null(
				App::request()
				   ->get('code')
			)
		) {
			$this->redirect(App::getConfig()['loginURL']);
		}
		$code     = App::request()
					   ->get('code');
		$config   = App::getConfig()['vkAPI'];
		$userData = VK::getUserToken($config, $code);
		if (!$userData->access_token) {
			$this->redirect('/auth');
		}
		$user = User::factory()
					->where('site', User::SITE_VK)
					->where('socialid', $userData->user_id)
					->findOne();
		if (!$user) {
			$user    = User::create(
				[
					'site'     => User::SITE_VK,
					'status'   => User::STATUS_SOCIAL_APPROVE,
					'rights'   => User::USER_RIGHTS,
					'socialid' => $userData->user_id,
					'token'    => $userData->access_token
				]
			);
			$isSaved = $user->save();
			if (!$isSaved) {
				throw new Exception('Не удалось сохранить пользователя.');
			}
		}
		$_SESSION['USER']['id'] = $user->id;
		$this->redirect('/auth/registration');
	}

	public function actionOk()
	{
		if (App::request()
			   ->get('error') || is_null(
				App::request()
				   ->get('code')
			)
		) {
			$this->redirect(App::getConfig()['loginURL']);
		}
		$code     = App::request()
					   ->get('code');
		$config   = App::getConfig()['okAPI'];
		$userData = OK::getUserToken($config, $code);
		if (!isset($userData->access_token)) {
			$this->redirect('/auth');
		}
		OK::setConfig($config);
		$currentUser = OK::getCurrentUser($userData->access_token);
		if(!isset($currentUser->uid)){
			$this->redirect('/auth');
		}
		$user = User::factory()
					->where('site', User::SITE_OK)
					->where('socialid', $currentUser->uid)
					->findOne();
		if (!$user) {
			$user    = User::create(
				[
					'site'     => User::SITE_OK,
					'status'   => User::STATUS_SOCIAL_APPROVE,
					'rights'   => User::USER_RIGHTS,
					'socialid' => $currentUser->uid,
					'token'    => $userData->refresh_token,
				]
			);
			$isSaved = $user->save();
			if (!$isSaved) {
				throw new Exception('Не удалось сохранить пользователя.');
			}
		}
		$_SESSION['USER']['id'] = $user->id;
		$this->redirect('/auth/registration');
	}

	public function actionRegistration()
	{
		$userSocialID = App::get('user')->socialid;
		if (!$userSocialID) {
			$this->redirect('/auth');
		}
		$site         = $this->getSocialNetwork();
		if(!$site){
			$this->redirect('/auth');
		}
		$userData = $site->getUser($userSocialID);
		$userExt    = UserExtend::factory()
								->where('user_id', App::get('user')->id)
								->findOne();
		if (!$userExt) {
			$userExt = UserExtend::create(
				[
					'user_id'    => App::get('user')->id,
					'first_name' => $userData->first_name,
					'last_name'  => $userData->last_name,
				]
			);
		}
		$userExt->save();
	}

	private function getSocialNetwork()
	{
		$site = false;
		switch(App::get('user')->site){
			case User::SITE_VK:
				$site = new VK();
				VK::setConfig(App::getConfig()['vkAPI']);
				break;
			case User::SITE_OK:
				$site = new OK();
				OK::setConfig(App::getConfig()['okAPI']);
				break;
		}
		return $site;
	}
}

?>
