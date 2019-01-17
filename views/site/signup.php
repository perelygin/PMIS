<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<?php $form = ActiveForm::begin() ?>
<div class="container">
	<div class="row">
	    <div class="col-sm-3">
			<?= $form->field($model, 'username') ?>
		</div>	
		<div class="col-sm-3">		
			<?= $form->field($model, 'email') ?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-3">		
			<?= $form->field($model, 'mantisname') ?>
		</div>
		<div class="col-sm-3">		
			<?= $form->field($model, 'mantispwd')->passwordInput()  ?>
		</div>
	</div>	
	<div class="row">
		<div class="col-sm-4">
			<?= $form->field($model, 'password')->passwordInput() ?>
		</div>
	</div>	
	<div class="row">
		<div class="col-sm-4">
			<?= $form->field($model, 'retypepassword')->passwordInput() ?>			
		</div>	
	</div>		
</div>	
<div class="form-group">
 <div>
 <?= Html::submitButton('Регистрация', ['class' => 'btn btn-success']) ?>
 </div>
</div>
<?php ActiveForm::end() ?>
