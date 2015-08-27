<?php
namespace frontend\modules\auth\services;

use common\services\Service;
use Exception;
use frontend\models\abstracts\UserFactory;
use frontend\models\User;
use frontend\models\UserExtendsBride;
use frontend\models\UserExtendsPhotographer;

/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 10.08.2015
 * Time: 23:48
 */

/**
 * Class UpdateUserDataService
 * @package frontend\modules\auth\services
 * @property User                                     $userModel
 * @property UserExtendsBride|UserExtendsPhotographer $userExtensionModel
 */
class UpdateUserDataService extends Service
{

    public $userModel;
    public $userExtensionModel;
    public $isModerate;

    /**
     * Процесс поиска и заполнение его результатами аттрибута results
     * @return array
     * @throws Exception
     */
    public function body()
    {
        try {
            $this->getUser();
            $this->createUserIfNotExists();
            $this->saveUser();
            $this->updateHistoryAsOld();
            $this->createNewHistory();
            $this->saveAvatar();
            $this->results = true;
        } catch (Exception $e) {
            $this->addError($e->getMessage());
            $this->results = false;
        }
    }

    private function getUser()
    {
        if (!$this->userModel) {
            throw new Exception('Не передан пользователь.');
        }
    }

    private function createUserIfNotExists()
    {
        if ($this->userExtensionModel) {
            return;
        }
        $this->userExtensionModel          = UserFactory::getModelByType($this->userModel->user_type);
        $this->userExtensionModel->user_id = $this->userModel->id;
        if (!$this->userExtensionModel->save()) {
            throw new Exception('Пользовать не был создан.');
        }
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

    private function saveUser()
    {
        if (!$this->userExtensionModel->save()) {
            throw new Exception('Не удалось сохранить данные пользователя');
        }
    }
}
