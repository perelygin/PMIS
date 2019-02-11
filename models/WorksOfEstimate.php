<?php

namespace app\models;

use Yii;
use app\models\WorkEffort;
use app\models\Systemlog;

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
            'mantisNumber' => 'Номер инцидента в мантиссе'
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
}
