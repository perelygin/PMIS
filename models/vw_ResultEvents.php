<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_ResultEvents".
 *
 * @property int $idResultEvents
 * @property string $ResultEventsDate
 * @property string $ResultEventsName
 * @property string $ResultEventsMantis
 * @property int $idwbs
 * @property int $deleted
 * @property string $team_member
 */
class vw_ResultEvents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_ResultEvents';
    }
	
	public static function primaryKey()
    {
        return ['idResultEvents'];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idResultEvents', 'idwbs', 'deleted'], 'integer'],
            [['ResultEventsDate'], 'safe'],
            [['ResultEventsName'], 'string', 'max' => 100],
            [['ResultEventsMantis'], 'string', 'max' => 45],
            [['team_member'], 'string', 'max' => 247],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idResultEvents' => 'Id Result Events',
            'ResultEventsDate' => 'Result Events Date',
            'ResultEventsName' => 'Result Events Name',
            'ResultEventsMantis' => 'Result Events Mantis',
            'idwbs' => 'Idwbs',
            'deleted' => 'Deleted',
            'team_member' => 'Team Member',
        ];
    }
}
