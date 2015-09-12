<?php
namespace userDetails\forms;

use yii\base\Model;

/**
 * Class StudioForm
 *
 * @package app\modules\userDetails\forms
 *
 * @property string $name
 * @property string $phone
 * @property string $address
 */
class StudioForm extends Model
{
    public $name;
    public $phone;
    public $address;

    public function rules()
    {
        return [
            [['name',], 'required'],
            [['name'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 32],
            [['address'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'    => 'Название студии',
            'phone'   => 'Телефон',
            'address' => 'Адрес студии',
        ];
    }
}
