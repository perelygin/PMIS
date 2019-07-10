<?php

	use yii\helpers\Html;
	use app\models\Organization;
	use app\models\RoleModelType;
	use app\models\LifeCycleType;
    use yii\helpers\ArrayHelper;
    use app\models\Projects;
    use yii\widgets\ActiveForm;
    
		$Organization = Projects::find()->all();
		$items = ArrayHelper::map($Organization,'idProject','ProjectName');
		$params = [
			'prompt' => 'Выберите проект'
		];

		$RoleModelType = RoleModelType::find()->all();
		$items1 = ArrayHelper::map($RoleModelType,'idRoleModelType','RoleModelTypeName');
		$params1 = [
			'prompt' => 'Выберите ролевую модель'
		];
		$LifeCycleType = LifeCycleType::find()->all();
		$items2 = ArrayHelper::map($LifeCycleType,'idLifeCycleType','LifeCycleTypeName');
		$params2 = [
			'prompt' => 'Выберите шаблон wbs',
		];

/* @var $this yii\web\View */
/* @var $model app\models\VwListOfBR */

$this->title = 'Регистрация новой  BR';
$this->params['breadcrumbs'][] = ['label' => 'Vw List Of Brs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
	<div class="vw-list-of-br-create">
	 <?php $form = ActiveForm::begin(); ?>
	    <h1><?= Html::encode($this->title) ?></h1>
		<div class="container">
		   <div class="row">
			   <div class="col-sm-4">
					    <?= $form->field($model, 'idProject')->dropDownList($items,$params); ?>
		      </div>
		       <div class="col-sm-4">
					    <?= $form->field($model, 'BRRoleModelType')->dropDownList($items1,$params1); ?>
		      </div>
		      <div class="col-sm-4">
					   <?= $form->field($model, 'BRLifeCycleType')->dropDownList($items2,$params2); ?>
		      </div>
		   </div>
		   <div class="row">
			   <div class="col-sm-4">
					<?= $form->field($model, 'BRNumber')->textInput() ?>
		      </div>
		      <div class="col-sm-4">
					<?= $form->field($model, 'BRName')->textInput(['maxlength' => true]) ?>
		      </div>
		     <div class="col-sm-4">
					
		      </div>
		   </div>
	 	</div>
	
	</div>

	<div class="form-group">
        <?= Html::submitButton('Cохранить', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>

  
  
  
   
