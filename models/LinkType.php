<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "LinkType".
 *
 * @property int $idLinkType
 * @property string $LinkTypeName
 */
class LinkType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'LinkType';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['LinkTypeName'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idLinkType' => 'Id Link Type',
            'LinkTypeName' => 'Link Type Name',
        ];
    }
}
