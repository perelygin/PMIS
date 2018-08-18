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
}
