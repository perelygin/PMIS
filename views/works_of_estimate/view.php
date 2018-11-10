<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */

$this->title = $model->idWorksOfEstimate;
$this->params['breadcrumbs'][] = ['label' => 'Works Of Estimates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="works-of-estimate-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->idWorksOfEstimate], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->idWorksOfEstimate], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idWorksOfEstimate',
            'idEstimateWorkPackages',
            'WorkName',
            'idWbs',
            'WorkDescription',
            'deleted',
        ],
    ]) ?>

</div>
