<?php

namespace domain\wedding\entities;

/**
 * This is the model class for table "wedding".
 *
 * @property integer $id
 * @property integer $groom_id
 * @property integer $bride_id
 * @property string $date_created
 * @property string $date_deleted
 * @property string $date
 *
 * @property Person $groom
 * @property Person $bride
 */
class Wedding
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wedding';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['groom_id', 'bride_id'], 'required'],
            [['groom_id', 'bride_id'], 'integer'],
            [['date_created', 'date_deleted', 'date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'groom_id' => 'Жених',
            'bride_id' => 'Невеста',
            'date_created' => 'Date Created',
            'date_deleted' => 'Date Deleted',
            'date' => 'Дата свадьбы',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroom()
    {
        return $this->hasOne(Person::className(), ['id' => 'groom_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBride()
    {
        return $this->hasOne(Person::className(), ['id' => 'bride_id']);
    }
}
