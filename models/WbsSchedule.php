<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WbsSchedule".
 *
 * @property int $idWbsSchedule
 * @property int $idEstimateWorkPackages
 * @property int $idWbs
 * @property string $WbsBegin
 * @property string $WBSEnd
 */
class WbsSchedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'WbsSchedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idEstimateWorkPackages', 'idWbs'], 'integer'],
            [['WbsBegin', 'WBSEnd'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idWbsSchedule' => 'Id Wbs Schedule',
            'idEstimateWorkPackages' => 'Id Estimate Work Packages',
            'idWbs' => 'Id Wbs',
            'WbsBegin' => 'Дата начала',
            'WBSEnd' => 'Дата окончания',
        ];
    }
    /*
     * возвращает дату достижения результата для заданной оценки трудозатрат
     * 
     * 
     */ 
    public function getWbsEndDate($idEstimateWorkPackages,$idWbs){
		$sql = 'SELECT WBSEnd FROM Yii2pmis.WbsSchedule where idEstimateWorkPackages ='.$idEstimateWorkPackages.' and idWbs ='.$idWbs;
		$Results = Yii::$app->db->createCommand($sql)->queryScalar();		
		if($Results){
			$dend = \DateTime::createFromFormat('Y-m-d', $Results);
			return $dend;
		 } else{
			 return false;
			 }
		
		
		
	}
     /*
     * возвращает все даты достижения результата 
     * 
     * 
     */ 
    public function getWbsEndDatelist($LastEstimateId,$idWbs){
		$sql = 'SELECT WBSEnd,idEstimateWorkPackages FROM Yii2pmis.WbsSchedule where idWbs ='.$idWbs. ' order by WBSEnd desc';
		$Results = Yii::$app->db->createCommand($sql)->queryAll();		
		if($Results){
			$dendList = '';
			foreach($Results as $res){
				if($res['idEstimateWorkPackages'] == $LastEstimateId){
						$dendList = $dendList.'<b>'.$res['WBSEnd'].'</b><br>';  //выделяем  дату из актуальной оценки
					} else{
						$dendList = $dendList.' '.$res['WBSEnd'].'<br>';
					}
				
				}
			
			return $dendList;
		 } else{
			 return ' ';
			 }
		
		
		
	}
}
