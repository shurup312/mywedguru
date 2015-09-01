<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 02.07.2015
 * Time: 20:50
 */
namespace frontend\modules\socials;

use frontend\modules\auth\models\SocialUser;
use frontend\modules\socials\fb\Entities\AccessToken;
use frontend\modules\socials\fb\FacebookRedirectLoginHelper;
use frontend\modules\socials\fb\FacebookRequest;
use frontend\modules\socials\fb\FacebookSession;
use frontend\modules\socials\fb\GraphUser;
use yii\base\Object;

class FB extends Object
{

	private static $config;

	public static function getURLForAuth($fbAPI)
	{
		define('FACEBOOK_SDK_V4_SRC_DIR', \yii::getAlias('@frontend/modules/socials/fb'));
		FacebookSession::setDefaultApplication($fbAPI['APPID'], $fbAPI['SECURITY_KEY']);
		$helper = new FacebookRedirectLoginHelper(\yii::$app->params['fbAPI']['redirectURL']);
		$loginUrl = $helper->getLoginUrl();
		return $loginUrl;
	}

	public function getUser($id)
	{
		FacebookSession::setDefaultApplication(self::$config['APPID'], self::$config['SECURITY_KEY']);
		$accessToken = \Yii::$app->session->get('USER')->token;
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
