<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WbsSchedule".
 *
 * @property int $idWbsSchedule
 * @property int $idEstimateWorkPackages
 * @property int $idWbs
 * @property string $WbsBegin
 * @property string $WBSEnd
 */
class WbsSchedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'WbsSchedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idEstimateWorkPackages', 'idWbs'], 'integer'],
            [['WbsBegin', 'WBSEnd'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idWbsSchedule' => 'Id Wbs Schedule',
            'idEstimateWorkPackages' => 'Id Estimate Work Packages',
            'idWbs' => 'Id Wbs',
            'WbsBegin' => 'Дата начала',
            'WBSEnd' => 'Дата окончания',
        ];
    }
}
