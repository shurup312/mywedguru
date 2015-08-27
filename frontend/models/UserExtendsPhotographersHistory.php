<?php
namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_extends_photographers_history".
 * @property integer $id
 * @property integer $user_id
 * @property integer $action_user_id
 * @property string  $first_name
 * @property string  $last_name
 * @property string  $studio_name
 * @property string  $site_name
 * @property string  $email
 * @property string  $phone
 * @property string  $avatar
 * @property string  $about
 * @property string  $date_birth
 * @property integer $status
 * @property string  $date_created
 * @property string  $date_deleted
 */
class UserExtendsPhotographersHistory extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_extends_photographers_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'action_user_id', 'status'], 'required'],
            [['user_id', 'action_user_id', 'status'], 'integer'],
            [['date_created', 'date_deleted'], 'safe'],
            [['first_name', 'last_name', 'studio_name', 'site_name', 'email', 'phone'], 'string', 'max' => 255],
            [['avatar'], 'string', 'max' => 64],
            [['date_birth'], 'date'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'user_id'        => 'ID пользователя',
            'action_user_id' => 'ID кто заапрувил или отменил',
            'first_name'     => 'Имя',
            'last_name'      => 'Фамилия',
            'studio_name'    => 'Название студии',
            'site_name'      => 'URL сайта',
            'email'          => 'E-mail',
            'phone'          => 'Телефон',
            'avatar'         => 'Аватарка',
            'status'         => 'Status',
            'about'          => 'О себе',
            'date_birth'     => 'Дата рождения',
            'date_created'   => 'Date Created',
            'date_deleted'   => 'Date Deleted',
        ];
    }
}
