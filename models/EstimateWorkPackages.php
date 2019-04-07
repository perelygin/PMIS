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
            [['ewp_comment'], 'safe'],
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
            'finished' =>'Статус',
            'ewp_comment' =>'Примечание'
        ];
    }
    /*
     * возвращает название BR, по которой делалась оценка
     */
    public function getBrInfo()
    {
		$BR = BusinessRequests::findOne($this->idBR);
			
		$BrInfo = array('BRName' => $BR->BRName,
					    'BRNumber'=> $BR->BRNumber,
					    'EstimateName'=>$this->EstimateName,
					    'dataEstimate'=>$this->dataEstimate,
					    'finished'=>$this->isFinished() ? 'Закрыта':'Активна',
					    'BRDateBegin'=>$BR->BRDateBegin);
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
	/*
	 * возвращает максимальную дату в расписании 
	 * 
	 * 
	 */ 
	public function getScheduleLastData($idEWP){
		$sql = 'select 
		 sch.WorkBegin,
		 sch.WorkEnd,
		 sch.idWorksOfEstimate,
		 woe.idEstimateWorkPackages
		 from Schedule  as sch
		LEFT OUTER JOIN WorksOfEstimate woe ON sch.idWorksOfEstimate = woe.idWorksOfEstimate
		where woe.idEstimateWorkPackages = '.$idEWP.' order by sch.WorkEnd desc';
		$Results = Yii::$app->db->createCommand($sql)->queryOne();		
		return $Results;
	}
	/*
	 * формирует строку состоящую из тэгов <td></td> 
	 * Число тэгов определяется числом дней между $dataBRBeg,$dataBREnd
	 * один день - один тэг
	 * тэги соотв. выходным окрашиваются красным
	 * тэги соотв. работе окраш. синим
	 */ 
	 public function getDayRowTable($dataBRBeg,$dataBREnd,$dataWorkBeg,$dataWorkEnd){
		 $str='';
		  $dBRBeg = \DateTime::createFromFormat('Y-m-d', $dataBRBeg);
		  $dBREnd = \DateTime::createFromFormat('Y-m-d', $dataBREnd);
		  $dWorkBeg = \DateTime::createFromFormat('Y-m-d', $dataWorkBeg);
		  $dWorkEnd = \DateTime::createFromFormat('Y-m-d', $dataWorkEnd);
		  $d = new \DateInterval('P1D');  //интервал в один день
		  $dateCurent = $dBRBeg;
		  $dif = $dateCurent->diff($dBREnd);
		  while($dif->invert != 1){
			  //текущая дата относится к интервалу работы?
			  $dif_wb = $dateCurent->diff($dWorkBeg);
			  $dif_we = $dateCurent->diff($dWorkEnd);
			  if(Weekends::isWeekend($dateCurent)) {
				   $str = $str.'<td bgcolor="#fb1c0d">&nbsp</td>';
				  }elseif($dif_wb->invert == 1 && $dif_we->invert != 1){
					 $str = $str.'<td bgcolor="#4f7db8">&nbsp</td>';  
					  }else{
						  $str = $str.'<td>&nbsp</td>';
						  }
			  $dateCurent->add($d); 
			  $dif = $dateCurent->diff($dBREnd);
		  }
		 return $str;
	 }	 
}
