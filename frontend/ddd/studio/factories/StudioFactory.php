<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 31.08.2015
 * Time: 10:56
 */
namespace app\ddd\studio\factories;

use app\ddd\studio\aggregates\StudioAggregate;
use frontend\models\Person;
use frontend\models\Studio;
use frontend\models\StudioOwner;

class StudioFactory
{
    private $person;
    public function create(Person $person)
    {
        $studioOwner = new StudioOwner();
        $studioOwner->person_id = $person->id;
        $studio = new Studio();
        return new StudioAggregate($studio, $studioOwner);
    }
}
