<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Форма фильтра для выбора работы-предшественицы
 */
class select_Work_search extends Model
{
    public $name;
    public $WorkName;
    public $mantisNumber;
    public $idWorksOfEstimate;
    
     public function rules()
    {
        return [
          [['name', 'WorkName','mantisNumber','idWorksOfEstimate'], 'safe'],
        ];
    }
    public function search($params,$idEWP,$idBR,$idWorksOfEstimate)  
    {
        //$query = VwListOfPeople::find();
		//$subQuery = (new \yii\db\Query())->from('WorksOfEstimate')
		//							  ->where(['idEstimateWorkPackages' =>$idEWP]);
									  
		//уже привязанные работы
		$subQuery1 = (new \yii\db\Query())
							->select(['idFirstWork'])
							->from('Links')
							->where(['=','idSecondWork',$idWorksOfEstimate]);
		//выбираем все работы, по пакету оценок,  без учета работы,  к которой ппривязываем работы, и без  учета уже привязанных.		
		$subQuery = (new \yii\db\Query())->from('WorksOfEstimate')
									  ->where(['and',['not in','idWorksOfEstimate',$subQuery1],['and',['!=','idWorksOfEstimate',$idWorksOfEstimate],['=','idEstimateWorkPackages',$idEWP]]]);									 
		//выбираем результаты с работами
		$query = (new \yii\db\Query())
						->select(['wbs.id','wbs.tree','wbs.lft','wbs.rgt','wbs.depth','name',
								  'wbs.idBr','woe.idEstimateWorkPackages',
								  'idWorksOfEstimate','WorkName','mantisNumber'])
						->from('wbs')
						->rightJoin(['woe' => $subQuery], 'woe.idWbs = wbs.id')
						->where(['and', 'wbs.rgt - wbs.lft <= 1',['=','wbs.idBr',$idBR]]);
								
        // add conditions that should always apply here

        //$dataProvider = new ActiveDataProvider([
            //'query' => $query,
        //]);

		$dataProvider = new ActiveDataProvider([
						    'query' => $query,
						    'pagination' => [
						        'pageSize' => 8,
						    ],
						]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //// grid filtering conditions
        //$query->andFilterWhere([
            //'idHuman' => $this->idHuman,
            //'idOrganization' => $this->idOrganization,
        //]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'WorkName', $this->WorkName])
            ->andFilterWhere(['like', 'mantisNumber', $this->mantisNumber]);

        return $dataProvider;
    }
    
    
}
