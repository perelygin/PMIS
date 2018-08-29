<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "LifeCycleType".
 *
 * @property int $idLifeCycleType
 * @property string $LifeCycleTypeName
 * @property string $LifeCycleTypeComent
 */
class LifeCycleType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'LifeCycleType';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['LifeCycleTypeName'], 'string', 'max' => 45],
            [['LifeCycleTypeComent'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idLifeCycleType' => 'Id Life Cycle Type',
            'LifeCycleTypeName' => 'Life Cycle Type Name',
            'LifeCycleTypeComent' => 'Life Cycle Type Coment',
        ];
    }
}
