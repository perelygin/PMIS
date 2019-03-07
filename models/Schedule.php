<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Schedule".
 *
 * @property int $idSchedule
 * @property string $WorkBegin
 * @property string $WorkEnd
 * @property int $idWorksOfEstimate
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['WorkBegin', 'WorkEnd'], 'safe'],
            [['idWorksOfEstimate'], 'integer'],
            [['idWorksOfEstimate'], 'exist', 'skipOnError' => true, 'targetClass' => WorksOfEstimate::className(), 'targetAttribute' => ['idWorksOfEstimate' => 'idWorksOfEstimate']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idSchedule' => 'Id Schedule',
            'WorkBegin' => 'Work Begin',
            'WorkEnd' => 'Work End',
            'idWorksOfEstimate' => 'Id Works Of Estimate',
        ];
    }
}
