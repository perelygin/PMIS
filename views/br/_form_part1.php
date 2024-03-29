 <?php 
	use app\models\Organization;
	use app\models\RoleModelType;
	use app\models\LifeCycleType;
    use yii\helpers\ArrayHelper;
    use app\models\Projects;
    use vova07\imperavi\Widget;
    use kartik\datetime\DateTimePicker;
    use kartik\date\DatePicker;
    use app\models\BRStatus;
    
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
		$BRStatus = BRStatus::find()->all();
		 $items3 = ArrayHelper::map($BRStatus,'idBRStatus','BRStatusName');
		  $params3 = [
		        'prompt' => 'Выберите cтатус BR'
		        //'1'=>['selected'=>true]
		    ]; 
		
 ?>
  
  
  
   <div class="container">
	   <div class="row">
		  <div class="col-sm-3">
				<?= $form->field($model, 'idProject')->dropDownList($items,$params); ?>
	      </div>
	      <div class="col-sm-3">
				<?= $form->field($model, 'BRRoleModelType')->dropDownList($items1,$params1); ?>
		  </div>
		  <div class="col-sm-3">
				<?= $form->field($model, 'BRLifeCycleType')->dropDownList($items2,$params2); ?>
		  </div>
		  <div class="col-sm-3">
				<?= $form->field($model, 'BRStatus')->dropDownList($items3,$params3); ?>
		  </div>
		 </div>
	   <div class="row">
		   <div class="col-sm-3">
				<?= $form->field($model, 'BRNumber')->textInput() ?>
	      </div>
	      <div class="col-sm-3">
				<?= $form->field($model, 'BRName')->textInput(['maxlength' => true]) ?>
	      </div>
	      <div class="col-sm-3">
				<?php
				//$form->field($model, 'BRDateBegin')->widget(DateTimePicker::className(),[
			    //'pluginOptions' => [
			        //'autoclose'=>true,
			        //'format' => 'yyyy-mm-dd hh:ii:ss',
			         //'todayHighlight' => true
			    //]
			//]) 
			echo $form->field($model, 'BRDateBegin')->widget(DatePicker::className(),[
				    'pluginOptions' => [
				        'autoclose'=>true,
				        'format' => 'yyyy-mm-dd',
				         'todayHighlight' => true
				    ]
				]); 
			?>
	      </div>
	   </div>
	   <div class="row">
		<div class="col-sm-6">
			<?php 
				echo $form->field($model, 'BRDescription')->widget(Widget::className(), [
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
 	</div>
 	
 	
 	
 			
				
 
  
