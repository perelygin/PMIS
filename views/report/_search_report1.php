<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VwReport1Search */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vw-report1-search">

    <?php $form = ActiveForm::begin([
        'action' => ['report1'],
        'method' => 'get',
    ]); ?>
 <div class="container">
	<div class="row">
		  <div class="col-sm-3">
			  <?php  echo $form->field($model, 'BRNumber') ?>
		  </div>
		  <div class="col-sm-3">
			  <?php  echo $form->field($model, 'BRName') ?>
		  </div>
		  <div class="col-sm-3">
			  <?php  echo $form->field($model, 'name') ?>
		  </div>
		  <div class="col-sm-3">
			   <?php echo $form->field($model, 'ResultStatusName') ?>
		  </div>
		</div>
    <div class="row">
		  <div class="col-sm-3">
			<?php  echo $form->field($model, 'mantis') ?>
		  </div>
		  <div class="col-sm-3">
			  <?php  echo $form->field($model, 'fio') ?>
		  </div>
		  <div class="col-sm-3">
			  <?php echo $form->field($model, 'CustomerName') ?>
		  </div>
		  <div class="col-sm-3">
			  
		  </div>
	</div>
</div>
  
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
