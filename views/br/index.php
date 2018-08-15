<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Business Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-requests-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Business Requests', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idBR',
            'BRName',
            'idProject',
            'BRLifeCycleType',
            'BRCurrentStage',
            //'BRCurrentStageStatus',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
