<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ServiceType".
 *
 * @property int $idServiceType
 * @property string $ServiceName
 * @property string $ServiceDescript
 */
class ServiceType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ServiceType';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ServiceName'], 'string', 'max' => 100],
            [['ServiceDescript'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idServiceType' => 'Id Service Type',
            'ServiceName' => 'Service Name',
            'ServiceDescript' => 'Service Descript',
        ];
    }
    	 /*
	  * возвращает массив с перечнем услуг. оакзываемых для ролей указанного типа 
	  * 
	  */
	  public function getServs($RoleModelType){
		  
		   $sql = 'select srt.idServiceType, srt.ServiceName from ServiceType as srt
						LEFT OUTER JOIN RoleModel as rlm ON  rlm.idRole = srt.idRole
						where rlm.idRoleModelType = '.$RoleModelType.' order by srt.idServiceType';
			$ServsForBr = Yii::$app->db->createCommand($sql)->queryAll(); 				// выбрали тарифы для ролевой модели					 
		
		 return $ServsForBr;
	  }
}
