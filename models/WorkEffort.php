<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WorkEffort".
 *
 * @property int $idLaborExpenditures
 * @property int $idWorksOfEstimate
 * @property int $idTeamMember id члена команды
 * @property string $workEffort
 */
class WorkEffort extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'WorkEffort';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idWorksOfEstimate', 'idTeamMember'], 'integer'],
            [['workEffort'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idLaborExpenditures' => 'Id Labor Expenditures',
            'idWorksOfEstimate' => 'Id Works Of Estimate',
            'idTeamMember' => 'Id Team Member',
            'workEffort' => 'Work Effort',
        ];
    }
}
