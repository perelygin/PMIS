<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\EstimateWorkPackages;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\SearchWorksOfEstimate */
/* @var $form yii\widgets\ActiveForm */


        //$EstimateWorkPackages = EstimateWorkPackages::find()->where(['deleted' => 0, 'idBR'=>$idBR])->all();
		//$items = ArrayHelper::map($EstimateWorkPackages,'idEstimateWorkPackages','EstimateName');  //.' от '.'dataEstimate'
		$items = EstimateWorkPackages::find()
					->select(['EstimateName','idEstimateWorkPackages'])
					->where(['deleted' => 0, 'idBR'=>$idBR])
					->indexBy('idEstimateWorkPackages')
					->column();
		$params = [
			'prompt' => 'Выберите оценку',
			//'4' => ['Selected' => true]
			//$idEstimateWorkPackages => ['Selected' => true]
			
		];
		
// 		
?>


<div class="works-of-estimate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index','idBR'=>$idBR, 'id_node'=>$id_node],
        'method' => 'get',
    ]); ?>

    <?php // $form->field($model, 'idWorksOfEstimate') ?>

    <?= $form->field($model, 'idEstimateWorkPackages')->dropDownList($items,$params); ?>

    <?php // $form->field($model, 'WorkName') ?>

    <?php // $form->field($model, 'idWbs') ?>

    <?php // $form->field($model, 'WorkDescription') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
