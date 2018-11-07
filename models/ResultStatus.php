<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ResultStatus".
 *
 * @property int $idResultStatus
 * @property string $ResultStatusName
 */
class ResultStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ResultStatus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idResultStatus'], 'required'],
            [['idResultStatus'], 'integer'],
            [['ResultStatusName'], 'string', 'max' => 45],
            [['idResultStatus'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idResultStatus' => 'Id Result Status',
            'ResultStatusName' => 'Статус по результату',
        ];
    }
}
