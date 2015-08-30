<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 30.08.2015
 * Time: 13:02
 */
namespace app\modules\factories;

use Exception;
use frontend\models\User;
use frontend\models\UserType;

/**
 * @property User user
 */
class PersonFactory
{

    private $user;

    /**
     * @param User $user
     *
     * @throws Exception
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $firstName
     * @param $lastName
     *
     * @return \frontend\models\Person
     * @throws Exception
     */
    public function create($firstName, $lastName)
    {
        $result = null;
        switch ($this->user->type) {
            case UserType::USER_BRIDE:
                $result = (new BrideFactory($this->user))->create($firstName, $lastName);
                break;
            case UserType::USER_PHOTOGRAPGER:
                $result = (new PhotographerFactory($this->user))->create($firstName, $lastName);
        }
        if ($result===null) {
            throw new Exception('У пользователя указан неверный тип.');
        }
        return $result;
    }
}
