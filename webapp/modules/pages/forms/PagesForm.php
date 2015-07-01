<?php
namespace webapp\modules\pages\forms;

use system\core\base\Form;
use system\core\HTML\CheckBoxListTag;
use system\core\HTML\InputCheckboxList;
use system\core\HTML\InputTag;
use system\core\HTML\SelectTag;
use system\core\HTML\Tag;
use system\core\HTML\TextareaTag;
use webapp\modules\users\models\User;

/**
 * Class PagesForm
 * @package webapp\modules\pages\forms
 */
class PagesForm extends Form
{

	public function elements()
	{
		return [
			'menu_name'        => [
				'type'       => InputTag::className(),
				'label'      => 'Название в меню',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'menu_name',
					'required'  => 1,
					'maxlength' => '255',
					'class'     => 'form-control',
				],
			],
			'title'            => [
				'type'       => InputTag::className(),
				'label'      => 'Название',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'title',
					'required'  => 1,
					'maxlength' => '255',
					'class'     => 'form-control',
				],
			],
			'content'          => [
				'type'       => TextareaTag::className(),
				'label'      => 'Текст',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'content',
					'required'  => 1,
					'maxlength' => '65535',
					'class'     => 'form-control',
					'rows'      => '5',
				],
			],
			'url'              => [
				'type'       => InputTag::className(),
				'label'      => 'chpu',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'url',
					'maxlength' => '255',
					'class'     => 'form-control',
				],
			],
			'meta_title'       => [
				'type'       => InputTag::className(),
				'label'      => 'Meta Title',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'meta_title',
					'maxlength' => '255',
					'class'     => 'form-control',
				],
			],
			'meta_keywords'    => [
				'type'       => TextareaTag::className(),
				'label'      => 'Meta Keywords',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'meta_keywords',
					'maxlength' => '65535',
					'class'     => 'form-control',
					'rows'      => '3',
				],
			],
			'meta_description' => [
				'type'       => TextareaTag::className(),
				'label'      => 'Meta Description',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'meta_description',
					'maxlength' => '65535',
					'class'     => 'form-control',
					'rows'      => '3',
				],
			],
			'rights'           => [
				'type'       => SelectTag::className(),
				'label'      => 'Права',
				'attributes' => [
					'type'   => 'checkbox',
					'name'   => 'rights',
					'value'  => User::SUPER_RIGHTS,
					'values' => [
						[
							'name'  => 'SUPER_RIGHT',
							'value' => User::SUPER_RIGHTS,
						],
						[
							'name'  => 'ADMIN_RIGHT',
							'value' => User::ADMIN_RIGHTS,
						],
						[
							'name'  => 'USERS_RIGHT',
							'value' => User::USER_RIGHTS,
						],
						[
							'name'  => 'GUEST_RIGHT',
							'value' => User::GUEST_RIGHTS,
						],
					],
				],
			],
			'submit'           => [
				'type'       => InputTag::className(),
				'label'      => '',
				'attributes' => [
					'type'  => 'submit',
					'value' => 'Сохранить',
					'class' => 'btn btn-success',
				],
			],
		];
	}
}
