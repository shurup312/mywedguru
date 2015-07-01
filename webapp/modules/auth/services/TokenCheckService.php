<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 23.03.2015
 * Time: 16:24
 */
namespace webapp\modules\auth\services;

use system\core\base\Service;
use webapp\modules\realty\models\User;

class TokenCheckService extends Service
{

	protected $token;
	private $user;

	protected function loadResults()
	{
		if($this->checkToken())
		{
			$this->results = $this->user;
		}else{
			$this->results = false;
		}
	}

	protected function loadTotalCount()
	{
		;
	}

	private function checkToken()
	{
		$this->user = User::where('code', $this->token)->findOne();
		if(!empty($this->user))
		{
			return true;
		}

		return false;
	}
}
?>
