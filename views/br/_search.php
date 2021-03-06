<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Projects;
use app\models\BRStatus;

use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\VwListOfBRSearch */
/* @var $form yii\widgets\ActiveForm */

 $Organization = Projects::find()->all();
 $items = ArrayHelper::map($Organization,'ProjectName','ProjectName');
  $params = [
        'prompt' => 'Выберите проект'
    ];
    
 $BRStatus = BRStatus::find()->all();
 $items1 = ArrayHelper::map($BRStatus,'idBRStatus','BRStatusName');
  $params1 = [
        'prompt' => 'Выберите cтатус BR'
       // '1'=>['selected'=>true]
    ];  
?>

<div class="vw-list-of-br-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

  <div class="container">
	   <div class="row">
		  
		  <div class="col-sm-3">
  			<?php
  				  echo $form->field($model, 'idBRStatusFilter')->dropDownList($items1,$params1);  
				 ?>
		  </div>  
		  <div class="col-sm-7">
  			<?= $form->field($model, 'ProjectName')->dropDownList($items,$params);  ?>
		  </div>  
		  <div class="col-sm-2">
  			<?= $form->field($model, 'mantis_filter');  ?>
		  </div>  
	   </div>    
  </div>  

    <?php // echo $form->field($model, 'StageName') ?>

    <?php // echo $form->field($model, 'StagesStatusName') ?>

    <?php // echo $form->field($model, 'Family') ?>

    <?php // echo $form->field($model, 'CustomerName') ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
      
    </div>

    <?php ActiveForm::end(); ?>

</div>
