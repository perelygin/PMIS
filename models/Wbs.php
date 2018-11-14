<?php

namespace app\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
/**
 * This is the model class for table "wbs".
 *
 * @property int $id
 * @property int $tree
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property string $name
 * @property string $mantis
 * @property string $description
 * @property int $idBr
 */
class Wbs extends \yii\db\ActiveRecord
{
	public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new WbsQuery(get_called_class());
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wbs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'idBr'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'idBr'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['mantis'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 1000],
            [['lft', 'rgt', 'depth','tree','idResultType','idResultStatus','idsystem_versions'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'name' => 'Результат',
            'mantis' => 'Mantis',
            'description' => 'Описание',
            'idBr' => 'Id Br',
            'idResultType'=>'Тип результата',
            'idResultStatus'=>'Текущий статус',
            'idsystem_versions'=>'Плановая версия'
        ];
    }
    //public function getChildren()
    //{
		
		//$Children = $this->find()->leaves()->all();
		
		//return $Children;
	//}
	
	  /*
     * возвращает название BR, по которой делалась оценка
     */
    public function getWbsInfo()
    {
		$WBSInfo = array('name' => $this->name,
							'idBr'=>$this->idBr	);
        return $WBSInfo;
    }
    public function isWbsHasWork()  //есть ли работы по данному узлу
    {
		$sql = "SELECT  count(*)
				   FROM Yii2pmis.wbs  
				   inner JOIN WorksOfEstimate woe ON woe.idWbs=wbs.id
					where wbs.id = ".$this->id;
		 $WbsWorkNumber = Yii::$app->db->createCommand($sql)->queryScalar(); 
		 
		 if ($WbsWorkNumber>0) { 
			 return true;
		  }
		   else return false;
    }
}
