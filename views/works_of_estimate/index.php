<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
    
use app\models\Wbs;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchWorksOfEstimate */
/* @var $dataProvider yii\data\ActiveDataProvider */


    
		//ищем родителей для rootid
		  
			$wbs_current_node = Wbs::findOne(['id'=>$id_node]);
			if(!is_null($wbs_current_node)){
				$parents = $wbs_current_node->parents()->all();	
				foreach($parents as $prn){
					$this->params['breadcrumbs_wbs'][] = ['label' => $prn['name'], 'url' => Url::toRoute(['br/update', 'id' =>$idBR, 'page_number' => 3, 'root_id'=>$prn['id']])];
				}
				$this->params['breadcrumbs_wbs'][]=	['label' => $wbs_current_node->name];
			}
		
		
		$this->title = 'Перечень работ,  которые необходимо выполнить для достижения результата:  "' . $wbs_current_node->name . '"';

?>
<div class="works-of-estimate-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel, 'idBR'=>$idBR, 'id_node'=>$id_node, 'idEstimateWorkPackages'=>$idEstimateWorkPackages]); ?>

    <p>
        <?= Html::a('Create Works Of Estimate', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'idWorksOfEstimate',
            //'idEstimateWorkPackages',
            'WorkName',
            //'idWbs',
            //'WorkDescription',
            //'deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
