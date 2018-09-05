<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */

$this->title = 'Update Works Of Estimate: ' . $model->idWorksOfEstimate;
$this->params['breadcrumbs'][] = ['label' => 'Works Of Estimates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idWorksOfEstimate, 'url' => ['view', 'id' => $model->idWorksOfEstimate]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="works-of-estimate-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
