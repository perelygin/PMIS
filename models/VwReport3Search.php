<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwReport1;

/**
 * VwReport1Search represents the model behind the search form of `app\models\VwReport1`.
 */
class VwReport3Search extends VwReport3
{
    /**
     * {@inheritdoc}
     */
    public $DateBeginFilter;  //Дата определяющую минимальную дату события
    public function rules()
    {
        return [
            [['BRNumber', 'id', 'idBr', 'idOrgResponsible', 'idResultStatus'], 'integer'],
            [['BRName', 'name',  'ResultStatusName', 'fio', 'CustomerName','ResultPriorityOrder','DateBeginFilter'], 'safe'],
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
        $query = VwReport3::find();

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
        //if (!empty($this->DateBeginFilter)){
			//echo $this->DateBeginFilter; die;
			//}
		
        // grid filtering conditions
        $query->andFilterWhere([
            'BRNumber' => $this->BRNumber,
            //'id' => $this->id,
            //'idBr' => $this->idBr,
            //'idOrgResponsible' => $this->idOrgResponsible,
            //'idResultStatus' => $this->idResultStatus,
            
        ]);

//Yii::$app->session->addFlash('error',$this->idResultStatus);

        $query->andFilterWhere(['like', 'BRName', $this->BRName])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'ResultStatusName', $this->ResultStatusName])
            ->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['like', 'CustomerName', $this->CustomerName]);

		$query->andFilterWhere(['>','begBate',$this->DateBeginFilter]);
		
		//if(!empty($this->ResultPriorityOrder)){
			//$RPO_arr = explode(",", $this->ResultPriorityOrder);
			//$query->andFilterWhere(['in','ResultPriorityOrder',$RPO_arr]);
		//} else{
			////echo($this->ResultPriorityOrder);die;
			//if($this->ResultPriorityOrder == '0'){
				////echo('нуль');die;
				//$query->andFilterWhere(['ResultPriorityOrder' => 0,]);
				//} 
			//}
		
        return $query->all();
    }
}
