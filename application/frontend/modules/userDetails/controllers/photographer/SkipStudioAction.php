<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 18:20
 */
namespace app\modules\userDetails\controllers\photographer;

use domain\person\specifications\IsSetStudioSpecification;
use infrastructure\common\components\CommandBusList;
use infrastructure\person\commands\GetCurrentPersonCommand;
use infrastructure\person\commands\UpdatePersonCommand;
use infrastructure\person\components\PersonRepository;
use userDetails\components\Action;
use yii\helpers\Url;

class SkipStudioAction extends Action
{

    public function run()
    {
        $person = CommandBusList::getPersonCommanBus()->handle(new GetCurrentPersonCommand());
        $person->setStudioId(IsSetStudioSpecification::EMPTY_STUDIO_ID);
        $this->getPersonCommandBus()->handle(new UpdatePersonCommand($person));
        \Yii::$app->response->redirect(URL::toRoute('/cabinet/'.$person->user()->slug()));
    }
}
