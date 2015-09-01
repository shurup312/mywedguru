<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 16:32
 */
namespace app\ddd\person\factories;

use app\ddd\person\aggregates\PhotographerAggregate;
use app\ddd\studio\aggregates\StudioAggregate;
use app\ddd\studio\factories\StudioFactory;
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
     * @param string $firstName
     * @param string $lastName
     *
     * @return Person
     */
    public function create($firstName, $lastName)
    {
        $photograper = $this->createPhotographer($firstName, $lastName);
        $studioAggregate = $this->createStudio($photograper);
        $this->createPhotogallery();
        $aggregate = new PhotographerAggregate($photograper, $studioAggregate, null);
        return $aggregate;
    }

    private function createPhotographer($firstName, $lastName)
    {
        $photographer             = new Person();
        $photographer->user_id    = $this->user->id;
        $photographer->first_name = $firstName;
        $photographer->last_name  = $lastName;
        return $photographer;
    }

    private function createPhotogallery()
    {
    }

    /**
     * @param Person $photographer
     *
     * @return StudioAggregate
     */
    private function createStudio(Person $photographer)
    {
        return (new StudioFactory())->create($photographer);
    }
}
/**
 * Как правило, атрибуты СУЩНОСТИ, не обязательные для ее идентификации, можно до­
 * бавить позже, а не при создании.
 * Конструкторов может быть несколько  - все кастомные, для разных параметров
 */
