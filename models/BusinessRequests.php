<?php

namespace app\models;

use Yii;
use app\models\Wbs;


/**
 * This is the model class for table "BusinessRequests".
 *
 * @property int $idBR
 * @property string $BRName Наименование BR
 * @property int $idProject
 * @property int $BRLifeCycleType Тип ЖЦ
 * @property int $BRCurrentStage Текущий этап работ
 * @property int $BRCurrentStageStatus Текущее состояние работ
 * @property int $BRCurrentResponsible ответственный за текущее состояние
 * @property int $BRDeleted
 * @property int $BRNumber
 * @property int $BRRoleModelType тип ролевой модели
 */
class BusinessRequests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'BusinessRequests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idProject', 'BRNumber','BRRoleModelType'], 'integer'],
            [['idProject'], 'required','message' => 'Пожалуйста, укажите проект'],
            [['BRName'], 'required','message' => 'Пожалуйста, введите имя'],
            [['BRRoleModelType'], 'required','message' => 'Пожалуйста, укажите тип ролевой модели'],
            [['BRLifeCycleType'], 'required','message' => 'Пожалуйста, укажите шаблон WBS'],
            [['BRName'], 'string', 'max' => 150],
            [['BRDescription','BRDateBegin', 'BRStatus'], 'safe'],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idBR' => 'Id Br',
            'BRName' => 'Название BR',
            'idProject' => 'Проект',
            'BRLifeCycleType' => 'Шаблон WBS',
            'BRDeleted' => 'Brdeleted',
            'BRNumber' => 'Номер BR',
            'BRRoleModelType' =>'Тип ролевой модели',
            'BRDescription' =>'Описание',
            'BRDateBegin'=>'Дата начала работ',
            'BRStatus'=>'Cтатус BR'
        ];
    }
    public function findModelWbs($idBr)
    {
        if (($model = Wbs::findOne(['idBr'=>$idBr,'depth'=>'0'])) !== null) {
            return $model;
        }
		throw new \yii\web\NotFoundHttpException('Запись не найдена');
       // throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /*
	  * Возвращает тип ролевой модели
	  * 
	  */ 
    public function get_BRRoleModelType(){
		return $this->BRRoleModelType;
	}
	    /*
     * возвращает id последней  оценки по экспертизе
     */
    public function getLastEstimateId()
    {
	    $sql = "SELECT * FROM EstimateWorkPackages 
				where idBR = "
				.$this->idBR
				." and deleted = 0 order by dataEstimate desc limit 1";
		$EstPckg = Yii::$app->db->createCommand($sql)->queryOne();		
		if($EstPckg){
			
			return $EstPckg['idEstimateWorkPackages'];	
		} else {
			return null;
				}
			
        
    }
        /*
     * возвращает сумму последней  оценки по экспертизе + 10%
     */
    public function getLastEstimateSumm()
    {
	    $idEWP=-1;
	    $sql = "SELECT idEstimateWorkPackages FROM EstimateWorkPackages 
				where idBR = "
				.$this->idBR
				." and deleted = 0 order by dataEstimate desc limit 1";
		$EstLastPckg = Yii::$app->db->createCommand($sql)->queryOne();		
		if($EstLastPckg){
			
			$idEWP = $EstLastPckg['idEstimateWorkPackages'];	
			$sql1 = "SELECT 
						sum(workEffort)
						FROM WorkEffort as we
						LEFT OUTER JOIN WorksOfEstimate woe ON we.idWorksOfEstimate = woe.idWorksOfEstimate
						where woe.idEstimateWorkPackages = ".$idEWP;
			$SumWE = Yii::$app->db->createCommand($sql1)->queryScalar();
			$SumWE = $SumWE + ($SumWE*0.1); //10% на тестирование по  методикам банка, которые в оценке расчитываются динамически.
			return $SumWE;    // false, при отсутствии результата

		} else {
			return false;
				}
			
        
    }
    public function get_pm_login()
    {
	    $sql = "SELECT 
				 br.idBR,
				 concat(ppl.Family,' ',ppl.Name) as fio,
				 rlm.RoleName,
				 ppl.mantis_login
				FROM BusinessRequests as br
				LEFT OUTER JOIN ProjectCommand prc ON br.idBR = prc.idBR
				LEFT OUTER JOIN RoleModel rlm ON prc.idRole = rlm.idRole
				LEFT OUTER JOIN People ppl ON prc.idHuman = ppl.idHuman
				Where br.idBR = ".$this->idBR." and rlm.RoleName like 'Менеджер проекта' and ppl.idHuman <> -1
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
    /*
     * Возвращает перечень работ с номерами инцидентов мантис для текущей BR с указанным типом результата
     * $ResType - 1 экспертиза
     * $ResType - 2 БФТЗ
     * $ResType - 3 Все инциденты по результату idwbs
     * $ResType - 6 тестирование в версии
     */
    public function getMantisNumbers($ResType = 1, $idwbs = 0)
    {
	    $sql1 = "SELECT idEstimateWorkPackages FROM EstimateWorkPackages 
				where idBR = "
				.$this->idBR
				." and deleted = 0 order by dataEstimate desc limit 1";
		$EstLastPckg = Yii::$app->db->createCommand($sql1)->queryOne();		
		if($EstLastPckg){
			$idLastEWP = $EstLastPckg['idEstimateWorkPackages'];	
			if($ResType == 1 or $ResType == 2){
				$sql ="SELECT 
				 wbs.id,
				 wbs.name,
				 wef.idWorksOfEstimate,
				 wef.WorkName,
				 wef.mantisNumber
				FROM wbs
				LEFT OUTER JOIN WorksOfEstimate wef ON wbs.id = wef.idWbs
				where wbs.idBr =" .$this->idBR. " and wbs.idResultType = ".$ResType." and wef.idEstimateWorkPackages = ".$idLastEWP ;
				}
			if($ResType == 6){
				$sql ="SELECT 
				 wbs.id,
				 wbs.name,
				 wef.idWorksOfEstimate,
				 wef.WorkName,
				 wef.mantisNumber
				FROM wbs
				LEFT OUTER JOIN WorksOfEstimate wef ON wbs.id = wef.idWbs
				where wbs.idBr =" .$this->idBR. " and (wbs.idResultType = 1 or wbs.idResultType = 2) and wef.idEstimateWorkPackages = ".$idLastEWP ;
				}	
			if($ResType == 3 ){
				$sql ="SELECT 
				 wbs.id,
				 wbs.name,
				 wef.idWorksOfEstimate,
				 wef.WorkName,
				 wef.mantisNumber
				FROM wbs
				LEFT OUTER JOIN WorksOfEstimate wef ON wbs.id = wef.idWbs
				where wbs.idBr =" .$this->idBR. " and wbs.id = ".$idwbs." and wef.idEstimateWorkPackages = ".$idLastEWP ;
			}	
			
			 
				
		$mantis_numbers = Yii::$app->db->createCommand($sql)->queryAll();	
		
		return $mantis_numbers;
		} else{
			return false;
			}		
		
      
		}
	/*
	 * возвращает перечень работ,  по указанной оценке трудозатрат
	 * 
	 */ 	
	 public function getWOEList($idEWP)
		{
			$sql ='select idWorksOfEstimate from WorksOfEstimate as woe
						where woe.idEstimateWorkPackages = '.$idEWP
						.' order by idWorksOfEstimate'; 
			$WOEList = Yii::$app->db->createCommand($sql)->queryAll();	
		
		return $WOEList;			
		}	
	/*
	 *Проверяет,  установлены ли даты начала и окончания у работы 
	 * 
	 * 
	 */
	public function isDatasSet($idWOE)
	{
		$sql = "select 
				  sch.WorkBegin,
				  sch.WorkEnd,
				  sch.idSchedule,
                  sch.idWorksOfEstimate
				 from Schedule as sch
				 where  sch.idWorksOfEstimate = ".$idWOE;
		 $Datas = Yii::$app->db->createCommand($sql)->queryOne();
		 if($Datas){
			 if(!is_null($Datas['WorkBegin']) and !is_null($Datas['WorkEnd'])){
				 return true;
				 } else{
					 return false;
					 }
				 
			 }else{
				 return false;
				 }
	} 
	  	
	/*
	 * Возвращает перечень результатов для текущей BR
	 * в выборку попадают только те результаты, у которых нет подчинных результатов
	 */
	public function getResults()
    {
		$sql =	"SELECT id,name FROM wbs Where (wbs.rgt - wbs.lft <= 1) and idBr=".$this->idBR;
		$Results = Yii::$app->db->createCommand($sql)->queryAll();		
		return $Results;
	}
	
	/*Возвращает массив с mantis-логинами членов команды
	 * 
	 * 
	 */
	public function getCMembersLogins()
    {
		$sql =	"SELECT 
				  ppl.mantis_login
				FROM ProjectCommand as pcm
				 LEFT OUTER JOIN People ppl ON pcm.idHuman = ppl.idHuman
				where parent_id != 0 and pcm.idBR = ".$this->idBR;
		$Results = Yii::$app->db->createCommand($sql)->queryAll();		
		return $Results;
	}
	/*Возвращает id члена команды по mantis-логинаму
	 * 
	 * 
	 */
	public function getIdTeamMemberBylogin($mantis_login)
    {
		$sql =	"SELECT 
					pcm.id
					FROM ProjectCommand as pcm
					LEFT OUTER JOIN People ppl ON pcm.idHuman = ppl.idHuman
					where ppl.mantis_login = '". $mantis_login.   "' and pcm.idBR = ".$this->idBR;
		$Results = Yii::$app->db->createCommand($sql)->queryOne();		
		if($Results){
			return $Results['id'];
			} else{
				return -1;
				}
		
	}
	    /*
     * Возвращает ParentId для роли в текущей BR
     */
    public function getParentId($idRole){
		 $sql = 'SELECT * FROM ProjectCommand WHERE idBR = :idBR and parent_id = 0 and idRole = :idRole';
		 $pc = ProjectCommand::findBySql($sql, [
		':idBR' =>$this->idBR,
		':idRole'=>$idRole
		])->one();
		 
		 if(isset($pc)){
			 return $pc->id;
			 } else{
				 return null;
				 }
	} 
	public function getBrId(){
		return $this->idBR;
	} 
	//public function getBRDateBegin(){
		//return $this->BRDateBegin;
	//}
	
	
	public function getBRDateBegin(){
		$DateBegin = \DateTime::createFromFormat('Y-m-d', $this->BRDateBegin);
		return 	$DateBegin;
	} 
	/*
	 *  возвращает данные по рассписанию для BR выбранного пакета оценок
	 * 
	 */
	public function getBRScheduleData($idEWP){
	$sql = 	'select 
				 sch.WorkBegin,
				 sch.WorkEnd,
				 sch.duration,
				 sch.lag,
				 sch.idWorksOfEstimate,
	             woe.WorkName,
				 woe.idWorksOfEstimate,
				 wbs.name,
				 wbs.id,
				 wbs.idBr,
				 woe.idEstimateWorkPackages,
				 wbssh.WbsBegin,
				 wbssh.WBSEnd
			 from  wbs
	            LEFT OUTER JOIN WorksOfEstimate woe ON wbs.id = woe.idWbs
	            LEFT OUTER JOIN Schedule sch ON sch.idWorksOfEstimate = woe.idWorksOfEstimate
	            LEFT OUTER JOIN WbsSchedule wbssh ON wbs.id = wbssh.idWbs 
              where (wbs.idBr = '.$this->idBR.' and (wbs.rgt - wbs.lft <= 1)) 
				and (woe.idEstimateWorkPackages = '.$idEWP.' or isnull(woe.idEstimateWorkPackages))
				and (wbssh.idEstimateWorkPackages = '.$idEWP.')
			 	order by woe.idWbs, sch.WorkBegin';
		
	//$sql1="select 
			 //sch.WorkBegin,
			 //sch.WorkEnd,
			 //sch.duration,
			 //sch.lag,
			 //woe.WorkName,
			 //woe.idWorksOfEstimate,
			 //wbs.name,
			 //wbs.id,
			 //sch.idWorksOfEstimate,
			 //wbs.idBr,
			 //woe.idEstimateWorkPackages
			 //from Schedule  as sch
			//LEFT OUTER JOIN WorksOfEstimate woe ON sch.idWorksOfEstimate = woe.idWorksOfEstimate
			//LEFT OUTER JOIN wbs ON woe.idWbs = wbs.id
			//where woe.idEstimateWorkPackages = ".$idEWP.
			//" order by woe.idWbs";
		$Results = Yii::$app->db->createCommand($sql)->queryAll();		
		return $Results;	
	}		
	
	
	
}
