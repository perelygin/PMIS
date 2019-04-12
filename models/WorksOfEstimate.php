<?php

namespace app\models;

use Yii;
use app\models\WorkEffort;
use app\models\Systemlog;
use app\models\Schedule;

/**
 * Перечень работ, которые входят в оценку на дату
 * This is the model class for table "WorksOfEstimate".
 *
 * @property int $idWorksOfEstimate
 * @property int $idEstimateWorkPackages
 * @property string $WorkName
 * @property int $idWbs
 * @property string $WorkDescription
 */
class WorksOfEstimate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'WorksOfEstimate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idEstimateWorkPackages', 'WorkName'], 'required'],
            [['idEstimateWorkPackages', 'idWbs', 'deleted'], 'integer'],
            [['WorkName','mantisNumber'], 'string', 'max' => 250],
            [['WorkDescription'], 'string', 'max' => 65535],
            [['mantisNumber'],'match','pattern'=>'#^[0-9]+$#' ],
           ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idWorksOfEstimate' => 'Id Works Of Estimate',
            'idEstimateWorkPackages' => 'Выбери оценку трудозатрат:',
            'WorkName' => 'Наименование работы',
            'idWbs' => 'Id Wbs',
            'WorkDescription' => 'Описание работы',
            'mantisNumber' => 'Номер инцидента в мантиссе',
            'ServiceType'=>'Тип услуги'
        ];
    }
   
	public function afterSave($insert, $changedAttributes){
	    parent::afterSave($insert, $changedAttributes);
	    $SysLog = new Systemlog();
	    $SysLog->IdTypeObject = 4; 
	    $SysLog->IdUser = Yii::$app->user->getId();;
	    $SysLog->DataChange = date("Y-m-d H:i:s");
	    $SysLog->idObject = $this->idWorksOfEstimate;
	    if($insert){
			$SysLog->SystemLogString = Yii::$app->user->identity->username.' => Работа добавлена: '.$this->idWorksOfEstimate.' '.$this->WorkName
			.' id Br:'.$this->idWbs
			.' id оценки: '.$this->idEstimateWorkPackages
			.' mantisNumber '.$this->mantisNumber;
			} else{
				$SysLog->SystemLogString = Yii::$app->user->identity->username.' => Работа изменена: '.$this->idWorksOfEstimate.' '.$this->WorkName
				.' id Br:'.$this->idWbs
				.' id оценки: '.$this->idEstimateWorkPackages
				.' mantisNumber '.$this->mantisNumber;
				}
			$SysLog->save();
	}
	
    public function beforeDelete(){
        if (parent::beforeDelete()){
			
			//смотрим наличие  записей в подчиненной таблице с трудозатратами	
            $WorkEffort = WorkEffort::find()->where(['idWorksOfEstimate' => $this->idWorksOfEstimate])->all();
            if(count($WorkEffort)>0){
				//Yii::$app->session->addFlash('error','ошибка удаления,  есть подчиненные записи' );
				//return false;
	            foreach ($WorkEffort as $model) {
					$model->delete();
				}	
			}
			$SysLog = new Systemlog();
		    $SysLog->IdTypeObject = 4; 
		    $SysLog->IdUser = Yii::$app->user->getId();;
		    $SysLog->DataChange = date("Y-m-d H:i:s");
		    $SysLog->idObject = $this->idWorksOfEstimate;
		    $SysLog->SystemLogString = Yii::$app->user->identity->username.' =>  Работа удалена: '.$this->idWorksOfEstimate.' '.$this->WorkName
			.' id Br:'.$this->idWbs
			.' id оценки: '.$this->idEstimateWorkPackages
			.' mantisNumber '.$this->mantisNumber;
			 $SysLog->save();
			 
            return true;
        }
        return false;
    }
   public function GetMantisNumber(){
	   return $this->mantisNumber;
	   }
	/*
	 * Возвращает информациюпо работе
	 * 
	 */    
	public function GetWOEInfo($idWorksOfEstimate){
		$sql = "SELECT 
						wbs.name,
						wbs.idBr,
						woe.idWorksOfEstimate,
						woe.WorkName,
						woe.mantisNumber
						FROM wbs  
						LEFT OUTER JOIN  WorksOfEstimate woe ON woe.idWbs=wbs.id
						where woe.idWorksOfEstimate= ".$idWorksOfEstimate;
		
		$WOEInfo = Yii::$app->db->createCommand($sql)->queryOne();		
		
		if($WOEInfo){
			if(!empty($WOEInfo)){
				
				return $WOEInfo;	
			} else {
				return '';
					}	
			} else {
				return '';
					}					
	   
	   }   
	public function get_analit_login()
    {
	    $sql = "SELECT 
					woe.idWorksOfEstimate,
				    rlm.RoleName,
					ppl.mantis_login,
					concat(ppl.Family,' ',ppl.Name) as fio
				FROM WorksOfEstimate as woe
				LEFT OUTER JOIN WorkEffort wef ON woe.idWorksOfEstimate = wef.idWorksOfEstimate
				LEFT OUTER JOIN ProjectCommand prc ON wef.idTeamMember=prc.id
				LEFT OUTER JOIN People ppl ON prc.idHuman = ppl.idHuman
				LEFT OUTER JOIN RoleModel rlm ON prc.idRole = rlm.idRole
				where woe.idWorksOfEstimate = ".$this->idWorksOfEstimate." and rlm.RoleName like 'Аналитик'
				order by fio limit 1";
	    
		$mantis_login_str = Yii::$app->db->createCommand($sql)->queryOne();		
		
		if($mantis_login_str){
			if(!empty($mantis_login_str['mantis_login'])){
				
				return $mantis_login_str['mantis_login'];	
			} else {
				return '';
					}	
			} else {
				return '';
					}	
	   
    }
    public function addWork($idEstimateWorkPackages,$idWbs,$workName,$workDescr,$MantisNumber=0)
    {
	   $this->idEstimateWorkPackages = $idEstimateWorkPackages;
	   $this->idWbs = $idWbs;
	   $this->WorkName = $workName;
	   $this->WorkDescription = $workDescr;
	   if($MantisNumber != 0){$this->mantisNumber = $MantisNumber;}
	   $this->save();
	   if($this->hasErrors()){
				return -1; 
	   }else{
				return 0;
	   }
	}
	/*
	 * 
	 * Возвращает сумарные трудозатраты по работе 
	 * возвращаемое значение DateInterval 
	 */ 
	 public function getWorkDuration($idWOE){
		 $sql = "select sum(workEffort) as Days ,sum(workEffortHour) as Hours from WorkEffort where idWorksOfEstimate = ".$idWOE;
		 $WorkDuration = Yii::$app->db->createCommand($sql)->queryOne();
		 //if($WorkDuration){
			 //$St = 'P'.round($WorkDuration['Days']+$WorkDuration['Hours']/8).'D';
		 //}else{
			 //$St = 'P0D';
			 //}
		 //return $WorkDurationInt = new \DateInterval($St);	 
		 return $WorkDurationInt = round($WorkDuration['Days']+$WorkDuration['Hours']/8);	 
	 }
	 /*
	  * прибавляет к дате n дней с учетом выходых из  таблицы
	  * 
	  */
	  public function addDay($date,$n){
		  $d = new \DateInterval('P1D');
		  $a = $n;
		  $dateA = $date;
		  while($a>0){
			  $dateA->add($d); 
			  if(!Weekends::isWeekend($dateA)){
				$a=$a-1;
			  }
		  }
		  return $dateA;
		  } 
	   /*
	  * отнимает от даты n дней с учетом выходых из  таблицы
	  * 
	  */
	  public function subDay($date,$n){
		  $d = new \DateInterval('P1D');
		  $a = $n;
		  $dateA = $date;
		  while($a>0){
			  $dateA->sub($d); 
			  if(!Weekends::isWeekend($dateA)){
				$a=$a-1;
			  }
		  }
		  return $dateA;
		  }  
	 /*
	  * возвращает дату начала работы с учетом типа связи и задержек/опережений
	  * тип связи конец-начало -> дата начала =  дата окончания работы предшественици +(-)задержка
	  * тип связи начало-начало -> дата начала =  дата начала работы предшественици +(-)задержка
	  * 
	  */
	 public function getWorkDateBegin($lag,$idLinkType,$dataBeg,$dataEnd){
		 if($idLinkType==1){  //конец-начало
			 $dataResult=WorksOfEstimate::addDay($dataEnd,1);
			}elseif($idLinkType==2){ //начала-начало
				 $dataResult = $dataBeg;
			 }else{
				 return false;
				 }
	//Добавляем задержки и опережения
				if($lag>0){ //задержка
						$dend1 = WorksOfEstimate::addDay($dataResult,round($lag));
					}elseif($lag<0){//опережение
					    $dend1 = WorksOfEstimate::subDay($dataResult,round($lag*-1));
					}else{
						$dend1 =$dataResult ;
						}
				 return $dend1;
	 }
	/*
	 * 
	 * $idWOEcur - id  работы
	 * возвращает наибольшу дату  окончания работы-предшественицы.  Определяется как максимальная из дат окончания работ-предшествеников с учетом запаздывания
	 */ 
	public function getPrevWorkMaxDateEnd($idWOEcur,$idBR){
		$BR = BusinessRequests::findOne($idBR);
		$sql1 = "select 
				  lnk.idFirstWork,
				  lnk.idSecondWork,
				  lnk.lag,
				  lnk.idLinkType,
				  scd.WorkBegin,
				  scd.WorkEnd,
				  scd.idSchedule,
				  scd.duration
				 from Links as lnk
				 LEFT OUTER JOIN WorksOfEstimate as woe ON  woe.idWorksOfEstimate = lnk.idFirstWork
				 LEFT OUTER JOIN Schedule as scd ON scd.idWorksOfEstimate = woe.idWorksOfEstimate	
				 where lnk.idSecondWork= ".$idWOEcur;
				 
		$PrevWorkList = Yii::$app->db->createCommand($sql1)->queryAll();	
		//получили список работ предшествеников
		$dates_begin = array();
		foreach($PrevWorkList as $pwl){
			if(is_null($pwl['WorkEnd'])){    // если у работы предшественицы не установлена дата начала
				//определем и сохраняем даты начала и окончания работы-предшественицы
				$sch = new Schedule();
				$begArr = WorksOfEstimate::getPrevWorkMaxDateEnd($pwl['idFirstWork'],$idBR);  //получаем максимальную дату окончания у  работ-предшественицу работы-предшественицы
				$dbeg=$begArr['data'];
				$sch->WorkBegin = $dbeg->format('Y-m-d');
				$sch->idWorkPrev = $begArr['idWorkPrev'];
				$sch->lag = $begArr['lag'];
				$sch->idLinkType = $begArr['idLinkType'];
				
				$duration = WorksOfEstimate::getWorkDuration($pwl['idFirstWork']);
				$dend = WorksOfEstimate::addDay($dbeg,$duration-1);
				$sch->duration = $duration;
				$sch->WorkEnd = $dend->format('Y-m-d');
				$sch->idWorksOfEstimate = $pwl['idFirstWork'];
			    $sch->DataSetting =date("Y-m-d H:i:s");
			    $sch->idBr = $idBR;
			    
			    $sch->save();
			    if($sch->hasErrors()){
						Yii::$app->session->addFlash('error',"Ошибка сохранения оценки работ ");
				}	
				//определяем дату начала работы исходя из дат текущей работы-предшественика, типа связи и задержки
				$dend1 = WorksOfEstimate::getWorkDateBegin($pwl['lag'],$pwl['idLinkType'],$begArr['data'],$dend);
							
				if($dend1){
						//заносим в массив дату начала работы и задержку
					    $dates_begin[] = ['data'=>$dend1,'lag'=>$pwl['lag'],'idWorkPrev'=>$pwl['idFirstWork']
										 ,'idLinkType'=>$pwl['idLinkType']];	
					}else{
						Yii::$app->session->addFlash('error',"Ошибка определения даты начала работы");
					}				
			} else{//дата окончания не NULL
				$dend = \DateTime::createFromFormat('Y-m-d', $pwl['WorkEnd']);
				$dbeg = \DateTime::createFromFormat('Y-m-d', $pwl['WorkBegin']);
				//определяем дату начала работы исходя из дат текущей работы-предшественика, типа связи и задержки
				$dend1 = WorksOfEstimate::getWorkDateBegin($pwl['lag'],$pwl['idLinkType'],$dbeg,$dend);
				if($dend1){
						//заносим в массив дату начала работы и задержку
					    $dates_begin[] = ['data'=>$dend1,'lag'=>$pwl['lag'],'idWorkPrev'=>$pwl['idFirstWork']
										 ,'idLinkType'=>$pwl['idLinkType']];	
					}else{
						Yii::$app->session->addFlash('error',"Ошибка определения даты начала работы");
					}	
			}
		}
		//для всех дат начала,  определнных на основании всех работ-предшествениц,  определяем максимальную
		if(empty($dates_begin)){  
			//возвращаем дату начала проекта 
			 //$d = WorksOfEstimate::subDay($BR->getBRDateBegin(),1); //потому что иначе дата окончания для первой работы будет больше на 1
			$d1 = ['data'=>$BR->getBRDateBegin(),'lag'=>0,'idWorkPrev'=>0,'idLinkType'=>0];
			//$d1 = $BR->getBRDateBegin();
			return $d1;
		  } else{
			    //ищем максимальную дату в массиве
			    $max_dt = $dates_begin[0]; 
			    foreach($dates_begin as $d){
					
					$interval = $d['data']->diff($max_dt['data']);
					if($interval->invert == 1){
						$max_dt = $d;
	 				    }
					}
					// возвращаем массив с максимальной датой и задержкой
					
				return $max_dt;
			}
		
	} 
	/*
	 * Возращает перечень id  работ предшествеников или  последователей 
	 * в зависимости от параметра
	 * 
	 * 
	 */ 
	public function getPNWOEList($typeList){
		$str='';
		if($typeList == 1){  //предшественицы
			$sql = 'SELECT idFirstWork FROM Links where idSecondWork = '.$this->idWorksOfEstimate;
			$WorkList = Yii::$app->db->createCommand($sql)->queryAll();	
			if(!empty($WorkList)){
				foreach($WorkList as $wl){
					$str = $str.$wl['idFirstWork'].',';
					}
				}	
			} elseif($typeList == 2 ){ //последующие работы
				$sql = 'SELECT idSecondWork FROM Links where idFirstWork = '.$this->idWorksOfEstimate;
				$WorkList = Yii::$app->db->createCommand($sql)->queryAll();	
					foreach($WorkList as $wl){
						$str = $str.$wl['idSecondWork'].',';
						}
					}
		return $str;			
	}
			
		 
	 
}
