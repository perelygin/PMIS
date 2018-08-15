<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BusinessRequests */

$this->title = 'Update Business Requests: ' . $model->idBR;
$this->params['breadcrumbs'][] = ['label' => 'Business Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idBR, 'url' => ['view', 'id' => $model->idBR]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="business-requests-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
