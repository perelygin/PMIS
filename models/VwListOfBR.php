<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_ListOfBR".
 *
 * @property int $idBR
 * @property int $BRDeleted
 * @property int $BRnumber
 * @property string $BRName Наименование BR
 * @property string $ProjectName
 * @property string $StageName
 * @property string $StagesStatusName
 * @property string $Family
 * @property string $CustomerName
 */
class VwListOfBR extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_ListOfBR';
    }
	public static function primaryKey()
    {
        return ['idBR'];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idBR', 'BRDeleted', 'BRnumber'], 'integer'],
            [['BRName'], 'string', 'max' => 150],
            [['ProjectName'], 'string', 'max' => 100],
            [['StageName', 'StagesStatusName', 'Family'], 'string', 'max' => 45],
            [['CustomerName'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idBR' => 'Id Br',
            'BRDeleted' => 'Brdeleted',
            'BRnumber' => 'Номер BR',
            'BRName' => 'Наименование BR',
            'ProjectName' => 'Проект',
            'StageName' => 'Этап',
            'StagesStatusName' => 'Текущее состояние',
            'Family' => 'Ответственный',
            'CustomerName' => 'Ответственная сторона',
        ];
    }
}
