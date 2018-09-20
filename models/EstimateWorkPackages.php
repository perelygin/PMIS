<?php

namespace app\models;

use Yii;

/**
 * Пакет оцениваемых работ. включает в себя перечень работ и их оценку на дату
 * This is the model class for table "EstimateWorkPackages".
 *
 * @property int $idEstimateWorkPackages
 * @property string $dataEstimate
 * @property string $EstimateName Наименование оценки
 * @property int $idBR
 *
 * @property BusinessRequests $bR
 * @property WorksOfEstimate[] $worksOfEstimates
 */
class EstimateWorkPackages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'EstimateWorkPackages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dataEstimate'], 'safe'],
            [['idBR','deleted'], 'integer'],
            [['EstimateName'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idEstimateWorkPackages' => 'Id Estimate Work Packages',
            'dataEstimate' => 'Дата оценки',
            'EstimateName' => 'Наименование оценки',
            'idBR' => 'Id Br',
        ];
    }
    /*
     * возвращает название BR, по которой делалась оценка
     */
    public function getBrInfo()
    {
		$BR = BusinessRequests::findOne($this->idBR);
		$BrInfo = array('BRName' => $BR->BRName,'BRNumber'=>$BR->BRNumber,'EstimateName'=>$this->EstimateName,'dataEstimate'=>$this->dataEstimate);
        return $BrInfo;
    }
}
