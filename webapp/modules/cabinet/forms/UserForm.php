<?php
namespace webapp\modules\cabinet\forms;

use system\core\base\Form;
use system\core\HTML\InputTag;
use system\core\HTML\TextareaTag;

/**
 * Class CoursesForm
 * @package webapp\modules\courses\forms
 */
class UserForm extends Form
{

	public function elements()
	{
		return [
			'first_name'   => [
				'type'       => InputTag::className(),
				'label'      => 'Фамилия',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'first_name',
					'required'  => 1,
					'maxlength' => '128',
					'class'     => 'form-control',
				],
			],
			'last_name'    => [
				'type'       => InputTag::className(),
				'label'      => 'Фамилия',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'last_name',
					'required'  => 1,
					'maxlength' => '128',
					'class'     => 'form-control',
				],
			],
			'phone'        => [
				'type'       => InputTag::className(),
				'label'      => 'Телефон',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'phone',
					'maxlength' => '64',
					'class'     => 'form-control',
				],
			],
			'work_phone'   => [
				'type'       => InputTag::className(),
				'label'      => 'Рабочий телефон',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'work_phone',
					'maxlength' => '64',
					'class'     => 'form-control',
				],
			],
			'passport'     => [
				'type'       => InputTag::className(),
				'label'      => 'Серия и номер паспорта',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'passport',
					'maxlength' => '64',
					'class'     => 'form-control',
				],
			],
			'passport_ext' => [
				'type'       => InputTag::className(),
				'label'      => 'Кем и когда выдан',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'passport_ext',
					'maxlength' => '64',
					'class'     => 'form-control',
				],
			],
			'avatar'       => [
				'type'       => InputTag::className(),
				'label'      => 'Аватарка',
				'attributes' => [
					'type'  => 'file',
					'name'  => 'avatar',
					'class' => 'btn btn-primary',
				],
			],
			'submit'       => [
				'type'       => InputTag::className(),
				'label'      => '',
				'attributes' => [
					'type'  => 'submit',
					'class' => 'btn btn-primary',
					'value' => 'Сохранить',
				],
			],
		];
	}
}
