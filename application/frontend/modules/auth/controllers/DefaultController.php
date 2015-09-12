<?php
namespace auth\controllers;

use domain\person\components\PersonFactory;
use frontend\modules\auth\forms\PersonForm;
use frontend\modules\socials\FB;
use frontend\modules\socials\fb\FacebookRedirectLoginHelper;
use frontend\modules\socials\fb\FacebookRequest;
use frontend\modules\socials\fb\FacebookRequestException;
use frontend\modules\socials\fb\FacebookSession;
use frontend\modules\socials\fb\GraphUser;
use frontend\modules\socials\OK;
use frontend\modules\socials\VK;
use infrastructure\person\components\PersonRepository;
use infrastructure\person\entities\User;
use yii\db\Exception;
use yii\helpers\Url;
use yii\web\Controller;

class DefaultController extends Controller
{

    public function actionIndex()
    {
        \yii::$app->session->set('1', 1);
        return $this->render('index');
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        \yii::$app->response->redirect('/auth/');
    }

    public function actionVk()
    {
        if (\yii::$app->request->get('error') || is_null(\yii::$app->request->get('code'))) {
            \yii::$app->response->redirect(\yii::$app->params['loginURL']);
        }
        $code   = \yii::$app->request->get('code');
        $config = \yii::$app->params['vkAPI'];
        if (!$userData = VK::getUserToken($config, $code)) {
            \Yii::$app->response->redirect('/auth');
        }
        if (!$userData->access_token) {
            \yii::$app->response->redirect('/auth');
        }
        $user = User::find()
                    ->where([
                        'site'     => User::SITE_VK,
                        'socialid' => $userData->user_id
                    ])
                    ->one();
        if (!$user) {
            $user           = new User();
            $user->site     = User::SITE_VK;
            $user->status   = User::STATUS_SOCIAL_APPROVE;
            $user->socialid = (string)$userData->user_id;
            $user->token    = (string)$userData->access_token;
            $isSaved        = $user->save();
            if (!$isSaved) {
                throw new Exception('Не удалось сохранить пользователя.');
            }
        } else {
            $user->token = $userData->access_token;
            $user->save();
        }
        \yii::$app->session->set('USER', $user);
        \yii::$app->response->redirect('/auth/checktype');
    }

    public function actionOk()
    {
        if (\yii::$app->request->get('error') || is_null(\yii::$app->request->get('code'))) {
            \yii::$app->response->redirect(\yii::$app->params['loginURL']);
        }
        $code     = \yii::$app->request->get('code');
        $config   = \yii::$app->params['okAPI'];
        $userData = OK::getUserToken($config, $code);
        if (!isset($userData->access_token)) {
            \yii::$app->response->redirect('/auth');
        }
        OK::setConfig($config);
        $currentUser = OK::getCurrentUser($userData->access_token);
        if (!isset($currentUser->uid)) {
            \yii::$app->response->redirect('/auth');
        }
        $user = User::find()
                    ->where([
                        'site'     => User::SITE_OK,
                        'socialid' => $currentUser->uid
                    ])
                    ->one();
        if (!$user) {
            $user           = new User();
            $user->site     = User::SITE_OK;
            $user->status   = User::STATUS_SOCIAL_APPROVE;
            $user->socialid = (string)$currentUser->uid;
            $user->token    = (string)$userData->refresh_token;
            $isSaved        = $user->save();
            if (!$isSaved) {
                throw new Exception('Не удалось сохранить пользователя.');
            }
        } else {
            $user->token = $userData->refresh_token;
            $user->save();
        }
        \yii::$app->session->set('USER', $user);
        \yii::$app->response->redirect('/auth/checktype');
    }

