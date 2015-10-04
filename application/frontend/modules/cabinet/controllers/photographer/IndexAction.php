<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 18:08
 */
namespace cabinet\controllers\photographer;

use domain\person\entities\Person;
use infrastructure\common\components\CommandBusList;
use infrastructure\person\commands\GetCurrentPersonCommand;
use infrastructure\person\components\PersonRepository;
use infrastructure\studio\components\StudioRepository;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class IndexAction extends Action
{

    public function run($slug)
    {
        /**
         * @var Person $person
         */
        $person       = CommandBusList::getPersonCommanBus()->handle(new GetCurrentPersonCommand());
        $studio       = StudioRepository::getByPerson($person);
        $personBySlug = PersonRepository::getBySlug($slug);

        $isOwner = $person->equalsTo($personBySlug);
        return $this->controller->render($personBySlug->user()->type()->prefix().'/index',
            ['person' => $personBySlug, 'studio' => $studio, 'isOwner' => $isOwner]);
    }
}
