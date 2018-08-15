<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BusinessRequests */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-requests-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'BRName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idProject')->textInput() ?>

    <?= $form->field($model, 'BRLifeCycleType')->textInput() ?>

    <?= $form->field($model, 'BRCurrentStage')->textInput() ?>

    <?= $form->field($model, 'BRCurrentStageStatus')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
