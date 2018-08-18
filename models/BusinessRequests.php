<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "BusinessRequests".
 *
 * @property int $idBR
 * @property string $BRName Наименование BR
 * @property int $idProject
 * @property int $BRLifeCycleType Тип ЖЦ
 * @property int $BRCurrentStage Текущий этап работ
 * @property int $BRCurrentStageStatus Текущее состояние работ
 * @property int $BRCurrentResponsible ответственный за текущее состояние
 * @property int $BRDeleted
 * @property int $BRNumber
 * @property int $BRRoleModelType тип ролевой модели
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
            [['idProject', 'BRNumber','BRRoleModelType'], 'integer'],
            [['idProject'], 'required','message' => 'Пожалуйста, укажите проект'],
            [['BRName'], 'required','message' => 'Пожалуйста, введите имя'],
            [['BRRoleModelType'], 'required','message' => 'Пожалуйста, укажите тип ролевой модели'],
            [['BRName'], 'string', 'max' => 150],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idBR' => 'Id Br',
            'BRName' => 'Название BR',
            'idProject' => 'Проект',
            'BRLifeCycleType' => 'Brlife Cycle Type',
            'BRCurrentStage' => 'Brcurrent Stage',
            'BRCurrentStageStatus' => 'Brcurrent Stage Status',
            'BRCurrentResponsible' => 'Brcurrent Responsible',
            'BRDeleted' => 'Brdeleted',
            'BRNumber' => 'Номер BR',
            'BRRoleModelType' =>'Тип ролевой модели',
        ];
    }
}