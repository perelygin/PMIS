<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwListOfBR;

/**
 * VwListOfBRSearch represents the model behind the search form of `app\models\VwListOfBR`.
 */
class VwListOfBRSearch extends VwListOfBR
{
    /**
     * {@inheritdoc}
     */
    public $mantis_filter; 
    public function rules()
    {
        return [
            [['idBR', 'BRDeleted', 'BRnumber'], 'integer'],
            [['BRName', 'ProjectName','mantis_filter'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
	public function attributeLabels()
    {
        return [
            'mantis_filter' => 'Номер инцидента',
             ];
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

		
        $query = VwListOfBR::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'pagination' => [
				'pageSize' => 15,
			],
        ]);
        $this->load($params); 
		//ищем id BR,  в которых встречается номер инциндента из mantis_filter
		if(!empty($this->mantis_filter)){
				$sql = 'SELECT 
						  wbs.id,
						  wbs.idBr,
						  woe.mantisNumber
						 FROM wbs
						LEFT OUTER JOIN WorksOfEstimate woe ON wbs.id = woe.idWbs
						where woe.mantisNumber = '.$this->mantis_filter;
			
			$BRList = Yii::$app->db->createCommand($sql)->queryAll();
			$BRstr=array();
			if(!empty($BRList)){
			
				 foreach($BRList as $brl){
					 $BRstr[] = $brl['idBr']; 
					 }
				 //print_r($BRstr);die;
				  $query->andFilterWhere(['in','idBR',$BRstr]);
				}
		}
        
				
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idBR' => $this->idBR,
            'BRDeleted' => $this->BRDeleted,
           // 'BRnumber' => $this->BRnumber,
            //'ProjectName'=> $this->ProjectName,
        ]);

        $query->andFilterWhere(['like', 'BRName', $this->BRName])
            ->andFilterWhere(['like', 'BRnumber', $this->BRnumber])
            ->andFilterWhere(['like', 'ProjectName', $this->ProjectName]);

        
        return $dataProvider;
    }
}
