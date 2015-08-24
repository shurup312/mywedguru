<?php
namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "images".
 * @property integer $id
 * @property integer $user_id
 * @property string  $path
 * @property string  $filename
 * @property string  $date_created
 * @property string  $date_deleted
 * @property Work[]  $works
 * @property Work[]  $works0
 */
class Image extends ActiveRecord
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'images';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[
				['user_id'],
				'integer'
			],
			[
				[
					'filename'
				],
				'required',
				'on'=>'add',
			],
			[
				[
					'date_created',
					'date_deleted'
				],
				'safe'
			],
			[
				[
					'path',
					'filename'
				],
				'string',
				'max' => 255
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'           => 'ID',
			'user_id'      => 'ID пользователя, загрузившего изображение',
			'path'         => 'Путь до изображения',
			'filename'     => 'Имя файла',
			'date_created' => 'Date Created',
			'date_deleted' => 'Date Deleted',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBride()
	{
		return $this->hasMany(UserExtendsBride::className(), ['avatar_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPhotographer()
	{
		return $this->hasMany(UserExtendsPhotographer::className(), ['avatar_id' => 'id']);
	}

	public function beforeSave($insert)
	{
		$this->user_id = \yii::$app->cuser->id;
		return true;
	}

	public function behaviors()
	{
		return [
			[
				'class'              => TimestampBehavior::className(),
				'createdAtAttribute' => 'date_created',
				'updatedAtAttribute' => null,
				'value'              => new Expression('NOW()'),
			],
		];
	}
}
