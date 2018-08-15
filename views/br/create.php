<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BusinessRequests */

$this->title = 'Create Business Requests';
$this->params['breadcrumbs'][] = ['label' => 'Business Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-requests-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
