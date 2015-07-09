<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 02.07.2015
 * Time: 19:45
 */
namespace webapp\modules\cabinet\services;

use system\core\base\Service;
use webapp\modules\cabinet\models\UserExtend;
use webapp\modules\cabinet\models\UserExtendHistory;

class ApproveUserChangeService extends Service
{

	public $id;
	public $approveUserId;
	/**
	 * Процесс поиска и заполнение его результатами аттрибута results
	 * @return array
	 */
	public function body()
	{
		$userData = UserExtendHistory::findOne($this->id);
		if (!$userData) {
			return;
		}
		$userData->set(
			[
				'action_user_id' => $this->approveUserId,
				'status'         => UserExtendHistory::IS_APPROVED_STATUS,
				'date_deleted'   => date('Y-m-d H:i:s'),
			]
		);
		$userData->save();
		$arrayUserData = $userData->asArray();
		unset($arrayUserData['id']);
		unset($arrayUserData['action_user_id']);
		unset($arrayUserData['status']);
		unset($arrayUserData['date_created']);
		unset($arrayUserData['date_deleted']);
		if(!$arrayUserData['avatar']){
			unset($arrayUserData['avatar']);
		}
		$oldUserData = UserExtend::factory()
								 ->where('user_id', $userData['user_id'])
								 ->findOne();
		if (!$oldUserData) {
			$oldUserData = UserExtend::create($arrayUserData);
		} else {
			$oldUserData->set($arrayUserData);
		}
		$oldUserData->save();
	}
}
