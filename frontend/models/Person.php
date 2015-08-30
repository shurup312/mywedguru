<?php
namespace frontend\models;

use Yii;

/**
 * This is the model class for table "person".
 * @property integer $id
 * @property integer $user_id
 * @property string  $first_name
 * @property string  $last_name
 * @property integer $sex
 * @property string  $date_birth
 * @property string  $mob_phone
 * @property string  $phone
 * @property string  $address
 * @property string  $email
 * @property integer $contact_id
 * @property string  $about
 * @property string  $date_created
 * @property string  $date_deleted
 * @property Users   $user
 */
class Person extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'sex', 'contact_id'], 'integer'],
            [['date_birth', 'mob_phone', 'phone', 'address', 'email', 'date_created', 'date_deleted'], 'safe'],
            [['about'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'user_id'      => 'Пользователь',
            'first_name'   => 'Имя',
            'last_name'    => 'Фамилия',
            'sex'          => 'Пол',
            'date_birth'   => 'Дата рождения',
            'mob_phone'    => 'Мобильный телефон',
            'phone'        => 'Домашний телефон',
            'address'      => 'Адрес',
            'email'        => 'E-mail',
            'contact_id'   => 'Контакты',
            'about'        => 'Обо мне',
            'date_created' => 'Date Created',
            'date_deleted' => 'Date Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
