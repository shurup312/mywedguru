<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 18:08
 */
namespace cabinet\controllers\defaults;

use infrastructure\person\components\PersonRepository;
use infrastructure\studio\components\StudioRepository;
use yii\base\Action;

class IndexAction extends Action
{

    public function run()
    {
        $person = PersonRepository::getByUser(\Yii::$app->getUser()->identity);
        $studio = StudioRepository::getByPerson($person);
        return $this->controller->render($person->type()->prefix().'/index', ['person' => $person, 'studio' => $studio,]);
    }
}
