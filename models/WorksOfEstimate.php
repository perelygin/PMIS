<?php

namespace app\models;

use Yii;
use app\models\WorkEffort;

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
            [['WorkName'], 'string', 'max' => 250],
            [['WorkDescription'], 'string', 'max' => 1000],
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
            'WorkDescription' => 'Work Description',
        ];
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
            return true;
        }
        return false;
    }
   
}
