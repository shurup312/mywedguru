<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 02.07.2015
 * Time: 19:49
 */
namespace webapp\modules\cabinet\services;

use system\core\base\Service;
use webapp\modules\cabinet\models\UserExtendHistory;

class RejectUserChangeService extends Service
{

	public $rejectUserID;

	/**
	 * Процесс поиска и заполнение его результатами аттрибута results
	 * @return array
	 */
	public function body()
	{
		$userData = UserExtendHistory::findOne($id);
		if (!$userData) {
			return ;
		}
		$userData->set(
			[
				'action_user_id' => $this->rejectUserID,
				'status'         => UserExtendHistory::IS_REJECTED_STATUS,
				'date_deleted'   => date('Y-m-d H:i:s'),
			]
		);
		$userData->save();
	}
}
