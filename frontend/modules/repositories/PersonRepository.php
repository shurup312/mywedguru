<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace app\modules\repositories;

use frontend\models\Person;

class PersonRepository
{

    /**
     * @param $userID
     *
     * @return Person|null
     */
    public static function getByUserId($userID){
        return Person::findOne($userID);
    }

    /**
     * @param Person $model
     *
     * @return bool
     */
    public static function save(Person &$model)
    {
        return $model->save();
    }
}
