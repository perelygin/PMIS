<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ResultEvents".
 *
 * @property int $idResultEvents
 * @property int $idwbs
 * @property string $ResultEventsDate
 * @property string $ResultEventsDescription
 * @property string $ResultEventsName
 * @property string $ResultEventsMantis
 * @property int $ResultEventResponsible
 * @property int $deleted
 */
class ResultEvents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ResultEvents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idwbs', 'ResultEventResponsible', 'deleted'], 'integer'],
            [['ResultEventsDate'], 'safe'],
            [['ResultEventsDescription'], 'string', 'max' => 1000],
            [['ResultEventsName'], 'string', 'max' => 100],
            [['ResultEventsMantis'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idResultEvents' => 'Id Result Events',
            'idwbs' => 'Idwbs',
            'ResultEventsDate' => 'Дата события',
            'ResultEventsDescription' => 'Описание',
            'ResultEventsName' => 'Событие',
            'ResultEventsMantis' => 'Result Events Mantis',
            'ResultEventResponsible' => 'Ответственный  по результату',
            'deleted' => 'Deleted',
        ];
    }
}
