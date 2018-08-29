<?php

namespace app\models;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{
  	public function rules()
		{
		return [
			['username', 'required'],
			['username', 'unique','message'=>'Пользователь с таким именем уже есть'],
			['username', 'string', 'min' => 3, 'message'=>'Минимум 3 символа'],
			['username', 'match', 'pattern' => '~^[A-Za-z][A-Za-z0-9]+$~', 'message'
			=> 'Username can contain only alphanumeric characters.'],
			[['username', 'password_hash', 'password_reset_token'],
			'string', 'max' => 255
			],
			['auth_key', 'string', 'max' => 32],
			//['email','string','max'=>7],
		];
		}


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
       return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
		throw new NotSupportedException('"findIdentityByAccessToken" is not	implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
       return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }
    
    /**
	* Generates password hash from password and sets it to the model
	*
	* @param string $password
	*/
	public function setPassword($password)
	{
		$this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
	}
	/**
	* Generates "remember me" authentication key
	*/
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->getSecurity()->generateRandomString();
	}
	/**
	* Generates new password reset token
	*/
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->getSecurity()->generateRandomString() . '_' . time();
	}
	public static function findByPasswordResetToken($token)
	{
		$expire = Yii::$app->params['user.passwordResetTokenExpire'];
		$parts = explode('_', $token);
		$timestamp = (int) end($parts);
		if ($timestamp + $expire < time()) {
			return null;
		}
		return static::findOne(['password_reset_token' => $token]);
	}
}
//}
