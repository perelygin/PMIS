<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ResultEvents".
 *
 * @property int $idResultEvents
 * @property int $idwbs
 * @property string $ResultEventsDate
 * @property string $ResultEventsDescription
 * @property string $ResultEventsName
 * @property string $ResultEventsMantis
 * @property int $ResultEventResponsible
 * @property int $deleted
 * * @property string $ResultEventsPlannedResponseDate 
* @property string $ResultEventsFactResponseDate 
 */
class ResultEvents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ResultEvents';
    }
	public function afterSave($insert, $changedAttributes)
	{
	
	parent::afterSave($insert, $changedAttributes);
	   //записываем ответственную организацию по результату на основании ответственного по событию 	
		$sql = 'select 
		  rsl.ResultEventsDate,
		  rsl.ResultEventResponsible,
		  ppl.idOrganization,
		  ppl.idHuman
		from ResultEvents rsl
		 LEFT OUTER JOIN ProjectCommand prc ON prc.id = rsl.ResultEventResponsible
		 LEFT OUTER JOIN People ppl ON prc.idHuman = ppl.idHuman
		where idwbs = '.$this->idwbs. 
		' Order by ResultEventsDate desc
		LIMIT 1';
		$Respons = Yii::$app->db->createCommand($sql)->queryAll();
		if (!empty($Respons)){
			$wbs = Wbs::findOne(['id'=>$this->idwbs]);
			$wbs->idOrgResponsible = $Respons[0]['idOrganization'];
			$wbs->idPeopleResponsible = $Respons[0]['idHuman'];
			$wbs->save();
		}
		
		
	}
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idwbs', 'ResultEventResponsible', 'deleted'], 'integer'],
            [['ResultEventsDate', 'ResultEventsPlannedResponseDate', 'ResultEventsFactResponseDate'], 'safe'],
            [['ResultEventResponsible','ResultEventsDate','ResultEventsName'], 'required'],
            [['ResultEventsDescription'], 'string', 'max' => 65535 ],
            [['ResultEventsName'], 'string', 'max' => 100],
            [['ResultEventsMantis'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idResultEvents' => 'Id Result Events',
            'idwbs' => 'Idwbs',
            'ResultEventsDate' => 'Дата события',
            'ResultEventsDescription' => 'Описание',
            'ResultEventsName' => 'Событие',
            'ResultEventsMantis' => 'Result Events Mantis',
            'ResultEventResponsible' => 'Ответственный  по результату',
            'deleted' => 'Deleted',
            'ResultEventsPlannedResponseDate' => 'Плановая дата обработки события', 
            'ResultEventsFactResponseDate' => 'Фактическая дата обработки события', 
        ];
    }
    /*
     * Возвращает дату плановой реакции по последнему событию результата
     * 
     * 
     * 
     */ 
      public function getLastResultEventPlanData($idwbs){
		  $sql ="SELECT ResultEventsPlannedResponseDate FROM ResultEvents where idwbs =".$idwbs.
					" order by  ResultEventsDate DESC
					limit 1";
					$Results = Yii::$app->db->createCommand($sql)->queryScalar();
			if($Results){
				$dPlanEvent = \DateTime::createFromFormat('Y-m-d', $Results);
				return $dPlanEvent;
		 } else{
			 return false;
			 }		
		  }
}
