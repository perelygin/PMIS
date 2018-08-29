<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_ListOfPeople".
 *
 * @property string $fio
 * @property int $idHuman
 * @property string $CustomerName
 * @property int $idOrganization
 */
class VwListOfPeople extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_ListOfPeople';
    }
	public static function primaryKey()
    {
        return ['idHuman'];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idHuman', 'idOrganization'], 'integer'],
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
            'fio' => 'Fio',
            'idHuman' => 'Id Human',
            'CustomerName' => 'Customer Name',
            'idOrganization' => 'Id Organization',
        ];
    }
}
