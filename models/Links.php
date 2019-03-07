<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Links".
 *
 * @property int $idLink
 * @property int $idFirstWork
 * @property int $idSecondWork
 * @property int $idLinkType 1- конец-окончание 2- начало-начало
 */
class Links extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Links';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idFirstWork', 'idSecondWork', 'idLinkType'], 'integer'],
            [['idFirstWork'], 'exist', 'skipOnError' => true, 'targetClass' => WorksOfEstimate::className(), 'targetAttribute' => ['idFirstWork' => 'idWorksOfEstimate']],
            [['idSecondWork'], 'exist', 'skipOnError' => true, 'targetClass' => WorksOfEstimate::className(), 'targetAttribute' => ['idSecondWork' => 'idWorksOfEstimate']],
            [['idLinkType'], 'exist', 'skipOnError' => true, 'targetClass' => LinkType::className(), 'targetAttribute' => ['idLinkType' => 'idLinkType']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idLink' => 'Id Link',
            'idFirstWork' => 'Id First Work',
            'idSecondWork' => 'Id Second Work',
            'idLinkType' => 'Id Link Type',
        ];
    }
}
