<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ConstraintType".
 *
 * @property int $idConstrType
 * @property string $ConstrTypeName
 */
class ConstraintType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ConstraintType';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ConstrTypeName'], 'string', 'max' => 90],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idConstrType' => 'Id Constr Type',
            'ConstrTypeName' => 'Constr Type Name',
        ];
    }
}
