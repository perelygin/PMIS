<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SystemVersions".
 *
 * @property int $idsystem_versions
 * @property string $release_date
 * @property string $commit_ date
 * @property int $version_number
 * @property int $deleted
 */
class SystemVersions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'SystemVersions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['release_date', 'commit_ date'], 'safe'],
            [['version_number', 'deleted','released'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idsystem_versions' => 'Idsystem Versions',
            'release_date' => 'Release Date',
            'commit_ date' => 'Commit  Date',
            'version_number' => 'Version Number',
            'deleted' => 'Deleted',
        ];
    }
}
