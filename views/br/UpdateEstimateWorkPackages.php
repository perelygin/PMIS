<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\date\DatePicker;
use vova07\imperavi\Widget;
use kartik\datetime\DateTimePicker;

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

			<?php
			 //$form->field($model, 'dataEstimate')->widget(DatePicker::className(),[
			    //'pluginOptions' => [
			        //'autoclose'=>true,
			        //'format' => 'yyyy-mm-dd'
			    //]
			//]) 
			?>
			<?= $form->field($model, 'dataEstimate')->widget(DateTimePicker::className(),[
			    'pluginOptions' => [
			        'autoclose'=>true,
			        'format' => 'yyyy-mm-dd hh:ii:ss',
			         'todayHighlight' => true
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
	  <div class="row">
		<div class="col-sm-6">
			<?php 
				echo $form->field($model, 'ewp_comment')->widget(Widget::className(), [
				    'settings' => [
				        'lang' => 'ru',
				        'minHeight' => 200,
				        'plugins' => [
			            'fullscreen',
				        ],
				    ],
				]);
	        ?>
		</div>
	  </div>	
      <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
      </div>
    <?php ActiveForm::end(); ?>

</div><!-- UpdateEstimateWorkPackages -->
