<?php
namespace app\models;
use yii\base\Model;

class SignupForm extends Model{
 
 public $username;
 public $password;
 public $retypepassword;
 public $email;
 
 public function rules() {
	return [
		 [['username', 'password','retypepassword', 'email'], 'required',  'message' => 'Заполните поле'],
		 ['username', 'string', 'min' => 3, 'message' =>'не меньше 3хс имволов'],
		 ['retypepassword','compare', 'compareAttribute' => 'password', 'message' =>'Введенные значения не совпадают'],
		 ['email', 'email', 'message' =>'Email  не корректный'],
		 ['username', 'match', 'pattern' => '~^[A-Za-z][A-Za-z0-9]+$~','message'=>'Должно состоять только из букв и цифр'],
	];
 }
 
 public function attributeLabels() {
	 return [
		 'username' => 'Логин',
		 'password' => 'Пароль',
		 'retypepassword' => 'Пароль еще раз',
		 'email'=>'Email пользователя'
	 ];
 }
 
}