    public function actionFb()
    {
        \yii::$app->session->set('1', 1);
        $fbAPI = \yii::$app->params['fbAPI'];
        FacebookSession::setDefaultApplication($fbAPI['APPID'], $fbAPI['SECURITY_KEY']);
        $helper = new FacebookRedirectLoginHelper($fbAPI['redirectURL']);
        try {
            $session = $helper->getSessionFromRedirect();
        } catch (FacebookRequestException $ex) {
            \yii::$app->response->redirect(\yii::$app->params['loginURL']);
        } catch (\Exception $ex) {
            \yii::$app->response->redirect(\yii::$app->params['loginURL']);
        }
        if (!isset($session)) {
            \yii::$app->response->redirect(\yii::$app->params['loginURL']);
        }
        $currentUser = (new FacebookRequest($session, 'GET', '/me'))->execute()
                                                                    ->getGraphObject(GraphUser::className());
        if (is_null($currentUser->getId())) {
            \yii::$app->response->redirect('/auth');
        }
        $user = User::find()
                    ->where([
                        'site'     => User::SITE_FB,
                        'socialid' => $currentUser->getId()
                    ])
                    ->one();
        if (!$user) {
            $user           = new User();
            $user->site     = User::SITE_FB;
            $user->status   = User::STATUS_SOCIAL_APPROVE;
            $user->socialid = (string)$currentUser->getId();
            $user->token    = $session->getAccessToken();
            $isSaved        = $user->save();
            if (!$isSaved) {
                throw new Exception('Не удалось сохранить пользователя.');
            }
        } else {
            $user->token = $session->getAccessToken();
            $user->save();
        }
        \yii::$app->session->set('USER', $user);
        \yii::$app->response->redirect('/auth/checktype');
    }

    public function actionChecktype()
    {
        $this->validateSocialNetwork();
        $userType = \Yii::$app->session->get('USER')->type;
        if ($userType && \Yii::$app->session->get('USER')->status==User::STATUS_REGISTERED) {
            \Yii::$app->response->redirect('/auth/step1/'.$userType);
            \Yii::$app->end();
        }
        return $this->render('checktype');
    }

    public function actionStep1($userType)
    {
        $this->validateSocialNetwork();
        if (!$userType) {
            \yii::$app->response->redirect('/auth');
        }
        $registrationUser = \Yii::$app->session->get('USER');
        if ($registrationUser->type && $registrationUser->status==User::STATUS_REGISTERED) {
            \yii::$app->response->redirect('/auth/step2');
        }
        $currentUser       = $registrationUser;
        $currentUser->type = $userType;
        $currentUser->save();
        \yii::$app->response->redirect('/auth/step2');
        \Yii::$app->end();
    }

    public function actionStep2()
    {
        $this->validateSocialNetwork();
        $this->validateRegistrationStep();
        $site         = $this->getSocialNetwork();
        $userSocialID = \Yii::$app->session->get('USER')->socialid;
        $userData     = $site->getUser($userSocialID);
        $currentUser  = \Yii::$app->session->get('USER');
        $personRepository = new PersonRepository();
        if($model = $personRepository->getById($currentUser->person_id)) {
            \Yii::$app->response->redirect(URL::toRoute('/userDetails'));
            \Yii::$app->end();
        }
        $model = new PersonForm();
        $model->first_name = $userData->first_name;
        $model->last_name = $userData->last_name;
        if ($model->load(\yii::$app->request->post()) && $model->validate()) {
            $person = (new PersonFactory())->createEmpty();
            $person->setFirstName($model->first_name);
            $person->setLastName($model->last_name);
            $person->setPhone($model->phone);
            $person->setEmail($model->email);
            $personRepository->save($person);
            $currentUser->status = User::STATUS_REGISTERED;
            $currentUser->person_id = $person->id();
            $currentUser->save();
            \yii::$app->user->login($currentUser);
            \yii::$app->session->remove('USER');
            \yii::$app->response->redirect(URL::toRoute('/userDetails'));
            \Yii::$app->end();
        }
        $viewName = 'form';
        return $this->render($viewName, [
            'model' => $model,
        ]);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function validateSocialNetwork()
    {
        if (!\yii::$app->session->get('USER')) {
            \yii::$app->response->redirect('/auth');
        }
    }

    protected function validateRegistrationStep()
    {
        if (\Yii::$app->session->get('USER')->status==User::STATUS_REGISTERED) {
            \yii::$app->user->login(\Yii::$app->session->get('USER'));
            \Yii::$app->session->remove('USER');
            \yii::$app->response->redirect(URL::toRoute('/userDetails'));
            \Yii::$app->end();
        }
    }

    private function getSocialNetwork()
    {
        $site = false;
        switch (\Yii::$app->session->get('USER')->site) {
            case User::SITE_VK:
                $site = new VK();
                VK::setConfig(\yii::$app->params['vkAPI']);
                break;
            case User::SITE_OK:
                $site = new OK();
                OK::setConfig(\yii::$app->params['okAPI']);
                break;
            case User::SITE_FB:
                $site = new FB();
                FB::setConfig(\yii::$app->params['fbAPI']);
                break;
        }
        if (!$site) {
            \yii::$app->response->redirect('/auth');
        }
        return $site;
    }
}
