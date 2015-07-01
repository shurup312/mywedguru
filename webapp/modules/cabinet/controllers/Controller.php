<?php
namespace webapp\modules\cabinet\controllers;

use system\core\App;
use system\core\base\View;
use system\core\behaviors\AccessBehavior;
use system\core\helpers\ArrayHelper;
use system\core\helpers\ImageHelper;
use webapp\modules\cabinet\forms\UserForm;
use webapp\modules\cabinet\models\UserExtend;
use webapp\modules\users\models\User;

/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 01.07.2015
 * Time: 13:55
 */
class Controller extends \system\core\Controller
{

	public function behaviors()
	{
		return [
			'access' => [
				'class'  => AccessBehavior::className(),
				'rights' => [
					User::ADMIN_RIGHTS,
					User::USER_RIGHTS,
					User::SUPER_RIGHTS,
				],
			]
		];
	}

	public function actionIndex()
	{
		$text = 'Данные пользователя';
		View::setDesign('admin');
		View::setDesignParams(
			[
				'header' => $text,
				'title'  => $text,
			]
		);
		$formName = 'userCabinet';
		$form     = new UserForm($formName);
		$userData = UserExtend::factory()
							  ->where('user_id', App::get('user')->id)
							  ->findArray();
		if ($userData) {
			$userData = $userData[0];
			$form->load($userData);
		}
		return View::withDesign(
			'index', [
					   'user' => $userData,
					   'form' => $form,
				   ]
		);
	}

	public function actionSave()
	{
		$formName = 'userCabinet';
		if (!App::request()
				->post($formName)
		) {
			$this->redirect('/cabinet');
		}
		$userData = UserExtend::factory()
							  ->where('user_id', App::get('user')->id)
							  ->findOne();
		if (!$userData) {
			$userData = UserExtend::create(['user_id' => App::get('user')->id]);
		}
		$userData->set(
			App::request()
			   ->post($formName)
		);
		if (isset($_FILES[$formName]['tmp_name']['avatar'])) {
			$imagePath     = $_FILES[$formName]['tmp_name']['avatar'];
			$image         = new ImageHelper($imagePath);
			$imageName     = microtime(true);
			$directoryName = App::module()->config['imageFolder'];
			$filename      = $imageName.'.'.$image->get_original_info()['format'];
			if (!file_exists($directoryName)) {
				mkdir($directoryName, 0777);
			}
			$image->thumbnail(App::module()->config['thmbWidth'], App::module()->config['thmbHeight'])
				  ->save($directoryName.$filename);
			$userData->set(['avatar' => $filename]);
		}
		$userData->save();
		$this->redirect('/cabinet');
	}
}
