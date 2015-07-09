<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 06.07.2015
 * Time: 17:47
 */
namespace webapp\modules\cabinet\services;

use system\core\base\Service;
use webapp\modules\users\services\SendInviteService as BaseSendInvite;

class SendInviteService extends Service
{

	public $email;
	public $first_name;
	public $last_name;
	public $phone;
	public $work_phone;
	public $passport;
	public $passport_ext;

	public function body()
	{
		$this->results  = [
			'user'       => false,
			'userExtend' => false,
		];
		$userData       = ['email' => $this->email];
		$user           = (new BaseSendInvite())->load($userData)
												->run();
		$userExtendData = [
			'isModerate' => false,
			'baseUser'   => $user,
			'userData'   => [
				'first_name'   => $this->first_name,
				'last_name'    => $this->last_name,
				'phone'        => $this->phone,
				'work_phone'   => $this->work_phone,
				'passport'     => $this->passport,
				'passport_ext' => $this->passport_ext,
			],
		];
		$this->results = (new UpdateUserDataService())->load(
			$userExtendData
		)
									 ->run();
	}
}
