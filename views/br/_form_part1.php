 <?php 
	use app\models\Organization;
    use yii\helpers\ArrayHelper;
    use app\models\Projects;
    
		$Organization = Projects::find()->all();
		$items = ArrayHelper::map($Organization,'idProject','ProjectName');
		$params = [
			'prompt' => 'Выберите проект'
		];
 ?>
  
  
  
   <div class="container">
	   <div class="row">
		   <div class="col-sm-6">
				    <?= $form->field($model, 'idProject')->dropDownList($items,$params); ?>
	      </div>
	   </div>
	   <div class="row">
		   <div class="col-sm-6">
				<?= $form->field($model, 'BRNumber')->textInput() ?>
	      </div>
	      <div class="col-sm-6">
				<?= $form->field($model, 'BRName')->textInput(['maxlength' => true]) ?>
	      </div>
	   </div>
 	</div>
 	
 	
 	
 			
				
 
  
