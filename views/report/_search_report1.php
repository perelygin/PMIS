<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ResultStatus;
use app\models\VwProjectCommand;
use app\models\SystemVersions;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\VwReport1Search */
/* @var $form yii\widgets\ActiveForm */

$ResultStatus = ResultStatus::find()->where(['deleted'=>0])->all();
$items1 = ArrayHelper::map($ResultStatus,'idResultStatus','ResultStatusName');
$params1 = [
	'prompt' => 'Выберите статус',
];
$SystemVersions = SystemVersions::find()->where(['deleted'=>0])->all();
$items2 = ArrayHelper::map($SystemVersions,'idsystem_versions','version_number');
$params2 = [
 'prompt' => 'Выберите версию',
];

?>

<div class="vw-report1-search">

    <?php $form = ActiveForm::begin([
        'action' => ['report1'],
        'method' => 'get',
    ]); ?>
 <div class="container">
	<div class="row">
		  <div class="col-sm-2">
			  <?php  echo $form->field($model, 'BRNumber') ?>
		  </div>
		  <div class="col-sm-1">
			  <?php  echo $form->field($model, 'ResultPriorityOrder') ?>
		  </div>
		  <div class="col-sm-3">
			  <?php  echo $form->field($model, 'BRName') ?>
		  </div>
		  <div class="col-sm-3">
			  <?php  echo $form->field($model, 'fio') ?>
		  </div>
		  <div class="col-sm-3">
			  <?php echo $form->field($model, 'CustomerName') ?> 
		  </div>
		</div>
    <div class="row">
		  <div class="col-sm-6">
			 <?php  echo $form->field($model, 'name') ?>
		  </div>
		  
		  <div class="col-sm-3">
			  <?php  echo	$form->field($model, 'idResultStatus')->dropDownList($items1,$params1);	?>
			  
		  </div>
		  <div class="col-sm-3">
			  <?php echo	$form->field($model, 'idsystem_versions')->dropDownList($items2,$params2);	?>
			   <?php // echo $form->field($model, 'idsystem_versions') ?>
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
