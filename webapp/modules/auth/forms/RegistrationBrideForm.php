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

class RegistrationBrideForm extends Form
{

	/**
	 * Метод, в котором определяется список полей формы
	 * @return array
	 */
	function elements()
	{
		$array = [
			'first_name'        => [
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
			'last_name'         => [
				'type'       => InputTag::className(),
				'label'      => 'Фамилия',
				'attributes' => [
					'name'    => 'last_name',
					'type'    => 'text',
					'require' => 1,
					'class'   => 'form-control',
				],
			],
			'fiance_first_name' => [
				'type'       => InputTag::className(),
				'label'      => 'Имя жениха',
				'attributes' => [
					'name'      => 'fiance_first_name',
					'type'      => 'text',
					'autofocus' => 1,
					'require'   => 1,
					'class'     => 'form-control',
				],
			],
			'fiance_last_name'  => [
				'type'       => InputTag::className(),
				'label'      => 'Фамилия жениха',
				'attributes' => [
					'name'    => 'fiance_last_name',
					'type'    => 'text',
					'require' => 1,
					'class'   => 'form-control',
				],
			],
			'date_wedding'      => [
				'type'       => InputTag::className(),
				'label'      => 'Дата свадьбы',
				'attributes' => [
					'name'    => 'date_wedding',
					'type'    => 'text',
					'require' => 1,
					'class'   => 'form-control',
				],
			],
			'submit'            => [
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
