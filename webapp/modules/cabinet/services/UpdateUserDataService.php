<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 02.07.2015
 * Time: 16:21
 */
namespace webapp\modules\cabinet\services;

use Exception;
use system\core\App;
use system\core\base\Service;
use system\core\helpers\ArrayHelper;
use system\core\helpers\ImageHelper;
use webapp\modules\cabinet\models\UserExtend;
use webapp\modules\cabinet\models\UserExtendHistory;
use webapp\modules\users\models\User;

class UpdateUserDataService extends Service
{

	public $userFiles = false;
	public $userData = false;
	/**
	 * @var UserExtend
	 */
	public $baseUser;
	private $user;
	protected $isModerate = true;

	/**
	 * Процесс поиска и заполнение его результатами аттрибута results
	 * @return array
	 * @throws Exception
	 */
	public function body()
	{
		if (!$this->userData) {
			throw new Exception('Не переданы данные для обновления.');
		}
		$this->saveUser();
		$this->results = [
			'user' => $this->baseUser,
			'userExtend' => $this->user,
		];
	}

	private function saveUser()
	{
		$this->getUser();
		$this->createUserIfNotExists();
		$this->setUserData();
		if ($this->isSendAvatar()) {
			$filename = $this->uploadImage();
			$this->user->set(['avatar' => $filename]);
		}
		$this->user->save();
		if (!$this->isModerate) {
			$historyDate = $this->user->asArray();
			unset($historyDate['id']);
			$historyDate = ArrayHelper::merge($historyDate, ['status' => UserExtendHistory::NOT_MODERATE_STATUS]);
			UserExtendHistory::create($historyDate)
							 ->save();
		}
	}

	private function getUser()
	{
		if($this->baseUser && !($this->baseUser instanceof User)){
			throw new Exception('При обновлении данных кабинета в качестве пользователя переданы неверные данные.');
		}
		if(!$this->baseUser){
			$this->baseUser = App::get('user');
		}
		if (!$this->isModerate) {
			$this->user = UserExtend::factory()
									->where('user_id', $this->baseUser->id)
									->findOne();
		} else {
			UserExtendHistory::factory()
							 ->rawExecute('UPDATE '.UserExtendHistory::$table.' SET status='.UserExtendHistory::REWRITED_STATUS.' WHERE user_id='.$this->baseUser->id.' AND status='.UserExtendHistory::NOT_APPROVED_STATUS);
			$this->user = UserExtendHistory::create(
				[
					'user_id' => $this->baseUser->id,
					'status'  => UserExtendHistory::NOT_APPROVED_STATUS,
				]
			);
		}
	}

	private function createUserIfNotExists()
	{
		if (!$this->user) {
			$this->user = UserExtend::create(['user_id' => $this->baseUser->id]);
		}
	}

	private function setUserData()
	{
		$this->user->set(
			$this->userData
		);
	}

	/**
	 * @return bool
	 */
	private function isSendAvatar()
	{
		return $this->userFiles['tmp_name']['avatar'] && !empty($this->userFiles['tmp_name']['avatar']);
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	private function uploadImage()
	{
		$imagePath     = $this->userFiles['tmp_name']['avatar'];
		$image         = new ImageHelper($imagePath);
		$imageName     = microtime(true);
		$directoryName = App::module()->config['imageFolder'];
		$filename      = $imageName.'.'.$image->get_original_info()['format'];
		if (!file_exists($directoryName)) {
			mkdir($directoryName, 0777);
		}
		$image->thumbnail(App::module()->config['thmbWidth'], App::module()->config['thmbHeight'])
			  ->save($directoryName.$filename);
		return $filename;
	}
}
