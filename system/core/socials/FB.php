<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 02.07.2015
 * Time: 20:50
 */
namespace system\core\socials;

use system\core\App;
use system\core\base\Object;
use system\core\socials\fb\Entities\AccessToken;
use system\core\socials\fb\FacebookRedirectLoginHelper;
use system\core\socials\fb\FacebookRequest;
use system\core\socials\fb\FacebookSession;
use system\core\socials\fb\GraphUser;

class FB extends Object
{

	private static $config;

	public function __construct()
	{

	}

	public static function getURLForAuth($fbAPI)
	{
		define('FACEBOOK_SDK_V4_SRC_DIR', App::getPathOfAlias('system.core.socials.fb'));
		FacebookSession::setDefaultApplication($fbAPI['APPID'], $fbAPI['SECURITY_KEY']);
		$helper = new FacebookRedirectLoginHelper(App::getConfig()['fbAPI']['redirectURL']);
		$loginUrl = $helper->getLoginUrl();
		return $loginUrl;
	}

	public function getUser($id)
	{
		FacebookSession::setDefaultApplication(self::$config['APPID'], self::$config['SECURITY_KEY']);
		$accessToken = new AccessToken(App::get('user')->token);
		$session = new FacebookSession($accessToken);
		$currentUser = (
				new FacebookRequest(
					$session, 'GET', '/me'
				)
				)->execute()
				 ->getGraphObject(GraphUser::className());
		$user = new SocialUser();
		$user->first_name = $currentUser->asArray()['first_name'];
		$user->last_name = $currentUser->asArray()['last_name'];
		return $user;
	}

	public static function setConfig($config)
	{
		self::$config = $config;
	}
}
