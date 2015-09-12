<?php
namespace frontend\modules\socials;

use frontend\modules\auth\models\SocialUser;
use yii\base\Object;

/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 10.08.2015
 * Time: 18:19
 */
class OK extends Object
{
	private static $config;

	public static function getCurrentUser($accessToken)
	{
		$sig  = self::getSig($accessToken, 'users.getCurrentUser');
		$link = 'http://api.ok.ru/fb.do?method=users.getCurrentUser&application_key='.self::$config['PUBLIC_KEY'].'&sig='.$sig.'&access_token='.$accessToken;
		return json_decode(self::request($link));
	}

	/**
	 * @param mixed $config
	 *
	 * @return $this
	 */
	public static function setConfig($config)
	{
		self::$config = $config;
	}

	public static function getURLForAuth($okAPI)
	{
		return 'http://www.odnoklassniki.ru/oauth/authorize?client_id='.$okAPI['APPID'].'&scope='.$okAPI['PERMISSIONS'].'&response_type=code&redirect_uri='.$okAPI['redirectURL'];
	}

	public static function getUserToken($config, $code)
	{
		$link     = 'https://api.odnoklassniki.ru/oauth/token.do?code='.$code.'&client_id='.$config['APPID'].'&client_secret='.$config['PRIVATE_KEY'].'&redirect_uri='.$config['redirectURL'].'&grant_type=authorization_code';
		$userData = self::request($link);
		return json_decode($userData);
	}

	public static function getAccessToken($config)
	{
		$accessToken = isset($_SESSION['USER']['access_token'])?$_SESSION['USER']['access_token']:false;
		if($accessToken){
			return $accessToken;
		}
		$link     = 'https://api.odnoklassniki.ru/oauth/token.do?refresh_token='.\Yii::$app->session->get('USER')->token.'&client_id='.$config['APPID'].'&client_secret='.$config['PRIVATE_KEY'].'&grant_type=refresh_token';
		$response = json_decode(self::request($link));
		if(isset($response->access_token)){
			$accessToken = $response->access_token;
		}
		return $accessToken;
	}

	/**
	 * @param $link
	 *
	 * @return string
	 */
	private static function request($link)
	{
		$context = stream_context_create(
			array (
				'http' => array (
					'method' => 'POST',
					'header' => 'Content-Type: application/x-www-form-urlencoded'.PHP_EOL,
				),
			)
		);
		return file_get_contents($link, false, $context);
	}

	/**
	 * @param $accessToken
	 * @param $method
	 *
	 * @return string
	 */
	private static function getSig($accessToken, $method)
	{
		$sig  = 'application_key='.self::$config['PUBLIC_KEY'].'method='.$method;
		$text = $accessToken.self::$config['PRIVATE_KEY'];
		$sig .= md5($text);
		$sig = strtolower(md5($sig));
		return $sig;
	}

	/**
	 * @return SocialUser
	 */
	public function getUser(){
		$responseUser = self::getCurrentUser(self::getAccessToken(self::$config));
		$user = new SocialUser();
		$user->first_name = $responseUser->first_name;
		$user->last_name = $responseUser->last_name;
		return $user;
	}
}
