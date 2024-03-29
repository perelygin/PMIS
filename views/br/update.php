<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\VwListOfBR */

$this->title = 'Корректировка Br-' . $model->BRNumber. ' '.$model->BRName ;
$this->params['breadcrumbs'][] = ['label' => 'Список BR', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => 'BR-'.$model->BRNumber, 'url' => ['view', 'id' => $model->idBR]];
$this->params['breadcrumbs'][] = 'BR-'.$model->BRNumber;
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="vw-list-of-br-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'prj_comm_model'=>$prj_comm_model,
        'page_number' =>$page_number,
        'wbs_leaves'=>$wbs_leaves,
        'root_id'=>$root_id,
        'EstimateListdataProvider' => $EstimateListdataProvider, //для трудозатрат
    ]) ?>

</div>
