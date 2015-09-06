<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 06.09.2015
 * Time: 18:18
 */
namespace app\ddd\person\services;

use frontend\ddd\person\repositories\PersonRepository;
use frontend\models\Person;

class GetPersonService
{

    /**
     * @return Person
     */
    public function execute()
    {
        return (new PersonRepository())->getByID(\Yii::$app->getUser()->identity->getId());
    }
}
