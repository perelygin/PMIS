<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Organization".
 *
 * @property int $idOrganization
 * @property string $CustomerName
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Organization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CustomerName','ShortName'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idOrganization' => 'Id Organization',
            'CustomerName' => 'Customer Name',
        ];
    }
}
