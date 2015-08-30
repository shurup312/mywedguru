<?php
namespace app\modules\cabinet\controllers;

use app\modules\repositories\PersonRepository;
use frontend\models\User;
use frontend\models\UserType;
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
        $personModel = PersonRepository::getByUserId(\Yii::$app->getUser()->identity->getId());
        return $this->render(UserType::$prefix[\Yii::$app->getUser()->identity->type].'/index', ['personModel' => $personModel]);
    }

    public function actionEdit()
    {
        /**
         * @var User $userModel
         */
        $userModel       = \Yii::$app->getUser()->identity;
        $userExtendModel = $userModel->userExtend;
        if($userExtendModel->load(\Yii::$app->request->post()) && $userExtendModel->save()){
            \Yii::$app->response->redirect(URL::toRoute('index'));
        }
        return $this->render(UserType::$prefix[$userModel->user_type].'/edit', ['userExtendModel' => $userExtendModel]);
    }
}
