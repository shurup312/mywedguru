<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace app\ddd\studio\repositories;

use app\ddd\interfaces\Repository;
use frontend\models\Person;
use frontend\models\StudioOwner;

class StudioRepository extends Repository
{

    /**
     * @param Person $person
     *
     * @return \frontend\models\Studio|null
     */
    public function getByPerson(Person $person)
    {
        /**
         * @var StudioOwner $studioOwner
         */
        $studioOwner = StudioOwner::find()->where(['person_id' => $person->id])->one();
        if (!$studioOwner) {
            return null;
        }
        return $studioOwner->studio;
    }
}
