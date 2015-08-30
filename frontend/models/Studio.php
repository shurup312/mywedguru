<?php
namespace frontend\models;

use Yii;

/**
 * This is the model class for table "studio".
 * @property integer $id
 * @property string  $name
 * @property string  $phone
 * @property string  $address
 * @property string  $date_created
 * @property string  $date_deleted
 */
class Studio extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'studio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['date_created', 'date_deleted'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 32],
            [['address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'name'         => 'Название студии',
            'phone'        => 'Телефон',
            'address'      => 'Адрес',
            'date_created' => 'Date Created',
            'date_deleted' => 'Date Deleted',
        ];
    }
}
