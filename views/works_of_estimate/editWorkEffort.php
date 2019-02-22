<?php 
 use app\models\VwProjectCommand;
 use app\models\ServiceType;
 use yii\helpers\ArrayHelper;
 use yii\helpers\Html;
 use yii\widgets\ActiveForm;
 
 
	$ProjectCommand = VwProjectCommand::find()->where(['idBR'=>$idBR])->all();
	$items1 = ArrayHelper::map($ProjectCommand,'id','team_member');
	$params1 = [
		
	];
	$params11 = [
		'disabled'=>"disabled"
	];
	//$ServiceType = ServiceType::find()->all();
	$sql = "SELECT srt.idServiceType,srt.ServiceName FROM ProjectCommand as prc
					LEFT OUTER JOIN  ServiceType srt ON prc.idRole = srt.idRole
					where prc.id = ".$model->idTeamMember;
    $ServiceType =  Yii::$app->db->createCommand($sql)->queryAll();
        
	$items2 = ArrayHelper::map($ServiceType,'idServiceType','ServiceName');
	$params2 = [
	];
	$params22 = [
	    'prompt'=>'выбери тип услуги',
		'disabled'=>"disabled"
	];
?>
 
 <?php 
 
   $form = ActiveForm::begin();
 ?>
 
 <div class="row">
	 <div class="col-sm-4">
		 <?php 
		 if($mark == 0){
		   echo $form->field($model, 'idTeamMember')->dropDownList($items1,$params1);
		 } else {
			 echo $form->field($model, 'idTeamMember')->dropDownList($items1,$params11);
			 }  
		 ?>
	 </div>
	 <div class="col-sm-4">
		 <?php 
		 if($mark == 0){
			 echo $form->field($model, 'idServiceType')->dropDownList($items2,$params22);
		 }else {
			 echo $form->field($model, 'idServiceType')->dropDownList($items2,$params2);
			 }
		   
		 ?>
	 </div>
	 <div class="col-sm-1">
		 <?php 
		  if(!$mark == 0){
		     echo $form->field($model, 'workEffort');
		    } 
		 ?>
	 </div>
	 <div class="col-sm-1">
		 <?php 
		 if(!$mark == 0){
		   echo $form->field($model, 'workEffortHour');
		 }
		 
		 
		   
		 ?>
	 </div>
</div>
<div class="row">
	<div class="col-sm-4">
		</div>
	<div class="col-sm-2">
		 <?php
		 if($mark == 0){
				echo  Html::submitButton('Далее', ['class' => 'btn btn-success',
											'title'=>'Перейти к выбору услуги',
											'name'=>'btn',
											'value' => 'add1_']);
		 }									
					
		?>
		
	</div>
	<div class="col-sm-2">
		<?php
		if($mark != 0){
			echo  Html::submitButton('Сохранить', ['class' => 'btn btn-success',
											'title'=>'Сохранить трудозатраты по работе',
											'name'=>'btn',
											'value' => 'add2_']);
		}									
					
		?>
	
	</div>
	<div class="col-sm-2">
		<?php
		
			echo  Html::submitButton('Отмена', ['class' => 'btn btn-success',
											'title'=>'',
											'name'=>'btn',
											'value' => 'cancel_']);
										
					
		?>
	
	</div>
	<div class="col-sm-4">
		</div>
 </div>

<?php 
   ActiveForm::end();
 ?>
