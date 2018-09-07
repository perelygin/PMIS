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
			//'prompt' => 'Выберите оценку',
			////'4' => ['Selected' => true]
			////$idEstimateWorkPackages => ['Selected' => true]
			
		];
		

        //скрипт для обновления страницы при выборе пакета оценок
		$script = <<< JS
		var b = document.getElementById("searchworksofestimate-idestimateworkpackages");
			b.onchange = function() {
				//alert("Спасибо, что выбрали!"); 
				document.getElementById("w111").submit();
			};
			
			//b.addEventListener("click", function() {alert('Еще раз спасибо!')}, false); 
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
		
		$this->registerJs($script, yii\web\View::POS_READY);

?>


<div class="works-of-estimate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index','idBR'=>$idBR, 'id_node'=>$id_node],
        'method' => 'post',
        'id' =>'w111'
    ]); ?>

    <?php // $form->field($model, 'idWorksOfEstimate') ?>

    <?= $form->field($model, 'idEstimateWorkPackages')->dropDownList($items,$params); ?>
   
   

    <div class="form-group">
        <?php 
        // вскрыть, если перестанет рабоать по id
        // Html::submitButton('Выбрать', ['class' => 'btn btn-primary']) ?>
       
    </div>

    <?php ActiveForm::end(); ?>

</div>
