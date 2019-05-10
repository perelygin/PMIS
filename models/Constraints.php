<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Constraints".
 *
 * @property int $idConstraints
 * @property int $idWorksOfEstimate
 * @property int $idConstrType
 * @property string $DataConstr
 */
class Constraints extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Constraints';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idWorksOfEstimate', 'idConstrType'], 'integer'],
            [['DataConstr'], 'required'],
            [['DataConstr'], 'default', 'value' => null]
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idConstraints' => 'Id Constraints',
            'idWorksOfEstimate' => 'Id Works Of Estimate',
            'idConstrType' => 'Тип ограничения',
            'DataConstr' => 'Дата ограничения',
        ];
    }
    /*
	 * Возвращает перечень ограничений
	 * 
	 */    
	public function getListConstraintsList($idWorksOfEstimate){
		$sql = "SELECT 
				 cns.DataConstr,
				 cns.idConstraints,
				 cnst.ConstrTypeName
				FROM Constraints as cns
				LEFT OUTER JOIN ConstraintType cnst ON cnst.idConstrType = cns.idConstrType
				where cns.idWorksOfEstimate =  ".$idWorksOfEstimate;
		
		$WOElcl = Yii::$app->db->createCommand($sql)->query();		
		
		if($WOElcl){
			if(!empty($WOElcl)){
				
				return $WOElcl;	
			} else {
				return '';
					}	
			} else {
				return '';
					}					
	   
	   }
	   
	   
	/*
	 * 
	 * возвращает количество ограничений для работы
	 * 
	 */
	 public function getConstrCount($idWorksOfEstimate){
		 $sql = 'SELECT count(*) FROM Constraints where idWorksOfEstimate = '.$idWorksOfEstimate;
		 $ConstrCount = Yii::$app->db->createCommand($sql)->queryScalar();
		 return $ConstrCount;
	 }      
}
