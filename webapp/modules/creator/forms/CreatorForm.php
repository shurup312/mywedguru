<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 08.05.15
 * Time: 14:06
 */
namespace webapp\modules\creator\forms;

use system\core\base\Form;
use system\core\HTML\InputTag;

/**
 * Class AddForm
 * @package webapp\modules\form\forms
 * @property string name
 * @property string table
 */
class CreatorForm extends Form
{

	public function elements()
	{
		return [
			'name'  => [
				'type'       => InputTag::className(),
				'label'      => 'Имя модуля',
				'attributes' => [
					'name'      => 'name',
					'required'  => 1,
					'maxlength' => '64',
					'class'     => 'form-control',
				],
			],
			'title'  => [
				'type'       => InputTag::className(),
				'label'      => 'Заголовок в админке',
				'attributes' => [
					'name'      => 'title',
					'required'  => 1,
					'maxlength' => '64',
					'class'     => 'form-control',
				],
			],
			'table'  => [
				'type'       => InputTag::className(),
				'label'      => 'Имя таблицы',
				'attributes' => [
					'name'      => 'table',
					'required'  => 1,
					'maxlength' => '64',
					'class'     => 'form-control',
				],
			],
			'submit'      => [
				'type'       => InputTag::className(),
				'label'      => '',
				'attributes' => [
					'type'  => 'submit',
					'value' => 'Создать',
					'class' => 'btn btn-success',
				],
			],
		];
	}
}
