<?php
namespace system\core\socials;

use Exception;
use system\core\base\Object;

/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 01.07.2015
 * Time: 19:58
 */
class VK extends Object
{

	private $config;

	/**
	 * @param mixed $config
	 *
	 * @return $this
	 */
	public function setConfig($config)
	{
		$this->config = $config;
		return $this;
	}
	public static function getURLForAuth($config)
	{
		return 'https://oauth.vk.com/authorize?client_id='.$config['APPID'].'>&scope='.$config['PERMISSIONS'].'&redirect_uri='.$config['responseURL'].'&response_type=code&v='.$config['version'].'&state=mywed.guru';
	}

	public static function getUserToken($config,$code)
	{
		$link = 'https://oauth.vk.com/access_token?client_id='.$config['APPID'].'&client_secret='.$config['SECURITY_KEY'].'&code='.$code.'&redirect_uri='.$config['responseURL'];
		$userData = file_get_contents($link);
		return json_decode($userData);
	}

	public function getUserByID($userID)
	{

		$link = 'https://api.vk.com/method/users.get?user_ids='.$userID.'&fields=sex,bdate,city,country,photo_50,photo_100,photo_200_orig,photo_200,photo_400_orig,photo_max,photo_max_orig,photo_id,online,online_mobile,domain,has_mobile,contacts,connections,site,education,universities,schools,can_post,can_see_all_posts,can_see_audio,can_write_private_message,status,last_seen,relation,relatives,counters,screen_name,maiden_name,timezone,occupation,activities,interests,music,movies,tv,books,games,about,quotes,personal,friends_status';
		$user = file_get_contents($link);
		return $this->response($user);
	}

	private function response($data)
	{
		$dataDecode = json_decode($data);
		if(isset($dataDecode->response)){
			return $dataDecode->response;
		}
		if(isset($dataDecode->error)){
			throw new Exception('Что-то пошло не так,сервер вернул '.$data);
		}

	}
}
