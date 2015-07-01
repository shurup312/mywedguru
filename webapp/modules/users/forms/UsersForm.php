<?php
namespace webapp\modules\users\forms;

use system\core\base\Form;
use system\core\HTML\InputTag;
use system\core\HTML\Select;
use system\core\HTML\SelectTag;
use system\core\HTML\TextareaTag;

/**
 * Class Users_controllForm
 * @package webapp\modules\users_controll\forms
 */
class UsersForm extends Form
{

	public function elements()
	{
		$select = [
			[
				'name' => 'n',
				'id'   => 'n',
			],
			[
				'name' => 'a',
				'id'   => 'a',
			],
			[
				'name' => 'd',
				'id'   => 'd',
			],
			[
				'name' => 'p',
				'id'   => 'p',
			],
		];
		return [
			'login'  => [
				'type'       => InputTag::className(),
				'label'      => 'Login',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'login',
					'required'  => 1,
					'maxlength' => '32',
					'class'     => 'form-control',
				],
			],
			'pass'   => [
				'type'       => InputTag::className(),
				'label'      => 'Password',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'pass',
					'maxlength' => '32',
					'class'     => 'form-control',
					'value'     => '',
				],
			],
			'email'  => [
				'type'       => InputTag::className(),
				'label'      => 'E-Mail',
				'attributes' => [
					'type'     => 'email',
					'name'     => 'email',
					'required' => 1,
					'class'    => 'form-control',
				],
			],
			'rights' => [
				'type'       => SelectTag::className(),
				'label'      => 'Rights',
				'attributes' => [
					'type'     => 'number',
					'name'     => 'rights',
					'required' => 1,
					'class'    => 'form-control',
					'value'    => 1,
					'values'   => [
						[
							'name' => 'SUPER_RIGHT',
							'id'   => _SUPER_RIGHT_,
						],
						[
							'name' => 'ADMIN_RIGHT',
							'id'   => _ADMIN_RIGHT_,
						],
						[
							'name' => 'USERS_RIGHT',
							'id'   => _USERS_RIGHT_,
						],
						[
							'name' => 'GUEST_RIGHT',
							'id'   => _GUEST_RIGHT_,
						],
					],
				],
			],
			'status' => [
				'type'       => SelectTag::className(),
				'label'      => 'Status',
				'attributes' => [
					'type'      => 'text',
					'name'      => 'status',
					'required'  => 1,
					'maxlength' => '1',
					'class'     => 'form-control',
					'value'     => '1',
					'values'    => $select
				],
			],
			'submit' => [
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
