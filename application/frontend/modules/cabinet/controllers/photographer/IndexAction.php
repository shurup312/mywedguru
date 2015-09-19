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
use infrastructure\studio\components\StudioRepository;
use yii\base\Action;

class IndexAction extends Action
{

    public function run()
    {
        /**
         * @var Person $person
         */
        $person = CommandBusList::getPersonCommanBus()->handle(new GetCurrentPersonCommand());
        $studio = StudioRepository::getByPerson($person);
        return $this->controller->render($person->type()->prefix().'/index', ['person' => $person, 'studio' => $studio,]);
    }
}
