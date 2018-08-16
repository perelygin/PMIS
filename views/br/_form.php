<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VwListOfBR */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vw-list-of-br-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idBR')->textInput() ?>

    <?= $form->field($model, 'BRDeleted')->textInput() ?>

    <?= $form->field($model, 'BRnumber')->textInput() ?>

    <?= $form->field($model, 'BRName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ProjectName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'StageName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'StagesStatusName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Family')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CustomerName')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
