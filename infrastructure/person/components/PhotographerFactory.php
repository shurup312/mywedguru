<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 16:32
 */
namespace app\ddd\person\components;

use frontend\models\Person;
use frontend\models\User;

class PhotographerFactory
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $aFirstName
     * @param string $aLastName
     *
     * @return Person
     */
    public function create($aFirstName, $aLastName)
    {
        $photographer             = new Person();
        $photographer->user_id    = $this->user->id;
        $photographer->firstName = $aFirstName;
        $photographer->lastName  = $aLastName;
        return $photographer;
    }
}
/**
 * Как правило, атрибуты СУЩНОСТИ, не обязательные для ее идентификации, можно до­
 * бавить позже, а не при создании.
 * Конструкторов может быть несколько  - все кастомные, для разных параметров
 */
