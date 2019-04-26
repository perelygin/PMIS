<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_report2".
 *
 * @property int $idBR
 * @property int $BRNumber
 * @property string $BRName Наименование BR
 * @property int $id
 * @property string $name
 * @property string $fio
 * @property string $CustomerName
 * @property int $version_number
 */
class VwReport2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_report2';
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
            [['idBR', 'BRNumber', 'id', 'version_number'], 'integer'],
            [['name'], 'required'],
            [['BRName'], 'string', 'max' => 150],
            [['name'], 'string', 'max' => 255],
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
            'idBR' => 'Id Br',
            'BRNumber' => 'Brnumber',
            'BRName' => 'Brname',
            'id' => 'ID',
            'name' => 'Name',
            'fio' => 'Fio',
            'CustomerName' => 'Customer Name',
            'version_number' => 'Version Number',
        ];
    }
}
