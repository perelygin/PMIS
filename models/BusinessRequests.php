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
     */
    public function getMantisNumbers($ResType = 1)
    {
	    $sql1 = "SELECT idEstimateWorkPackages FROM EstimateWorkPackages 
				where idBR = "
				.$this->idBR
				." and deleted = 0 order by dataEstimate desc limit 1";
		$EstLastPckg = Yii::$app->db->createCommand($sql1)->queryOne();		
		if($EstLastPckg){
			$idLastEWP = $EstLastPckg['idEstimateWorkPackages'];	
			 $sql ="SELECT 
				 wbs.id,
				 wbs.name,
				 wef.idWorksOfEstimate,
				 wef.WorkName,
				 wef.mantisNumber
				FROM wbs
				LEFT OUTER JOIN WorksOfEstimate wef ON wbs.id = wef.idWbs
				where wbs.idBr =" .$this->idBR. " and wbs.idResultType = ".$ResType." and wef.idEstimateWorkPackages = ".$idLastEWP ;
				
		$mantis_numbers = Yii::$app->db->createCommand($sql)->queryAll();	
		
		return $mantis_numbers;
		} else{
			return false;
			}		
		
      
		}
}
