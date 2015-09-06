<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 30.08.2015
 * Time: 13:02
 */
namespace app\ddd\person\factories;

use Exception;
use frontend\models\Person;
use frontend\models\User;

/**
 * @property User user
 */
class PersonFactory
{

    /**
     * @return Person
     * @throws Exception
     */
    public function create()
    {
        $person = new Person();
        return $person;
    }
}
