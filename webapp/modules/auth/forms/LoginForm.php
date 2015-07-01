<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 08.05.15
 * Time: 17:47
 */
namespace webapp\modules\auth\forms;

use system\core\base\Form;
use system\core\HTML\InputTag;

/**
 * Class LoginForm
 * @package webapp\modules\adm\forms
 * @property-read string $pass  пароль
 * @property-read string $email логин
 */
class LoginForm extends Form
{

	/**
	 * Метод, в котором определяется список полей формы
	 * @return array
	 */
	function elements()
	{
		return [
			'email'           => [
				'type'       => InputTag::className(),
				'label'      => 'Имя пользователя',
				'attributes' => [
					'name'      => 'email',
					'type'      => 'text',
					'autofocus' => 1,
				],
			],
			'pass'            => [
				'type'       => InputTag::className(),
				'label'      => 'Пароль',
				'attributes' => [
					'name' => 'pass',
					'type' => 'password',
				],
			],
			'submit'          => [
				'type'       => InputTag::className(),
				'label'      => '',
				'attributes' => [
					'type'  => 'submit',
					'value' => 'Войти',
				],
			],
		];
	}
}
