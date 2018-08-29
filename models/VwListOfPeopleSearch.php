<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwListOfPeople;

/**
 * VwListOfPeopleSearch represents the model behind the search form of `app\models\VwListOfPeople`.
 */
class VwListOfPeopleSearch extends VwListOfPeople
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'CustomerName'], 'safe'],
            [['idHuman', 'idOrganization'], 'integer'],
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
    public function search($params)
    {
        $query = VwListOfPeople::find();

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
        $query->andFilterWhere([
            'idHuman' => $this->idHuman,
            'idOrganization' => $this->idOrganization,
        ]);

        $query->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['like', 'CustomerName', $this->CustomerName]);

        return $dataProvider;
    }
}
