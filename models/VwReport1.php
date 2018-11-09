<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_report1".
 *
 * @property int $BRNumber
 * @property string $BRName Наименование BR
 * @property int $id
 * @property int $idBr
 * @property int $idOrgResponsible
 * @property string $name
 * @property int $idResultStatus
 * @property string $mantis
 * @property string $ResultStatusName
 * @property string $fio
 * @property string $CustomerName
 */
class VwReport1 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_report1';
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
            [['BRNumber', 'id', 'idBr', 'idOrgResponsible', 'idResultStatus'], 'integer'],
            [['idBr', 'name'], 'required'],
            [['BRName', 'mantis'], 'string', 'max' => 150],
            [['name'], 'string', 'max' => 255],
            [['ResultStatusName'], 'string', 'max' => 45],
            [['fio'], 'string', 'max' => 302],
            [['CustomerName'], 'string', 'max' => 120],
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
            'mantis' => 'Mantis',
            'ResultStatusName' => 'Result Status Name',
            'fio' => 'Fio',
            'CustomerName' => 'Customer Name',
        ];
    }
}
