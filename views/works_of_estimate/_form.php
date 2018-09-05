<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="works-of-estimate-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idEstimateWorkPackages')->textInput() ?>

    <?= $form->field($model, 'WorkName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idWbs')->textInput() ?>

    <?= $form->field($model, 'WorkDescription')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deleted')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
