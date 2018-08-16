<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\VwListOfBR */

$this->title = 'Update Vw List Of Br: ' . $model->idBR;
$this->params['breadcrumbs'][] = ['label' => 'Vw List Of Brs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idBR, 'url' => ['view', 'id' => $model->idBR]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vw-list-of-br-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
