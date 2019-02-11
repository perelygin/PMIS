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
	//записываем ответственную организацию по результату на основании ответственного по событию 	
	parent::afterSave($insert, $changedAttributes);
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
            [['ResultEventsDate'], 'safe'],
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
        ];
    }
}
