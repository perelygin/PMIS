<?php

namespace app\models;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{
	const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    
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
			['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['mantisname','mantispwd','mantisnonce'],'string'],
			//['email','string','max'=>7],
		];
		}

	public function afterSave($insert, $changedAttributes){
	    parent::afterSave($insert, $changedAttributes);
	      //echo($this->id);  die;
	      
	      if ($insert) { //присваеваем новому пользователю роль - analyst
		      $userRole = Yii::$app->authManager->getRole('analyst');
			  Yii::$app->authManager->assign($userRole, $this->id);
		  }
	    //... тут ваш код
	}

    /**
     * {@inheritdoc}
     */
    //public static function findIdentity($id)
    //{
       //return static::findOne($id);
    //}

	public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
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
    //public static function findByUsername($username)
    //{
        //return static::findOne(['username' => $username]);
    //}

	public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
       return $this->getPrimaryKey();
    }
    
    
    
    
    public function getUserName()
    {
       return $this->username();
    }
    public function getUserMantisName()
    {
       return $this->mantisname;
    }
    public function getMantisPwd()
    {
		 if(!empty($this->mantisname) and !empty($this->mantispwd)){
		  $mntpwd = base64_decode($this->mantispwd);
	      $secret_string = $this->username.$this->email;
			if(strlen($secret_string) < SODIUM_CRYPTO_SECRETBOX_KEYBYTES){
				$secret_key = str_pad($secret_string,SODIUM_CRYPTO_SECRETBOX_KEYBYTES,$this->mantisname);
				} else{
					$secret_key = substr($secret_string,0,SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
					}
		   $decrypted_mntpwd = sodium_crypto_secretbox_open($mntpwd, base64_decode($this->mantisnonce), $secret_key);
	       return $decrypted_mntpwd; 
		   } else{
			   return false;
			   }
		   
       
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
	//public function generatePasswordResetToken()
	//{
		//$this->password_reset_token = Yii::$app->getSecurity()->generateRandomString() . '_' . time();
	//}
	
	public function generatePasswordResetToken()
	{
	    $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}
	
	//public static function findByPasswordResetToken($token)
	//{
		//$expire = Yii::$app->params['user.passwordResetTokenExpire'];
		//$parts = explode('_', $token);
		//$timestamp = (int) end($parts);
		//if ($timestamp + $expire < time()) {
			//return null;
		//}
		//return static::findOne(['password_reset_token' => $token]);
	//}
	public static function findByPasswordResetToken($token)
	{
 
	    if (!static::isPasswordResetTokenValid($token)) {
	        return null;
	    }
	 
	    return static::findOne([
	        'password_reset_token' => $token,
	        'status' => self::STATUS_ACTIVE,
	    ]);
	}
	public static function isPasswordResetTokenValid($token)
	{
 
	    if (empty($token)) {
	        return false;
	    }
	 
	    $timestamp = (int) substr($token, strrpos($token, '_') + 1);
	    $expire = Yii::$app->params['user.passwordResetTokenExpire'];
	    return $timestamp + $expire >= time();
	}
	
	public function removePasswordResetToken()
	{
	    $this->password_reset_token = null;
	}
	
	public function encrypt($decrypted, $key)
	{
	  $a = sodium_crypto_secretbox_keygen();
	  return $a;
	}

	
}
//}
