<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 09.07.2015
 * Time: 16:37
 */
namespace webapp\modules\auth\forms;

use system\core\base\Form;
use system\core\HTML\InputTag;
use system\core\HTML\SelectTag;
use webapp\modules\auth\models\UserKind;

class RegistrationPhotographerForm extends Form
{

	/**
	 * Метод, в котором определяется список полей формы
	 * @return array
	 */
	function elements()
	{
		$array = [
			'first_name'  => [
				'type'       => InputTag::className(),
				'label'      => 'Имя',
				'attributes' => [
					'name'      => 'first_name',
					'type'      => 'text',
					'autofocus' => 1,
					'require'   => 1,
					'class'     => 'form-control',
				],
			],
			'last_name'   => [
				'type'       => InputTag::className(),
				'label'      => 'Фамилия',
				'attributes' => [
					'name'    => 'last_name',
					'require' => 1,
					'type'    => 'text',
					'class'   => 'form-control',
				],
			],
			'studio_name' => [
				'type'       => InputTag::className(),
				'label'      => 'Название студии',
				'attributes' => [
					'name'  => 'studio_name',
					'type'  => 'text',
					'class' => 'form-control',
				],
			],
			'site_name'   => [
				'type'       => InputTag::className(),
				'label'      => 'Сайт',
				'attributes' => [
					'name'  => 'site_name',
					'type'  => 'text',
					'class' => 'form-control',
				],
			],
			'phone'       => [
				'type'       => InputTag::className(),
				'label'      => 'Телефон',
				'attributes' => [
					'name'    => 'phone',
					'require' => 1,
					'type'    => 'text',
					'class'   => 'form-control',
				],
			],
			'e-mail'      => [
				'type'       => InputTag::className(),
				'label'      => 'E-mail',
				'attributes' => [
					'name'    => 'e-mail',
					'require' => 1,
					'type'    => 'email',
					'class'   => 'form-control',
				],
			],
			'submit'      => [
				'type'       => InputTag::className(),
				'label'      => '',
				'attributes' => [
					'type'  => 'submit',
					'value' => 'Зарегистрироваться',
					'class' => 'btn btn-primary',
				],
			],
		];
		return $array;
	}
}
