<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ResultType".
 *
 * @property int $idResultType
 * @property string $ResultTypeName
 */
class ResultType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ResultType';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ResultTypeName'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idResultType' => 'Id Result Type',
            'ResultTypeName' => 'Result Type Name',
        ];
    }
}
