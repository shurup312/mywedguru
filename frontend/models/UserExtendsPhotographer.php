<?php
namespace frontend\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "user_extends_photographers".
 * @property integer $id
 * @property integer $user_id
 * @property string  $first_name
 * @property string  $last_name
 * @property string  $studio_name
 * @property string  $site_name
 * @property string  $email
 * @property string  $phone
 * @property string  $avatar
 * @property string  $about
 * @property string  $date_birth
 * @property string  $date_created
 * @property string  $date_deleted
 */
class UserExtendsPhotographer extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_extends_photographers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'first_name', 'last_name', 'phone', 'email'], 'required'],
            [['email'], 'email'],
            [['user_id'], 'integer'],
            [['date_created', 'date_deleted'], 'safe'],
            [['about'], 'string'],
            [['first_name', 'last_name', 'studio_name', 'site_name', 'email', 'phone'], 'string', 'max' => 255],
            [['avatar'], 'string', 'max' => 64],
            [['date_birth'], 'date','format'=>'Y-m-d'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'user_id'      => 'ID пользователя',
            'first_name'   => 'Имя',
            'last_name'    => 'Фамилия',
            'studio_name'  => 'Название студии',
            'site_name'    => 'URL сайта',
            'email'        => 'E-mail',
            'phone'        => 'Телефон',
            'avatar'       => 'Аватарка',
            'about'        => 'О себе',
            'date_birth'   => 'Дата рождения',
            'date_created' => 'Date Created',
            'date_deleted' => 'Date Deleted',
        ];
    }

    public function beforeValidate()
    {
        $this->date_birth = (new DateTime($this->date_birth))->format('Y-m-d');
        return parent::beforeValidate();
    }
}
