<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace app\modules\repositories;

use frontend\models\Person;
use frontend\models\User;

class PersonRepository extends Repository
{

    /**
     * @param User $user
     *
     * @return Person|null
     */
    public function getByUser(User $user){
        return Person::findOne($user->id);
    }
    /**
     * @param Person $model
     *
     * @return bool
     */
    public function save(Person $model)
    {
        return $model->save();
    }
}
