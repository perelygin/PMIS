<?php
namespace app\models;
use yii\base\Model;

class MoveWorksToAnotherResultForm extends Model{
 
 public $NewResult;
 
 
 public function rules() {
	return [
		 [['NewResult'], 'required',  'message' => 'Выбери результат для перемещения работ'],
		];
 }
 
 public function attributeLabels() {
	 return [
		 'NewResult' => 'Результат, в который перемещаются работы',
		];
 }
 
}
