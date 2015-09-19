<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 19.09.2015
 * Time: 14:12
 */
namespace app\modules\userDetails\controllers\bride;

use domain\person\entities\Person;
use domain\person\values\UserType;
use domain\wedding\entities\Wedding;
use infrastructure\person\commands\GetCurrentPersonCommand;
use infrastructure\person\commands\CreatePersonCommand;
use infrastructure\wedding\commands\CreateWeddingCommand;
use infrastructure\wedding\components\WeddingRepository;
use userDetails\components\Action;
use userDetails\forms\WeddingForm;

class IndexAction extends Action
{

    public function run()
    {
        /**
         * @var Person $bride
         */
        $bride   = $this->getPersonCommandBus()->handle(new GetCurrentPersonCommand());
        $wedding = WeddingRepository::getByBride($bride);
        if ($wedding instanceof Wedding) {
            \Yii::$app->response->redirect('/cabinet');
            \Yii::$app->end();
        }
        $weddingForm = new WeddingForm();
        if ($weddingForm->load(\Yii::$app->getRequest()->post()) && $weddingForm->validate()) {
            $transaction = \Yii::$app->getDb()->beginTransaction();
            $groom = $this->getPersonCommandBus()->handle(new CreatePersonCommand($weddingForm->groomFirstName(), $weddingForm->groomLastName()));
            $this->getWeddingCommandBus()->handle(new CreateWeddingCommand($groom, $bride, $weddingForm->date()));
            $transaction->commit();
            \Yii::$app->response->redirect('/cabinet');
            \Yii::$app->end();
        }
        return $this->controller->render('wedding', [
            'weddingForm' => $weddingForm,
        ]);
    }
}
