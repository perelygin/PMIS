<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\EstimateWorkPackages */
/* @var $form ActiveForm */

		//$items = ArrayHelper::map($Organization,'idProject','ProjectName');
		$items = array('0'=>'Активна','1'=>'Закрыта');
		
		$params = [
			//'prompt' => 'Выберите проект'
		];
?>
<div class="UpdateEstimateWorkPackages">

    <?php $form = ActiveForm::begin(); 
    $BRinfo = $model->getBrInfo();
    ?>
 
  <div class="container">
	  <div class="row">
		  <div class="col-sm">
			<?php echo '<p> <b>'.$BRinfo['BRNumber']." ".$BRinfo['BRName'] . '</b></p>' ?>
		</div>
	  </div>	  
	  <div class="row">
		<div class="col-sm-4">
			<?= $form->field($model, 'dataEstimate')->widget(DatePicker::className(),[
			    'pluginOptions' => [
			        'autoclose'=>true,
			        'format' => 'yyyy-mm-dd'
			    ]
			]) ?>
			<?php 
				//echo DatePicker::widget([
				    //'name' => 'dataEstimate',
				    //'value' => date('Y-m-d'),
				    //'options' => ['placeholder' => 'Select issue date ...'],
				    //'pluginOptions' => [
				        //'format' => 'dd-mm-yyyy',
				        //'todayHighlight' => true
				    //]
				//]);
			?>
			</div>
		<div class="col-sm-4">
			<?= $form->field($model, 'EstimateName') ?>
		</div>
		<div class="col-sm-3">
			<?= $form->field($model, 'finished')->dropDownList($items,$params) ?>
		</div>
	  </div>	
      <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
      </div>
    <?php ActiveForm::end(); ?>

</div><!-- UpdateEstimateWorkPackages -->
