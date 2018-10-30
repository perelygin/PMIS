<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VwListOfBRSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Фазы проекта(BR)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vw-list-of-br-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новая BR', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'idBR',
            //'BRDeleted',
            'BRnumber',
            'BRName',
            //'ProjectName',
            //'StageName',
            //'StagesStatusName',
            //'Family',
            //'CustomerName',

			[
		'class' => 'yii\grid\ActionColumn',
		'headerOptions' => ['width' => '120'],
		'template' => '{update} {delete}',
		//Замыкание в анонимной функции PHP- я нихера не понял как это работает  -(((
		'buttons' => [
			'delete' => function ($url,$model){  
				$url = Url::to(['br/delete', 'id' =>$model->idBR]);
				return Html::a(
				'<span class="glyphicon glyphicon-trash"></span>', 
				$url,['title' => 'Удалить BR']);
			},
			'update' => function ($url,$model){  
				$url = Url::to(['br/update', 'id' =>$model->idBR]);
				return Html::a(
				'<span class="glyphicon glyphicon-pencil"></span>', 
				$url,['title' => 'Изменить BR']);
			},
		  ],
		
	],
        ],
    ]); ?>
</div>
