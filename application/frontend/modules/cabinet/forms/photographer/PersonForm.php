<?php
namespace cabinet\forms\photographer;

use yii\base\Model;

class PersonForm extends Model
{

    public $dateBirth;
    public $firstName;
    public $lastName;
    public $mobPhone;
    public $phone;
    public $address;
    public $email;
    public $about;

    public function rules()
    {
        return [
            [['firstName', 'lastName'], 'required'],
            [['firstName', 'lastName', 'mobPhone', 'phone','dateBirth'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 128],
            [['address', 'about'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'firstName' => 'Имя',
            'lastName'  => 'Фамилия',
            'mobPhone'  => 'Номер мобильного телефона',
            'phone'     => 'Номер телефона',
            'address'   => 'Адрес',
            'email'     => 'E-mail',
            'dateBirth' => 'Дата рождения',
            'about'     => 'О себе',
        ];
    }
}
