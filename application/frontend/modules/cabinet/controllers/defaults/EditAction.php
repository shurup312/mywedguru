<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 19:16
 */
namespace app\modules\cabinet\controllers\defaults;

use cabinet\components\Action;
use cabinet\forms\photographer\PersonForm;
use infrastructure\person\commands\UpdatePersonRawCommand;
use infrastructure\person\components\PersonRepository;
use yii\helpers\Url;

class EditAction extends Action
{

    public function run()
    {
        $person = PersonRepository::getByUser(\Yii::$app->getUser()->identity);
        $model  = new PersonForm();
        $model->setAttributes($person->asArray());
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if($this->getPersonCommandBus()->handle(new UpdatePersonRawCommand($person, $model->firstName, $model->lastName, $model->mobPhone, $model->phone,$model->dateBirth,$model->email,$model->address, $model->about))){
                \Yii::$app->response->redirect(URL::toRoute('index'));
                \Yii::$app->end();
            }
            \Yii::$app->session->setFlash('notice', 'Не удалось сохранить данные');
        }
        return $this->controller->render($person->type()->prefix().'/edit', ['model' => $model,]);
    }
}
