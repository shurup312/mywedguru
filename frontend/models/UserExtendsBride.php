<?php
namespace frontend\models;

use DateTime;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_extends_brides".
 * @property integer $id
 * @property integer $user_id
 * @property string  $first_name
 * @property string  $fiance_first_name
 * @property string  $last_name
 * @property string  $fiance_last_name
 * @property string  $avatar
 * @property string  $date_wedding
 * @property string  $date_created
 * @property string  $date_deleted
 * @property string  $date_birth
 */
class UserExtendsBride extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_extends_brides';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'date_wedding', 'first_name', 'fiance_first_name', 'last_name', 'fiance_last_name'], 'required'],
            [['user_id'], 'integer'],
            [['date_created', 'date_deleted'], 'safe'],
            [['first_name', 'fiance_first_name', 'last_name', 'fiance_last_name'], 'string', 'max' => 255],
            [['avatar'], 'string', 'max' => 64],
            [['date_birth', 'date_wedding'], 'date', 'format' => 'Y-m-d'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'user_id'           => 'ID пользователя',
            'first_name'        => 'Имя',
            'fiance_first_name' => 'Имя жениха',
            'last_name'         => 'Фамилия',
            'fiance_last_name'  => 'Фамилия жениха',
            'avatar'            => 'Avatar',
            'date_wedding'      => 'Дата свадьбы',
            'date_birth'        => 'Дата рождения',
            'date_created'      => 'Date Created',
            'date_deleted'      => 'Date Deleted',
        ];
    }

    public function beforeValidate()
    {
        if ($this->date_birth) {
            $this->date_birth = (new DateTime($this->date_birth))->format('Y-m-d');
        }
        if($this->date_wedding){
            $this->date_wedding= (new DateTime($this->date_wedding))->format('Y-m-d');
        }
        return parent::beforeValidate();
    }
}
