<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 23.03.2015
 * Time: 15:58
 */
namespace webapp\modules\auth\services;

use system\core\base\Service;
use webapp\modules\realty\models\User;

class PasswordRetrieveService extends Service
{

	protected $email;
	private $user;
	private $token;

	protected function loadResults()
	{
		$this->user = User::where('email', $this->email)->findOne();
		$this->token = $this->genToken();
		$this->setToken();
		$this->sendEmail();
	}

	protected function loadTotalCount()
	{
		;
	}

	private function  sendEmail()
	{
		$link = "http://".$_SERVER['SERVER_NAME']."/auth/check?t=".$this->token;
		$headers = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
		$headers .= "From: Изменение пароля MIND ESTATE<noreply@mindestate.ru>\r\n";
		$message = "
					Чтобы изменить пароль,
					перейди по <a href='$link'>ссылке</a>

					Если вы не знаете почему получили это письмо, то игнорируйте его.
			";
		if (mail($this->email, "Доступы к сайту MIND ESTATE", $message, $headers)) {
			return true;
		} else {
			return false;
		}
	}
	private function  setToken()
	{
		$this->user->code = $this->token;
		$this->user->save();
	}
	private function genToken()
	{
		return md5(time().$this->email);
	}
}

?>
