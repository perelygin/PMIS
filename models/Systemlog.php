<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Systemlog".
 *
 * @property int $idSystemlog
 * @property int $IdTypeObject Тип объекта логирования  1 - BR 2 -  wbs 3 - оценка трудозатрат 4 - работа  5 -  оценка работы 
 * @property int $idObject
 * @property string $SystemLogString
 * @property int $IdUser
 * @property string $DataChange
 */
class Systemlog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Systemlog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdTypeObject', 'idObject', 'IdUser'], 'integer'],
            [['DataChange'], 'safe'],
            [['SystemLogString'], 'string', 'max' => 4500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idSystemlog' => 'Id',
            'IdTypeObject' => 'Id Type Object',
            'idObject' => 'Id Object',
            'SystemLogString' => 'Лог',
            'IdUser' => 'Id User',
            'DataChange' => 'Дата изменения',
        ];
    }
}
