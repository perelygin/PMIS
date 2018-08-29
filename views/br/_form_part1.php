 <?php 
	use app\models\Organization;
	use app\models\RoleModelType;
	use app\models\LifeCycleType;
    use yii\helpers\ArrayHelper;
    use app\models\Projects;
    
		$Organization = Projects::find()->all();
		$items = ArrayHelper::map($Organization,'idProject','ProjectName');
		$params = [
			'prompt' => 'Выберите проект'
		];
		
		$RoleModelType = RoleModelType::find()->all();
		$items1 = ArrayHelper::map($RoleModelType,'idRoleModelType','RoleModelTypeName');
		$params1 = [
			'prompt' => 'Выберите ролевую модель',
			'disabled'=>"disabled"
		];
		$LifeCycleType = LifeCycleType::find()->all();
		$items2 = ArrayHelper::map($LifeCycleType,'idLifeCycleType','LifeCycleTypeName');
		$params2 = [
			'prompt' => 'Выберите шаблон wbs',
			'disabled'=>"disabled"
		];
 ?>
  
  
  
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
 	
 	
 	
 			
				
 
  
