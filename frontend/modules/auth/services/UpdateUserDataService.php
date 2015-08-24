<?php
namespace frontend\modules\auth\services;
use common\services\Service;

/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 10.08.2015
 * Time: 23:48
 */
class UpdateUserDataService extends Service
{
	public $userModel;
	public $userExtensionModel;
	public $isModerate;

	/**
	 * Процесс поиска и заполнение его результатами аттрибута results
	 * @return array
	 */
	public function body()
	{
		$this->getUser();
		$this->createUserIfNotExists();
		$this->updateHistoryAsOld();
		$this->createNewHistory();
		$this->saveAvatar();

	}

	private function getUser()
	{
	}

	private function createUserIfNotExists()
	{
	}

	private function updateHistoryAsOld()
	{
	}

	private function createNewHistory()
	{
	}

	private function saveAvatar()
	{
	}
}
