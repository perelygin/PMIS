<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class PersonalCabinetForm extends Model
{
     public $username;
	 public $password;
	 public $retypepassword;
	 public $email;
	 public $mantisname;
	 public $mantispwd;
   
    /**
     * @return array the validation rules.
     */
    public function rules() {
	return [
		 [['username','mantisname','email'], 'required',  'message' => 'Заполните поле'],
		 [['mantisname','mantispwd','password','retypepassword'],'safe'],
		 ['username', 'string', 'min' => 3, 'message' =>'не меньше 3х символов'],
		// ['retypepassword','compare', 'compareAttribute' => 'password','message' =>'Введенные значения не совпадают'],  //не работает без 'password'  required
		 ['email', 'email', 'message' =>'Email  не корректный'],
		 ['username', 'match', 'pattern' => '~^[A-Za-z][A-Za-z0-9]+$~','message'=>'Должно состоять только из букв и цифр'],
		 
	];
 }
 
 public function attributeLabels() {
	 return [
		 'username' => 'Логин',
		 'password' => 'Пароль',
		 'retypepassword' => 'Пароль еще раз',
		 'email'=>'Email пользователя',
		 'mantisname'=>'Логин в mantis',
		 'mantispwd' =>'Пароль в mantis'
	 ];
 }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    //public function contact($email)
    //{
        //if ($this->validate()) {
            //Yii::$app->mailer->compose()
                //->setTo($email)
                //->setFrom([$this->email => $this->name])
                //->setSubject($this->subject)
                //->setTextBody($this->body)
                //->send();

            //return true;
        //}
        //return false;
    }

