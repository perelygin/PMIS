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
}
