<?php
namespace app\modules\userDetails\controllers;

use app\ddd\person\services\GetPersonService;
use app\ddd\studio\repositories\StudioRepository;
use app\ddd\studio\services\CreateStudioByPersonService;
use app\modules\userDetails\forms\StudioForm;
use Exception;
use frontend\ddd\person\repositories\PersonRepository;
use frontend\models\UserType;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

class PhotographerController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'         => true,
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->getUser()->identity->type == UserType::USER_PHOTOGRAPGER;
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $studioRepository = (new StudioRepository());
        $personRepository = (new PersonRepository());
        $person           = (new GetPersonService())->execute();
        $studio           = $studioRepository->getByPerson($person);
        if ($studio !== null) {
            \Yii::$app->response->redirect('/cabinet');
            \Yii::$app->end();
        }
        $studioForm = new StudioForm();
        if ($studioForm->load(\Yii::$app->request->post()) && $studioForm->validate()) {
            $transaction = \Yii::$app->getDb()->beginTransaction();
            try {
                $service = new CreateStudioByPersonService($studioRepository, $personRepository, $studio, $person);
                $service->execute($studioForm->name, $studioForm->phone, $studioForm->address);
                $transaction->commit();
                \Yii::$app->response->redirect(URL::toRoute('/cabinet'));
            } catch (Exception $e) {
                $studioForm->addError('name', $e->getMessage());
            }
            $transaction->rollBack();
        }
        return $this->render('studio', ['studioForm' => $studioForm,]);
    }
}
