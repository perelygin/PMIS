<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ResultStatus;
use app\models\VwProjectCommand;
use app\models\SystemVersions;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\models\VwReport1Search */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="vw-report1-search">

    <?php $form = ActiveForm::begin([
        'action' => ['report3'],
        'method' => 'get',
    ]); ?>
 <div class="container">
	<div class="row">
		  <div class="col-sm-3">
			  <?php echo $form->field($model, 'DateBeginFilter')->widget(DatePicker::className(),[
			    'pluginOptions' => [
			        'autoclose'=>true,
			        'format' => 'yyyy-mm-dd',
			         'todayHighlight' => true
			    ]
			]) ?>
		  </div>
		  <div class="col-sm-3">
			  <?php  echo $form->field($model, 'BRNumber') ?>
		  </div>
		  <div class="col-sm-6">
			  <?php  echo $form->field($model, 'BRName') ?>
		  </div>
		  
		</div>
    <div class="row">
		  <div class="col-sm-6">
			 <?php?>
		  </div>
		  
		  <div class="col-sm-3">
			  <?php // echo	$form->field($model, 'idResultStatus')->dropDownList($items1,$params1);	
					//echo $form->field($model, 'ResultPriorityOrder')
					// echo $form->field($model, 'fio') 
					 // echo $form->field($model, 'name') 
					 //echo $form->field($model, 'CustomerName')
			   ?>
			  
		  </div>
		  <div class="col-sm-3">
			  
		  </div>
	</div>
</div>
  
    <div class="form-group">
        
         <?php
                  echo Html::submitButton('Искать', ['class' => 'btn btn-primary',
                 		'title'=>'Строить отчет',
						'name'=>'btn',
						'value' => 'filter'
                   ]).'  '.
				   Html::submitButton('в Excel', ['class' => 'btn btn-primary',
						'title'=>'Выгрузить в Excel',
						'name'=>'btn',
						'value' => 'excel'
				   ]) ;
              ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
