<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Constraints".
 *
 * @property int $idConstraints
 * @property int $idWorksOfEstimate
 * @property int $idConstrType
 * @property string $DataConstr
 */
class Constraints extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Constraints';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idWorksOfEstimate', 'idConstrType'], 'integer'],
            [['DataConstr'], 'safe'],
            [['idConstrType'], 'exist', 'skipOnError' => true, 'targetClass' => ConstraintType::className(), 'targetAttribute' => ['idConstrType' => 'idConstrType']],
            [['idWorksOfEstimate'], 'exist', 'skipOnError' => true, 'targetClass' => WorksOfEstimate::className(), 'targetAttribute' => ['idWorksOfEstimate' => 'idWorksOfEstimate']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idConstraints' => 'Id Constraints',
            'idWorksOfEstimate' => 'Id Works Of Estimate',
            'idConstrType' => 'Id Constr Type',
            'DataConstr' => 'Data Constr',
        ];
    }
}
