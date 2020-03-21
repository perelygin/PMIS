<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_report3".
 *
 * @property int $BRNumber
 * @property string $BRName Наименование BR
 * @property int $id
 * @property int $idBr
 * @property int $idOrgResponsible
 * @property string $name
 * @property int $idResultStatus
 * @property string $ResultStatusName
 * @property string $fio
 * @property int $idHuman
 * @property string $CustomerName
 * @property string $ResultEventsDate
 * @property string $ResultEventsName
 * @property int $ResultPriorityOrder
 */
class VwReport3 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_report3';
    }
	
	public static function primaryKey()
    {
        return ['id'];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['BRNumber', 'id', 'idBr', 'idOrgResponsible', 'idResultStatus', 'idHuman', 'ResultPriorityOrder'], 'integer'],
            [['idBr', 'name'], 'required'],
            [['ResultEventsDate','begBate'], 'safe'],
            [['BRName'], 'string', 'max' => 150],
            [['name'], 'string', 'max' => 255],
            [['ResultStatusName'], 'string', 'max' => 45],
            [['fio'], 'string', 'max' => 302],
            [['CustomerName'], 'string', 'max' => 120],
            [['ResultEventsName'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'BRNumber' => 'Brnumber',
            'BRName' => 'Brname',
            'id' => 'ID',
            'idBr' => 'Id Br',
            'idOrgResponsible' => 'Id Org Responsible',
            'name' => 'Name',
            'idResultStatus' => 'Id Result Status',
            'ResultStatusName' => 'Result Status Name',
            'fio' => 'Fio',
            'idHuman' => 'Id Human',
            'CustomerName' => 'Customer Name',
            'ResultEventsDate' => 'Result Events Date',
            'ResultEventsName' => 'Result Events Name',
            'ResultPriorityOrder' => 'Result Priority Order',
        ];
    }
}
