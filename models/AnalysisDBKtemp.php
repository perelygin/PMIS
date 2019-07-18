<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "AnalysisDBKtemp".
 *
 * @property int $idAnalysisDBKtemp
 * @property string $Title
 * @property string $Text
 */
class AnalysisDBKtemp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'AnalysisDBKtemp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Text'], 'string'],
            [['Title'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idAnalysisDBKtemp' => 'Id Analysis Dbktemp',
            'Title' => 'Title',
            'Text' => 'Text',
        ];
    }
    public function truncateTempTable(){
		 
		return Yii::$app->db->createCommand('truncate AnalysisDBKtemp')->execute();
	}
	/*
	 * возвращает все записи в  таблице
	 * 
	 */ 
	public function getRecords(){
		$sql ='SELECT idAnalysisDBKtemp,Text,Title FROM AnalysisDBKtemp'; 
		return Yii::$app->db->createCommand($sql)->queryAll();	
	}
	/**
	 * 
	 */
	public function getRecordCount(){
		$sql ='SELECT count(idAnalysisDBKtemp) FROM AnalysisDBKtemp'; 
		return Yii::$app->db->createCommand($sql)->queryScalar();	
	}  
	/*
	 * Возвращает информацию о требовании по id
	 * 
	 * 
	 */ 
	public function getReq($id){
		$sql ='SELECT Text,Title FROM AnalysisDBKtemp where idAnalysisDBKtemp='.$id; 
		return Yii::$app->db->createCommand($sql)->queryOne();	
	}
	
}



