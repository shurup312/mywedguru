<?php
namespace frontend\models;

use Yii;

/**
 * This is the model class for table "studio_owner".
 * @property integer $id
 * @property integer $studio_id
 * @property integer $person_id
 * @property string  $date_created
 * @property string  $date_deleted
 * @property Person  $person
 * @property Studio  $studio
 */
class StudioOwner extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'studio_owner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studio_id'], 'required'],
            [['studio_id', 'person_id'], 'integer'],
            [['date_created', 'date_deleted'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'studio_id'    => 'Студия',
            'person_id'    => 'Владелец',
            'date_created' => 'Date Created',
            'date_deleted' => 'Date Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudio()
    {
        return $this->hasOne(Studio::className(), ['id' => 'studio_id']);
    }
}
