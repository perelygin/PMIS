<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */

$this->title = 'Create Works Of Estimate';
$this->params['breadcrumbs'][] = ['label' => 'Works Of Estimates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="works-of-estimate-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
