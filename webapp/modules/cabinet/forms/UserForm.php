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
				'label'      => 'Имя',
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
