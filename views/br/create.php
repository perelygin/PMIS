<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VwListOfBR */

$this->title = 'Create Vw List Of Br';
$this->params['breadcrumbs'][] = ['label' => 'Vw List Of Brs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vw-list-of-br-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
