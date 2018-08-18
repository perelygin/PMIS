<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Projects".
 *
 * @property int $idProject
 * @property string $ProjectName
 * @property string $DataBegin
 * @property string $DataEnd
 * @property int $idOrganization
 */
class Projects extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Projects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['DataBegin', 'DataEnd'], 'safe'],
            [['idOrganization'], 'integer'],
            [['ProjectName'], 'string', 'max' => 100],
            [['idOrganization'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['idOrganization' => 'idOrganization']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idProject' => 'Id Project',
            'ProjectName' => 'Project Name',
            'DataBegin' => 'Data Begin',
            'DataEnd' => 'Data End',
            'idOrganization' => 'Id Organization',
        ];
    }
}
