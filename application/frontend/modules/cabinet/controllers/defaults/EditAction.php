<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 19:16
 */
namespace cabinet\controllers\defaults;

use cabinet\forms\photographer\PersonForm;
use domain\person\entities\Person;
use infrastructure\common\components\CommandBusList;
use infrastructure\person\commands\GetCurrentPersonCommand;
use infrastructure\person\commands\UpdatePersonRawCommand;
use yii\base\Action;
use yii\helpers\Url;

class EditAction extends Action
{
    public function run()
    {
        /**
         * @var Person $person
         */
        $person = CommandBusList::getPersonCommanBus()->handle(new GetCurrentPersonCommand());
        $model  = new PersonForm();
        $model->setAttributes($person->asArray());
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            CommandBusList::getPersonCommanBus()->handle(new UpdatePersonRawCommand($person, $model->firstName, $model->lastName, $model->mobPhone, $model->phone,$model->dateBirth,$model->email,$model->address, $model->about));
            \Yii::$app->response->redirect(URL::toRoute('index'));
            \Yii::$app->end();
        }
        return $this->controller->render('edit', ['model' => $model,]);
    }
}
