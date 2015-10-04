<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 16:12
 */
namespace userDetails\controllers\photographer;

use domain\person\entities\Person;
use domain\person\specifications\IsSetStudioSpecification;
use domain\studio\entities\Studio;
use Exception;
use infrastructure\common\components\CommandBusList;
use infrastructure\person\commands\GetCurrentPersonCommand;
use infrastructure\person\commands\UpdatePersonCommand;
use infrastructure\studio\commands\CreateStudioCommand;
use userDetails\components\Action;
use userDetails\forms\StudioForm;
use yii\helpers\Url;

class IndexAction extends Action
{

    public function run()
    {
        /**
         * @var Person $person
         */
        $person           = CommandBusList::getPersonCommanBus()->handle(new GetCurrentPersonCommand());
        if (IsSetStudioSpecification::withoutStudio($person)) {
            \Yii::$app->response->redirect(URL::toRoute('/cabinet/'.$person->user()->slug()));
            \Yii::$app->end();
        }
        $studioForm = new StudioForm();
        if ($studioForm->load(\Yii::$app->request->post()) && $studioForm->validate()) {
            $transaction = \Yii::$app->getDb()->beginTransaction();
            try {
                /**
                 * @var Studio $studio
                 */
                $studio = $this->getStudioCommandBus()->handle(new CreateStudioCommand($studioForm->name, $studioForm->phone, $studioForm->address));
                $person->setStudioId($studio->id());
                $this->getPersonCommandBus()->handle(new UpdatePersonCommand($person));
                $transaction->commit();
                \Yii::$app->response->redirect(URL::toRoute('/cabinet'));
            } catch (Exception $e) {
                $studioForm->addError('name', $e->getMessage());
            }
            $transaction->rollBack();
        }
        return $this->controller->render('studio', ['studioForm' => $studioForm,]);
    }
}
