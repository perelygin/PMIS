<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Links".
 *
 * @property int $idLink
 * @property int $idFirstWork
 * @property int $idSecondWork
 * @property int $idLinkType 1- конец-окончание 2- начало-начало
 */
class Links extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Links';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idFirstWork', 'idSecondWork', 'idLinkType', 'lag'], 'integer'],
            [['idFirstWork'], 'exist', 'skipOnError' => true, 'targetClass' => WorksOfEstimate::className(), 'targetAttribute' => ['idFirstWork' => 'idWorksOfEstimate']],
            [['idSecondWork'], 'exist', 'skipOnError' => true, 'targetClass' => WorksOfEstimate::className(), 'targetAttribute' => ['idSecondWork' => 'idWorksOfEstimate']],
            [['idLinkType'], 'exist', 'skipOnError' => true, 'targetClass' => LinkType::className(), 'targetAttribute' => ['idLinkType' => 'idLinkType']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idLink' => 'Id Link',
            'idFirstWork' => 'Id First Work',
            'idSecondWork' => 'Id Second Work',
            'idLinkType' => 'Тип связи',
            'lag' => 'Запаздывание',
        ];
    }
    
    
    	/*
	 * Возвращает перечень предшествующих работ
	 * 
	 */    
	public function getPrevWorks($idWorksOfEstimate){
		$sql = "SELECT 
						wbs.name,
						wbs.idBr,
						woe.idWorksOfEstimate,
						woe.WorkName,
						woe.mantisNumber,
						lnk.idLink,
                        lnk.lag
						FROM wbs  
						LEFT OUTER JOIN  WorksOfEstimate woe ON woe.idWbs=wbs.id
                        LEFT OUTER JOIN  Links lnk ON lnk.idFirstWork = woe.idWorksOfEstimate
						where lnk.idSecondWork = ".$idWorksOfEstimate;
		
		$WOEInfo = Yii::$app->db->createCommand($sql)->query();		
		
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
}
