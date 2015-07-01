<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 23.03.2015
 * Time: 15:16
 */
namespace webapp\modules\auth\controllers;
use Exception;
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
		return $this->renderPartial('login.php', ['loginForm' => $loginForm]);
	}

	protected function actionRetrieve()
	{
		$this->app->html->setContent(['status' => 'pending']);
		$this->app->html->setLayout('passretrieve');
	}

	protected function actionSendpass()
	{
		if ($_POST) {
			$email = $_POST['email'];
			if (!Validation::isEmail($email)) {
				$this->app->html->setContent(
					[
						'message' => 'Введите корректный E-Mail',
						'status'  => 'fail',
						'email'   => $email
					]
				);
				$this->app->html->setLayout('passretrieve');
			} else {
				$user = User::where('email', $email)
							->findOne();
				if ($user) {
					$retrieveDP = new PasswordRetrieveService();
					$retrieveDP->load(['email' => $email]);
					$retrieveDP->getResults();
					$this->app->html->setContent(
						[
							'message' => 'Письмо отравленно',
							'status'  => 'success'
						]
					);
					$this->app->html->setLayout('passretrieve');
				} else {
					$this->app->html->setContent(
						[
							'message' => 'Такой E-Mail не найден',
							'status'  => 'fail',
							'email'   => $email
						]
					);
					$this->app->html->setLayout('passretrieve');
				}
			}
		} else {
			$this->redirect('/realty');
		}
	}

	protected function actionCheck()
	{
		$token   = $_GET['t'];
		$checkDP = new TokenCheckService();
		$checkDP->load(['token' => $token]);
		$user = $checkDP->getResults();
		if ($user) {
			$_SESSION['USER'] = [
				'id'     => (int)$user->id,
				'pid'    => (int)$user->pid,
				'login'  => $user->login,
				'email'  => $user->email,
				'img'    => $user->img,
				'code'   => $user->code,
				'rights' => (int)$user->rights,
				'msg'    => ""
			];
			$this->redirect('changepassword');
		}
	}

	protected function actionChangepassword()
	{
		App::html()->left_menu = Menu::left();
		if ($_POST) {
			$newPass       = $_POST['new_password'];
			$newPassRepeat = $_POST['new_password2'];
			if ($newPass===$newPassRepeat) {
				$currUser       = App::get('user');
				$currUser->pass = md5($newPass);
				$currUser->code = "";
				$currUser->save();
				$this->app->html->setContent(
					$this->render(
						'changepassword.php', [
												'message' => 'Пароль успешно изменён',
												'ok'      => true
											]
					)
				);
			} else {
				$this->app->html->setContent($this->render('changepassword.php', ['message' => 'Введённые вами пароли не совпадают']));
			}
		} else {
			$newPass = Tools::passGenerate(8, true);
			if (isset($newPass) && !empty($newPass)) {
				$currUser       = App::get('user');
				$currUser->pass = md5($newPass);
				$currUser->save();
			}
			$this->app->html->setContent($this->render('changepassword.php', ['genPass' => $newPass]));
		}
	}

	protected function actionPassword()
	{
		App::html()->left_menu = Menu::left();
		if ($_POST) {
			$user                = App::get('user');
			$realCurrentPassword = $user->pass;
			$old_pass            = $_POST['old_password'];
			$new_pass            = $_POST['new_password'];
			$new_pass_repat      = $_POST['new_password2'];
			if ($new_pass!=$new_pass_repat) {
				$this->app->html->setContent($this->render('password.php', ['message' => 'Введённые вами пароли не совпадают']));
			} elseif (md5($old_pass)!=$realCurrentPassword) {
				$this->app->html->setContent($this->render('password.php', ['message' => 'Вы ввели неправильный пароль']));
			} else {
				$user->pass = md5($new_pass);
				$user->save();
				$this->app->html->setContent($this->render('password.php', ['ok' => 'ok']));
			}
		} else {
			$this->app->html->setContent($this->render('password.php'));
		}
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

	public function actionRegistration()
	{
		$userSocialID = App::get('user')->socialid;
		if (!$userSocialID) {
			$this->redirect('/auth');
		}
		$vk         = new VK;
		$userVKData = $vk->setConfig(App::getConfig()['vkAPI'])
						 ->getUserByID($userSocialID)[0];
		$userExt = UserExtend::factory()
							 ->where('user_id', App::get('user')->id)
							 ->findOne();
		if (!$userExt) {
			$userExt = UserExtend::create(
				[
					'user_id'    => App::get('user')->id,
					'first_name' => $userVKData->first_name,
					'last_name'  => $userVKData->last_name,
				]
			);
			$userExt->save();
		}
	}
}

?>
