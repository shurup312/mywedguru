<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 * @property integer $id
 * @property integer $site
 * @property string  $socialid
 * @property string  $token
 * @property integer $status
 * @property integer $type
 * @property string  $avatar
 * @property string  $date_created
 * @property string  $date_deleted
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_SOCIAL_APPROVE = 1;
    const STATUS_TYPE_USER_SELECT = 2;
    const STATUS_REGISTERED = 3;
    const SITE_VK = 1;
    const SITE_OK = 2;
    const SITE_FB = 3;
    const USER_TYPE_BRIDE = 1; //невесты
    const USER_TYPE_PHOTOGRAPHER = 2; //фотограф

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site', 'token'], 'required'],
            [['site', 'avatar', 'status', 'type'], 'integer'],
            [['date_created', 'date_deleted'], 'safe'],
            [['socialid'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'avatar'       => 'Аватарка',
            'site'         => 'ID соцсети',
            'socialid'     => 'ID пользователя в соцсети',
            'token'        => 'Токен для доступа к соц.сети',
            'status'       => 'Статус регистрации пользователя',
            'type'         => 'Тип пользователя',
            'date_created' => 'Date Created',
            'date_deleted' => 'Date Deleted',
        ];
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     *
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param mixed $token the token to be looked for
     * @param mixed $type  the type of the token. The value of this parameter depends on the implementation.
     *                     For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     *
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     * The space of such keys should be big enough to defeat potential identity attacks.
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * Validates the given auth key.
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @param string $authKey the given auth key
     *
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
