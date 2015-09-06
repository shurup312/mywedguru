<?php
namespace app\modules\cabinet\controllers;

use app\ddd\exceptions\exceptions\ServiceException;
use app\ddd\interfaces\exceptions\EntityException;
use app\ddd\person\services\GetPersonService;
use app\ddd\person\services\SavePersonService;
use app\ddd\studio\repositories\StudioRepository;
use app\modules\cabinet\forms\photographer\PersonForm;
use frontend\ddd\person\repositories\PersonRepository;
use frontend\models\User;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        /**
         * @var User $userModel
         */
        $person = (new GetPersonService())->execute();
        $studio = (new StudioRepository())->getByPerson($person);
        return $this->render($person->type()->prefix().'/index', ['person' => $person, 'studio' => $studio,]);
    }

    public function actionEdit()
    {
        $person = (new GetPersonService())->execute();
        $model = new PersonForm();
        $model->setAttributes($person->asArray());
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            try {
                (new SavePersonService($person, new PersonRepository()))->execute($model->getAttributes());
                \Yii::$app->response->redirect(URL::toRoute('index'));
                \Yii::$app->end();
            } catch (ServiceException $e){
            	\Yii::$app->session->setFlash('notice','Не удалось сохранить данные');
            } catch (EntityException $e){
            	\Yii::$app->session->setFlash('notice','Введенные данные ошибочны');
            }
        }
        return $this->render($person->type()->prefix().'/edit', ['model' => $model,]);
    }
}
