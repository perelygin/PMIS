<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_ProjectCommand".
 *
 * @property int $id
 * @property int $idBR
 * @property int $idHuman
 * @property int $idRole
 * @property int $parent_id
 * @property string $team_member
 */
class VwProjectCommand extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_ProjectCommand';
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
            [['id', 'idBR', 'idHuman', 'idRole', 'parent_id'], 'integer'],
            [['idBR', 'idHuman', 'idRole', 'parent_id'], 'required'],
            [['team_member'], 'string', 'max' => 247],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idBR' => 'Id Br',
            'idHuman' => 'Id Human',
            'idRole' => 'Id Role',
            'parent_id' => 'Parent ID',
            'team_member' => 'Член команды',
        ];
    }
}
