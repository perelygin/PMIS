<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WorksOfEstimate;

/**
 * SearchWorksOfEstimate represents the model behind the search form of `app\models\WorksOfEstimate`.
 */
class SearchWorksOfEstimate extends WorksOfEstimate
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idWorksOfEstimate', 'idEstimateWorkPackages', 'idWbs', 'deleted'], 'integer'],
            [['WorkName', 'WorkDescription'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$id_node,$idEstimateWorkPackages)
    {
        $query = WorksOfEstimate::find()->where(['deleted' => 0, 'idWbs'=>$id_node]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if(isset($this->idEstimateWorkPackages)){//если фильтр установен, то фильтруем по нему
			$query->andFilterWhere(['idEstimateWorkPackages' => $this->idEstimateWorkPackages]);
		}else{// иначе фильруем по значению по умолчанию
			$query->andFilterWhere(['idEstimateWorkPackages' => $idEstimateWorkPackages]);
			$this->idEstimateWorkPackages = $idEstimateWorkPackages;
			}
        

        $query->andFilterWhere(['like', 'WorkName', $this->WorkName])
            ->andFilterWhere(['like', 'WorkDescription', $this->WorkDescription]);

        return $dataProvider;
    }
}
