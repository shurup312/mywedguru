<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_extends_brides_history".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $action_user_id
 * @property string $first_name
 * @property string $fiance_first_name
 * @property string $last_name
 * @property string $fiance_last_name
 * @property string $avatar
 * @property string $date_wedding
 * @property integer $status
 * @property string $date_created
 * @property string $date_deleted
 */
class UserExtendsBridesHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_extends_brides_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'action_user_id', 'status'], 'required'],
            [['user_id', 'action_user_id', 'status'], 'integer'],
            [['date_wedding', 'date_created', 'date_deleted'], 'safe'],
            [['first_name', 'fiance_first_name', 'last_name', 'fiance_last_name'], 'string', 'max' => 255],
            [['avatar'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ID пользователя',
            'action_user_id' => 'ID кто заапрувил или отменил',
            'first_name' => 'Имя',
            'fiance_first_name' => 'Имя жениха',
            'last_name' => 'Фамилия',
            'fiance_last_name' => 'Фамилия жениха',
            'avatar' => 'Avatar',
            'date_wedding' => 'Date Wedding',
            'status' => 'Status',
            'date_created' => 'Date Created',
            'date_deleted' => 'Date Deleted',
        ];
    }
}
