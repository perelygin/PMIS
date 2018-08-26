<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "LifeCycleStages".
 *
 * @property int $idStage
 * @property string $StageName
 * @property int $StageOrder
 * @property int $idlifeCycleType
 * @property int $LCS_parent_id id родителя(для второго уровня wbs)
 * @property string $LCS_comment
 * @property string $LifeCycleStagescol Комментарий для элемена WBS
 */
class LifeCycleStages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'LifeCycleStages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['StageOrder', 'idlifeCycleType', 'LCS_parent_id'], 'integer'],
            [['LCS_parent_id'], 'required'],
            [['StageName'], 'string', 'max' => 145],
            [['LCS_comment'], 'string', 'max' => 250],
            [['idlifeCycleType'], 'exist', 'skipOnError' => true, 'targetClass' => LifeCycleType::className(), 'targetAttribute' => ['idlifeCycleType' => 'idLifeCycleType']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idStage' => 'Id Stage',
            'StageName' => 'Stage Name',
            'StageOrder' => 'Stage Order',
            'idlifeCycleType' => 'Idlife Cycle Type',
            'LCS_parent_id' => 'Lcs Parent ID',
            'LCS_comment' => 'Lcs Comment',
            'LifeCycleStagescol' => 'Life Cycle Stagescol',
        ];
    }
    
    //формируем массив из шаблона wbs,  который организован как adjency list(parent_id)
    public function getLifeCycleStages($BRLifeCycleType){  
		$wbs_template = array();
		$LifeCycleStages_level_1 = $this::find()->asArray()->where(['idlifeCycleType' => $BRLifeCycleType,'LCS_parent_id'=>0])->orderBy('StageOrder')->all(); 
		foreach($LifeCycleStages_level_1 as $lcs){
			$LifeCycleStages_level_2 = $this::find()->asArray()->where(['idlifeCycleType' => $BRLifeCycleType,'LCS_parent_id'=>$lcs['idStage']])->orderBy('StageOrder')->all(); 
			$lcs_level2_full = array();
			foreach($LifeCycleStages_level_2 as $lcs_l2){
				$lcs_level2 =  array('StageOrder'=>$lcs_l2['StageOrder'],'StageName' => $lcs_l2['StageName']);	
				$lcs_level2_full[] =  $lcs_level2;
			}
			$wbs_template[] = array('StageOrder' => $lcs['StageOrder'], 'StageName' => $lcs['StageName'], 'lvl2'=>$lcs_level2_full);
			
		}
		//echo('<pre> '.print_r($wbs_template).'</pre>');  die;
		return $wbs_template;
	}
}
