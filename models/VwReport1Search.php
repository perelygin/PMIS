<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwReport1;

/**
 * VwReport1Search represents the model behind the search form of `app\models\VwReport1`.
 */
class VwReport1Search extends VwReport1
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['BRNumber', 'id', 'idBr', 'idOrgResponsible', 'idResultStatus'], 'integer'],
            [['BRName', 'name', 'mantis', 'ResultStatusName', 'fio', 'CustomerName'], 'safe'],
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
        $query = VwReport1::find();

        // add conditions that should always apply here

        //$dataProvider = new ActiveDataProvider([
            //'query' => $query,
        //]);
		

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $query->all();
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'BRNumber' => $this->BRNumber,
            'id' => $this->id,
            'idBr' => $this->idBr,
            'idOrgResponsible' => $this->idOrgResponsible,
            'idResultStatus' => $this->idResultStatus,
        ]);

        $query->andFilterWhere(['like', 'BRName', $this->BRName])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mantis', $this->mantis])
            ->andFilterWhere(['like', 'ResultStatusName', $this->ResultStatusName])
            ->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['like', 'CustomerName', $this->CustomerName]);

        return $query->all();
    }
}
