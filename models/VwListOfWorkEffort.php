<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_ListOfWorkEffort".
 *
 * @property int $idWorksOfEstimate
 * @property string $WorkName
 * @property int $idEstimateWorkPackages
 * @property int $idWbs
 * @property string $workEffort
 * @property int $idTeamMember id члена команды
 * @property int $idRole
 * @property string $RoleName
 * @property int $idHuman
 * @property string $fio
 */
class VwListOfWorkEffort extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_ListOfWorkEffort';
    }

	public static function primaryKey()
    {
        return ['idWorksOfEstimate'];
    }
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idWorksOfEstimate', 'idEstimateWorkPackages', 'idWbs', 'idTeamMember', 'idRole', 'idHuman','idLaborExpenditures'], 'integer'],
            [['WorkName', 'idEstimateWorkPackages'], 'required'],
            [['workEffort'], 'number'],
            [['WorkName'], 'string', 'max' => 250],
            [['RoleName'], 'string', 'max' => 45],
            [['fio'], 'string', 'max' => 302],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idWorksOfEstimate' => 'Id Works Of Estimate',
            'WorkName' => 'Work Name',
            'idEstimateWorkPackages' => 'Id Estimate Work Packages',
            'idWbs' => 'Id Wbs',
            'workEffort' => 'ч/д',
            'idTeamMember' => '',
            'idRole' => 'Id Role',
            'RoleName' => 'Role Name',
            'idHuman' => 'Id Human',
            'fio' => 'Fio',
            'idLaborExpenditures' => 'idLaborExpenditures',
        ];
    }
    
    
    public static function getData($idEstimateWorkPackages, $id_node)
    {
		//$VwListOfWorkEffort = VwListOfWorkEffort::find()->where(['idEstimateWorkPackages'=>$idEstimateWorkPackages, 'idWbs'=>$id_node])->orderBy('idWorksOfEstimate')->all();
		
		
    }
}
