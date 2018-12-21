<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RoleModel".
 *
 * @property int $idRole
 * @property int $idRoleModelType
 * @property string $RoleName
 * @property string $RoleComment
 */
class RoleModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'RoleModel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idRoleModelType','idRole'], 'integer'],
            [['RoleName'], 'string', 'max' => 45],
            [['RoleComment'], 'string', 'max' => 300],
           
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idRole' => 'Id Role',
            'idRoleModelType' => 'Id Role Model Type',
            'RoleName' => 'Role Name',
            'RoleComment' => 'Role Comment',
        ];
    }
    /*
     * возвращает массив из перечня ролей для ролевой модели заданного типа
     */
     
     public function get_RoleModel($RoleModelType)
     {
		 $RoleModel = $this::find()->asArray()->where(['idRoleModelType' => $RoleModelType])->orderBy('idRole')->all(); 
		 return $RoleModel;
	 }
	  /*
     * возвращает массив из перечня тарифов ролевой модели заданного типа
     */
     
     public function get_RoleTarifModel($RoleModelType)
     {
		 $sql = "SELECT trf.idTariff,trf.TariffName
					FROM RoleModel as rlm 
					LEFT OUTER JOIN Tariff trf ON rlm.idTariff = trf.idTariff
					where rlm.idRoleModelType = ".$RoleModelType
					." group by trf.idTariff
					order by trf.idTariff";
		 $RoleTarifModel = Yii::$app->db->createCommand($sql)->queryAll(); 				// выбрали тарифы для ролевой модели					 
		
		 return $RoleTarifModel;
	 }
}
