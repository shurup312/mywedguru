<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace app\modules\repositories;

use app\modules\aggregates\StudioAggregate;
use frontend\models\Person;
use frontend\models\StudioOwner;

class StudioRepository
{

    public static function getByPerson(Person $person)
    {
        /**
         * @var StudioOwner $studioOwner
         */
        $studioOwner = StudioOwner::find()
                                  ->where(['person_id' => $person->id])
                                  ->one();
        if (!$studioOwner) {
            return null;
        }
        $studio = $studioOwner->studio;
        return new StudioAggregate($studio, $studioOwner);
    }
}
