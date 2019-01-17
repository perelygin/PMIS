<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>
 <h1><?= Html::encode($this->title) ?></h1>
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
			<?= $form->field($model, 'mantispwd')->passwordInput()->hint('Если не заполнено, то не изменяется')  ?>
		</div>
	</div>	
	<div class="row">
		<div class="col-sm-4">
			<?= $form->field($model, 'password')->passwordInput()->hint('Если не заполнено, то не изменяется') ?>
		</div>
	</div>	
	<div class="row">
		<div class="col-sm-4">
			<?= $form->field($model, 'retypepassword')->passwordInput()->hint('Если не заполнено, то не изменяется') ?>			
		</div>	
	</div>		
</div>	
<div class="form-group">
 <div>
 <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
 </div>
</div>
<?php ActiveForm::end() ?>
