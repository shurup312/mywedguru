<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 30.08.2015
 * Time: 13:02
 */
namespace app\ddd\person\factories;

use app\ddd\exceptions\exceptions\FactoryException;
use Exception;
use frontend\models\Person;
use frontend\models\User;

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
