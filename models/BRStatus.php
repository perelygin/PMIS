<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "BRStatus".
 *
 * @property int $idBRStatus
 * @property string $BRStatusName
 */
class BRStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'BRStatus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idBRStatus'], 'required'],
            [['idBRStatus'], 'integer'],
            [['BRStatusName'], 'string', 'max' => 100],
            [['idBRStatus'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idBRStatus' => 'Cтатус BR',
            'BRStatusName' => 'Cтатус BR',
        ];
    }
}
