<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class Weekend_generator extends Model
{
    public $DateBegin;
	public $DateEnd;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['DateBegin', 'DateEnd'], 'required'],
            [['DateBegin', 'DateEnd'], 'date'],
        ];
    }
}
