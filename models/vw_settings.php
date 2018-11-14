<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_settings".
 *
 * @property int $id_param
 * @property string $Prm_name
 * @property string $Prm_description
 * @property int $Prm_enum_id id  значения параметра
 * @property string $enm_num_value
 * @property string $enm_str_value
 */
class vw_settings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_settings';
    }

	public static function primaryKey()
    {
        return ['id_param'];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_param', 'Prm_enum_id'], 'integer'],
            [['enm_num_value'], 'number'],
            [['Prm_name'], 'string', 'max' => 100],
            [['Prm_description'], 'string', 'max' => 2000],
            [['enm_str_value'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_param' => 'Id Param',
            'Prm_name' => 'Параметр системы',
            'Prm_description' => 'Описание параметра',
            'Prm_enum_id' => 'Prm Enum ID',
            'enm_num_value' => 'Число',
            'enm_str_value' => 'Строка',
        ];
    }
}
