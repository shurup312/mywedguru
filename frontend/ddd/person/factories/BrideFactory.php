<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 16:32
 */
namespace app\ddd\person\factories;

use app\ddd\person\aggregates\BrideAggregate;
use frontend\models\Person;
use frontend\models\User;
use frontend\models\Wedding;

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
        $wedding = $this->createWedding();
        $brideAggregate = new BrideAggregate($bride, $groom, $wedding);
        return $brideAggregate;
    }

    /**
     * @param $firstName
     * @param $lastName
     *
     * @return Person
     */
    private function createBride($firstName, $lastName)
    {
        $bride             = new Person();
        $bride->user_id    = $this->user->id;
        $bride->first_name = $firstName;
        $bride->last_name  = $lastName;
        return $bride;
    }

    /**
     * @return Person
     */
    private function createGroom()
    {
        $groom             = new Person();
        return $groom;
    }

    /**
     * @return Wedding
     */
    private function createWedding()
    {
        $wedding             = new Wedding();
        return $wedding;
    }
}
/**
 * Как правило, атрибуты СУЩНОСТИ, не обязательные для ее идентификации, можно до­
 * бавить позже, а не при создании.
 * Конструкторов может быть несколько  - все кастомные, для разных параметров
 */
