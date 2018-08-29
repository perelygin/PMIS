<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RoleModelType".
 *
 * @property int $idRoleModelType
 * @property string $RoleModelTypeName
 * @property string $RoleModelTypeComment
 */
class RoleModelType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'RoleModelType';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['RoleModelTypeName'], 'string', 'max' => 45],
            [['RoleModelTypeComment'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idRoleModelType' => 'Id Role Model Type',
            'RoleModelTypeName' => 'Role Model Type Name',
            'RoleModelTypeComment' => 'Role Model Type Comment',
        ];
    }
}
