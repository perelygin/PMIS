<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "BusinessRequests".
 *
 * @property int $idBR
 * @property string $BRName Наименование BR
 * @property int $idProject
 * @property int $BRLifeCycleType
 * @property int $BRCurrentStage
 * @property int $BRCurrentStageStatus
 */
class BusinessRequests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'BusinessRequests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idProject', 'BRLifeCycleType', 'BRCurrentStage', 'BRCurrentStageStatus'], 'integer'],
            [['BRName'], 'string', 'max' => 150],
            [['idProject'], 'exist', 'skipOnError' => true],
            [['BRLifeCycleType'], 'exist', 'skipOnError' => true],
            [['BRCurrentStage'], 'exist', 'skipOnError' => true],
            [['BRCurrentStageStatus'], 'exist', 'skipOnError' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idBR' => 'Id Br',
            'BRName' => 'Brname',
            'idProject' => 'Id Project',
            'BRLifeCycleType' => 'Brlife Cycle Type',
            'BRCurrentStage' => 'Brcurrent Stage',
            'BRCurrentStageStatus' => 'Brcurrent Stage Status',
        ];
    }
}
