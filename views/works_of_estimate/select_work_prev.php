<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use yii\grid\GridView;
use app\models\Wbs;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

 $wbs = WBS::find()->where(['and', 'wbs.rgt - wbs.lft <= 1',['=','wbs.idBr',$idBR]])->all();
 $items = ArrayHelper::map($wbs,'name','name');
?>

 <div class="row">
	<div class="col-sm-12">
		<?= GridView::widget([
		        'dataProvider' => $dataProvider,
		        'filterModel' => $searchModel,
		        'columns' => [
		            [
			            'class' => 'yii\grid\ActionColumn',
			            'header'=>'Выбери', 
			            'headerOptions' => ['width' => '80'],
			            'template' => '{add_prev_work}',
			            //Замыкание в анонимной функции PHP- 
			            'buttons' => [
			                'add_prev_work' => function ($url,$model) use($idBR,$idEWP){  
								//var_dump($model);
							    $url = Url::to(['works_of_estimate/add_work_prev', 'idBR'=>$idBR,'idEWP'=>$idEWP,'idPrevWrk' => $model['idWorksOfEstimate']]);
			                    return Html::a(
			                    '<span class="glyphicon glyphicon-ok"></span>', 
			                    $url,['title' => 'Выбери работу-предшественницу']);
			                },
			        ],
			        ],
		            ['class' => 'yii\grid\SerialColumn'],
		            ['class' => 'yii\grid\DataColumn', 
		             'attribute' => 'name',
					 'format' => 'text',
					 'label' => 'Результат',
					  'filter' => $items
					 ],
					 
		            ['class' => 'yii\grid\DataColumn', 
		             'attribute' => 'WorkName',
					 'format' => 'text',
					 'label' => 'Работа'
					 ],
		            
		            ['class' => 'yii\grid\DataColumn', 
		             'attribute' => 'mantisNumber',
					 'format' => 'text',
					 'label' => 'Инцидент'
					 ],
					 ['class' => 'yii\grid\DataColumn', 
		             'attribute' => 'idWorksOfEstimate',
					 'format' => 'text',
					 'label' => 'idWorksOfEstimate'
					 ],
		             
		
					
		        ],
		    ]); ?>		
	</div>
	
	
</div>



