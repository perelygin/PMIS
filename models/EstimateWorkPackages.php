<?php

namespace app\models;

use Yii;

/**
 * Пакет оцениваемых работ. включает в себя перечень работ и их оценку на дату
 * This is the model class for table "EstimateWorkPackages".
 *
 * @property int $idEstimateWorkPackages
 * @property string $dataEstimate
 * @property string $EstimateName Наименование оценки
 * @property int $idBR
 *
 * @property BusinessRequests $bR
 * @property WorksOfEstimate[] $worksOfEstimates
 */
class EstimateWorkPackages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'EstimateWorkPackages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // [['dataEstimate'], 'safe'],
            [['dataEstimate'], 'required','message' => 'Пожалуйста, укажите дату оценки'],
            [['EstimateName'], 'required','message' => 'Пожалуйста, укажите название оценки'],
            [['idBR','deleted','finished'], 'integer'],
            [['EstimateName'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idEstimateWorkPackages' => 'Id Estimate Work Packages',
            'dataEstimate' => 'Дата оценки',
            'EstimateName' => 'Наименование оценки',
            'idBR' => 'Id Br',
            'finished' =>'Статус'
        ];
    }
    /*
     * возвращает название BR, по которой делалась оценка
     */
    public function getBrInfo()
    {
		$BR = BusinessRequests::findOne($this->idBR);
		$BrInfo = array('BRName' => $BR->BRName,'BRNumber'=>$BR->BRNumber,'EstimateName'=>$this->EstimateName,'dataEstimate'=>$this->dataEstimate);
        return $BrInfo;
    }
    /*
     * возвращает список работ по пакету работ и результату
     */
    public function getWorksList($idWbs)
    {
	     $sql = "SELECT * FROM WorksOfEstimate
					where idEstimateWorkPackages =".$this->idEstimateWorkPackages." and idWbs = ".$idWbs ;
		$WorksList = Yii::$app->db->createCommand($sql)->queryAll();		
		
		return $WorksList;	
		
    }
    /*
     * возвращает true пакет оценок закрыт
     * 
     */ 
    public function isFinished()
    {
		 if($this->finished == 1){
			 return true;
			 } else return false;
	}
}
