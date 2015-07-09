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
use system\core\socials\FB;
use system\core\socials\fb\FacebookRedirectLoginHelper;
use system\core\socials\fb\FacebookRequest;
use system\core\socials\fb\FacebookRequestException;
use system\core\socials\fb\FacebookSession;
use system\core\socials\fb\GraphUser;
use system\core\socials\OK;
use system\core\socials\VK;
use webapp\modules\auth\forms\LoginForm;
use webapp\modules\auth\forms\RegistrationForm;
use webapp\modules\auth\models\UserKind;
use webapp\modules\cabinet\models\UserExtend;
use webapp\modules\cabinet\services\UpdateUserDataService;
use webapp\modules\users\models\User;
use system\core\App;

class Controller extends \system\core\Controller
{

	protected $status;
	private $registerFormName = 'registerForm';

	protected function initOnce()
	{
		View::setDesign('blank');
		View::setDesignParams(['title' => 'Регистрация']);
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
		} else {
			$user->token = $userData->access_token;
			$user->save();
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
		if (!isset($currentUser->uid)) {
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
		} else {
			$user->token = $userData->refresh_token;
			$user->save();
		}
		$_SESSION['USER']['id'] = $user->id;
		$this->redirect('/auth/registration');
	}

	public function actionFb()
	{
		$fbAPI = App::getConfig()['fbAPI'];
		FacebookSession::setDefaultApplication($fbAPI['APPID'], $fbAPI['SECURITY_KEY']);
		$helper = new FacebookRedirectLoginHelper($fbAPI['redirectURL']);
		try {
			$session = $helper->getSessionFromRedirect();
		} catch(FacebookRequestException $ex) {
			$this->redirect(App::getConfig()['loginURL']);
		} catch(\Exception $ex) {
			$this->redirect(App::getConfig()['loginURL']);
		}
		if (!isset($session)) {
			$this->redirect(App::getConfig()['loginURL']);
		}
		/**
		 * @var GraphUser $currentUser
		 */
		$currentUser = (
		new FacebookRequest(
			$session, 'GET', '/me'
		)
		)->execute()
		 ->getGraphObject(GraphUser::className());
		if (is_null($currentUser->getId())) {
			$this->redirect('/auth');
		}
		$user = User::factory()
					->where('site', User::SITE_FB)
					->where('socialid', $currentUser->getId())
					->findOne();
		if (!$user) {
			$user    = User::create(
				[
					'site'     => User::SITE_FB,
					'status'   => User::STATUS_SOCIAL_APPROVE,
					'rights'   => User::USER_RIGHTS,
					'socialid' => $currentUser->getId(),
					'token'    => $session->getAccessToken(),
				]
			);
			$isSaved = $user->save();
			if (!$isSaved) {
				throw new Exception('Не удалось сохранить пользователя.');
			}
		} else {
			$user->token = $session->getAccessToken();
			$user->save();
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
		$userExtend = UserExtend::factory()->where('user_id', App::get('user')->id)->findOne();
		if($userExtend){
			App::response()->redirect('/cabinet');
		}

		$site = $this->getSocialNetwork();
		if (!$site) {
			$this->redirect('/auth');
		}
		$userData = $site->getUser($userSocialID);
		$form     = new RegistrationForm($this->registerFormName);
		$form->load(
			[
				'first_name' => $userData->first_name,
				'last_name'  => $userData->last_name,
			]
		);
		if (App::request()
			   ->post($this->registerFormName)
		) {
			$serviceDataArray = [
				'userData'  => App::request()
								  ->post($this->registerFormName),
				'userFiles' => null,
				'isModerate' => false,
			];
			(new UpdateUserDataService())->load($serviceDataArray)->run();
			User::findOne(App::get('user')->id)
				->set('status', User::STATUS_REGISTERED)
				->save();
			App::response()
			   ->redirect('/cabinet');
		}
		return View::withDesign(
			'register', [
						  'form' => $form,
					  ]
		);
	}

	private function getSocialNetwork()
	{
		$site = false;
		switch (App::get('user')->site) {
			case User::SITE_VK:
				$site = new VK();
				VK::setConfig(App::getConfig()['vkAPI']);
				break;
			case User::SITE_OK:
				$site = new OK();
				OK::setConfig(App::getConfig()['okAPI']);
				break;
			case User::SITE_FB:
				$site = new FB();
				FB::setConfig(App::getConfig()['fbAPI']);
				break;
		}
		return $site;
	}
}

?>
