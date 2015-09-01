<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 30.08.2015
 * Time: 13:02
 */
namespace app\modules\factories;

use app\modules\aggregates\BrideAggregate;
use app\modules\aggregates\PhotographerAggregate;
use app\modules\exceptions\FactoryException;
use app\modules\repositories\PersonRepository;
use Exception;
use frontend\models\Person;
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
        if(!$user->id){
            throw new FactoryException('Передан неуникализированный пользователь');
        }
        $this->user = $user;
    }

    /**
     * @param $firstName
     * @param $lastName
     *
     * @return Person
     * @throws Exception
     */
    public function create($firstName, $lastName)
    {
        $person = new Person();
        $person->user_id = $this->user->id;
        $person->first_name = $firstName;
        $person->last_name = $lastName;
        return $person;
    }
}
