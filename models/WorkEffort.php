<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WorkEffort".
 *
 * @property int $idLaborExpenditures
 * @property int $idWorksOfEstimate
 * @property int $idTeamMember id члена команды
 * @property string $workEffort
 */
class WorkEffort extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'WorkEffort';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idWorksOfEstimate', 'idTeamMember','idServiceType','workEffortHour'], 'integer'],
            [['idTeamMember', 'idServiceType'], 'required'],
            [['workEffort'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idLaborExpenditures' => 'Id Labor Expenditures',
            'idWorksOfEstimate' => 'Id Works Of Estimate',
            'idTeamMember' => 'Участник проекта',
            'workEffort' => 'ч.д.',
            'workEffortHour'=>'+ ч.час',
            'idServiceType' => 'Тип услуги'
        ];
    }
    //public function afterSave($insert, $changedAttributes){
	    //parent::afterSave($insert, $changedAttributes);
	    //$sql='SELECT Sum(workEffort) as summ FROM WorkEffort  where idWorksOfEstimate = '.$this->idWorksOfEstimate;
	    //$sumWef = Yii::$app->db->createCommand($sql)->queryScalar();
	    //$SysLog = new Systemlog();
	    //$SysLog->IdTypeObject = 4; 
	    //$SysLog->IdUser = Yii::$app->user->getId();;
	    //$SysLog->DataChange = date("Y-m-d H:i:s");
	    //$SysLog->idObject = $this->idWorksOfEstimate;
	    //$SysLog->SystemLogString = Yii::$app->user->identity->username.' => Трудозатраты по работе '.$this->idWorksOfEstimate.' изменены: '.$sumWef;
		//$SysLog->save();
	//}
}
