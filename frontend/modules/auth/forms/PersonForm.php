<?php
namespace frontend\modules\auth\forms;

use yii\base\Model;

/**
 * Class StudioForm
 *
 * @package app\modules\userDetails\forms
 * @property string $name
 * @property string $phone
 * @property string $address
 */
class PersonForm extends Model
{

    public $first_name;
    public $last_name;
    public $phone;
    public $email;

    public function rules()
    {
        return [
            [['first_name', 'last_name',], 'required'],
            [['phone', 'first_name', 'last_name',], 'string', 'max' => 32],
            [['email',], 'string', 'max' => 128],
        ];
    }

    public function attributeLabels()
    {
        return [
            'first_name' => 'Имя',
            'last_name'  => 'Фамилия',
            'phone'      => 'Телефон',
            'email'      => 'E-mail',
        ];
    }
}
