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
	 * тэги соотв. работе($dataWorkBeg,$dataWorkEnd) окраш. синим 
	 * для первого дня работы выводится подсказка о задачах предшествениках str1
	 * для последнего дня работы выводится подсказка о последующих задачах  str2
	 * для всех остальных дней выводится информация о работе
	 */ 
	 public function getDayRowTable($dataBRBeg,$dataBREnd,$dataWorkBeg,$dataWorkEnd,$rowType=1,$str1='',$str2='',$str3=''){
		 $str='';
		  $dBRBeg = \DateTime::createFromFormat('Y-m-d', $dataBRBeg);
		  $dBREnd = \DateTime::createFromFormat('Y-m-d', $dataBREnd);
		  $dWorkBeg = \DateTime::createFromFormat('Y-m-d', $dataWorkBeg);
		  $dWorkEnd = \DateTime::createFromFormat('Y-m-d', $dataWorkEnd);
		  $d = new \DateInterval('P1D');  //интервал в один день
		  $dateCurent = $dBRBeg;
		  $dif = $dateCurent->diff($dBREnd);
		  while($dif->invert != 1){
			  if($rowType==1){ //строка с работой
					  //текущая дата относится к интервалу работы?
					  $dif_wb = $dateCurent->diff($dWorkBeg);
					  $dif_we = $dateCurent->diff($dWorkEnd);
					  if(Weekends::isWeekend($dateCurent)) {
						   $str = $str.'<td bgcolor="#fb1c0d">&nbsp&nbsp&nbsp</td>';
						  }elseif(($dif_wb->invert == 1 && $dif_we->invert != 1)||($dif_wb->days == 0)||($dif_we->days == 0)){
							 if($dif_wb->days == 0){
								 $str = $str.'<td bgcolor="#4f7db8" title ="'.$str1.'">&nbsp&nbsp&nbsp</td>';  
								 } elseif($dif_we->days == 0){
									 $str = $str.'<td bgcolor="#4f7db8" title = "'.$str3.'">&nbsp&nbsp&nbsp</td>';  
									 } else{
										 $str = $str.'<td bgcolor="#4f7db8" title = "'.$str2.'">&nbsp&nbsp&nbsp</td>';  
										 }
							 
							  }else{
								  $str = $str.'<td>&nbsp&nbsp&nbsp</td>';
								  }
				  } else if($rowType==2){  //строка с результатом
					   if(Weekends::isWeekend($dateCurent)) {  //выходной?
						   $str = $str.'<td bgcolor="#fb1c0d">&nbsp&nbsp&nbsp</td>';
						   } else{
							    $str = $str.'<td>&nbsp&nbsp&nbsp</td>';
							   }
					  } else if($rowType==3){  //строка заголовка
					   if(Weekends::isWeekend($dateCurent)) {  //выходной?
						   $str = $str.'<th "><div class="xuybex">'.$dateCurent->format('j.m').'</div></th>';
						   } else{
							    $str = $str.'<th>&nbsp&nbsp&nbsp</th>';
							   }
					  }
			    
			  
			  $dateCurent->add($d); 
			  $dif = $dateCurent->diff($dBREnd);
		  }
		 return $str;
	 }
	/*
	 * возвращает перечень связей между работами в пакете 
	 * 
	 */ 
	public function getEWPlinks(){
		$sql = 'select * from Links where idEstimateWorkPackages='.$this->idEstimateWorkPackages;
		$Results = Yii::$app->db->createCommand($sql)->queryAll();		
		return $Results;
	} 
	/*
	 * 
	 * возвращает id работы по id работы с котрой она была скопирована.
	 * 
	 */
	 public function getWork_WhoseCopy($id_woe){
		 $sql = 'select idWorksOfEstimate from WorksOfEstimate  
					where idEstimateWorkPackages = '.$this->idEstimateWorkPackages. ' and WhoseCopy = '.$id_woe;
		$Results = Yii::$app->db->createCommand($sql)->queryScalar();		
		return $Results;
	 
	} 
	
	
	 	 
}
