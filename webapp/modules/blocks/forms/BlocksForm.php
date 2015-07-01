<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 08.05.15
 * Time: 14:06
 */
namespace webapp\modules\blocks\forms;

use system\core\base\Form;
use system\core\HTML\InputTag;
use system\core\HTML\TextareaTag;

/**
 * Class AddForm
 * @package webapp\modules\form\forms
 * @property string $title
 * @property string $description
 */
class BlocksForm extends Form
{

	public function elements()
	{
		return [
			'title'   => [
				'type'       => InputTag::className(),
				'label'      => 'Заголовок',
				'attributes' => [
					'name'      => 'title',
					'required'  => 1,
					'maxlength' => '64',
					'type'      => 'text',
					'class'     => 'form-control',
				],
			],
			'content' => [
				'type'       => TextareaTag::className(),
				'label'      => 'Контент',
				'attributes' => [
					'name'      => 'content',
					'required'  => 1,
					'maxlength' => '8192',
					'class'     => 'form-control ckeditor',
					'rows'      => 13,
				],
			],
			'submit'  => [
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
