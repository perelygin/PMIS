<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BusinessRequests */

$this->title = $model->idBR;
$this->params['breadcrumbs'][] = ['label' => 'Business Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-requests-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->idBR], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->idBR], [
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
            'idBR',
            'BRName',
            'idProject',
            'BRLifeCycleType',
            'BRCurrentStage',
            'BRCurrentStageStatus',
        ],
    ]) ?>

</div>
