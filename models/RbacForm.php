<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class RbacForm extends Model
{
    public $name;
   
   
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name'], 'safe'],
       
            
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
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

