<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 16:32
 */
namespace app\modules\factories;

use app\modules\aggregates\BrideAggregate;
use app\modules\entities\Wedding;
use frontend\models\Person;
use frontend\models\User;

/**
 * @property BrideAggregate aggregate
 */
class BrideFactory
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     *
     * @return Person
     */
    public function create($firstName, $lastName)
    {
        $bride = $this->createBride($firstName, $lastName);
        $groom = $this->createGroom();
        $this->createWedding($bride, $groom);
        return $bride;
    }

    private function createBride($firstName, $lastName)
    {
        $bride             = new Person();
        $bride->user_id    = $this->user->id;
        $bride->first_name = $firstName;
        $bride->last_name  = $lastName;
        $bride->save();
        return $bride;
    }

    private function createGroom()
    {
        $groom = new Person();
        $groom->save();
        return $groom;
    }

    private function createWedding(Person $bride, Person $groom)
    {
        $wedding          = new Wedding();
        $wedding->brideID = $bride->id;
        $wedding->groomID = $groom->id;
    }
}
/**
 * Как правило, атрибуты СУЩНОСТИ, не обязательные для ее идентификации, можно до­
 * бавить позже, а не при создании.
 * Конструкторов может быть несколько  - все кастомные, для разных параметров
 */
