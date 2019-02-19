<?php

namespace app\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
//use app\models\BusinessRequests;
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
		$sql = "SELECT 
				wbs.id, 
				wbs.name,
				br.BRName,
				br.BRNumber,
				sv.version_number,
				sv.version_number_s,
				sv.idsystem_versions
				FROM wbs
				LEFT OUTER JOIN SystemVersions sv ON wbs.idsystem_versions = sv.idsystem_versions
				LEFT OUTER JOIN BusinessRequests br ON wbs.idBr = br.idBR
				Where wbs.id = ".$this->id;
		$WBS = Yii::$app->db->createCommand($sql)->queryOne(); 
		
		$WBSInfo = array('name' => $this->name,
					     'idBr'=>$this->idBr,
					     'BRNumber'	=> $WBS['BRNumber'],
					     'BRName' => $WBS['BRName'],
					     'version_number_s' => $WBS['version_number_s'],
						 'idResultType'=>$this->idResultType);
        return $WBSInfo;
    }
    public function isWbsHasWork()  //есть ли работы по данному узлу
    {
		$sql = "SELECT  count(*)
				   FROM wbs  
				   inner JOIN WorksOfEstimate woe ON woe.idWbs=wbs.id
					where wbs.id = ".$this->id;
		 $WbsWorkNumber = Yii::$app->db->createCommand($sql)->queryScalar(); 
		 
		 if ($WbsWorkNumber>0) { 
			 return true;
		  }
		   else return false;
    }
    /*
     * 
     * Проверяет,  есть ли работа с таким номером mantis  по текущему результату в этой оценке
     */ 
    public function isWbsHasMantisNumber($idEstimateWorkPackages,$MantisNumber)  
    {
		$sql =  "SELECT count(*) mantisNumber FROM WorksOfEstimate 
					where idEstimateWorkPackages =".$idEstimateWorkPackages. " and idWbs=".$this->id." and mantisNumber = '".$MantisNumber."'";
		$WbsWorkNumber = Yii::$app->db->createCommand($sql)->queryScalar();
		 
		if ($WbsWorkNumber>0) { 
			 return true;
		  }
		   else return false;
	}	
}
