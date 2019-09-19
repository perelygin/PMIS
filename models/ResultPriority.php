<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ResultPriority".
 *
 * @property int $idResultPriority
 * @property int $ResultPriorityOrder
 * @property string $ResultPriorityName
 */
class ResultPriority extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ResultPriority';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ResultPriorityOrder'], 'integer'],
            [['ResultPriorityName'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idResultPriority' => 'Id Result Priority',
            'ResultPriorityOrder' => 'Result Priority Order',
            'ResultPriorityName' => 'Result Priority Name',
        ];
    }
}
