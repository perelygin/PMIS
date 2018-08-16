<?php

use yii\helpers\Html;
use yii\grid\GridView;

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
            'StageName',
            'StagesStatusName',
            'Family',
            'CustomerName',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
