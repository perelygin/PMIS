<?php 
 use app\models\VwProjectCommand;
 use app\models\ServiceType;
 use app\models\EstimateWorkPackages;
 use app\models\Wbs;
 use app\models\WorksOfEstimate;


 use yii\helpers\ArrayHelper;
 use yii\helpers\Html;
 use yii\helpers\Url;
 use yii\widgets\ActiveForm;
 //Выпадающий список сотрудников
      $sql1 = 'SELECT * FROM vw_ProjectCommand  vpc
					RIGHT OUTER JOIN ServiceType srt ON srt.idRole = vpc.idRole
					where idBr='.$idBR;
	$TeamWithServs = Yii::$app->db->createCommand($sql1)->queryAll();
	
	
	$items1 = ArrayHelper::map($TeamWithServs,'id','team_member');
	$params1 = [
		
	];
	$params11 = [
		'disabled'=>"disabled"
	];
	//Выпадающий список услуг
	
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
	
	//для заголовка
	$EWP_BrInfo = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEstimateWorkPackages])->getBrInfo();
	$wbs_current_node = Wbs::findOne(['id'=>$idWbs]);
	$WBSInfo = $wbs_current_node->getWbsInfo();
	$WorkOfEstimate = WorksOfEstimate::findOne(['idWorksOfEstimate'=>$idWorksOfEstimate]);

//ищем родителей для rootid
			
			if(!is_null($wbs_current_node)){
				$parents = $wbs_current_node->parents()->all();	
				foreach($parents as $prn){
					$this->params['breadcrumbs'][] = ['label' => $prn['name'], 
					'url' => Url::toRoute(['br/update', 
											'id' =>$idBR, 
											'page_number' => 3, 
											'root_id'=>$prn['id']])];
				}
				
				
				$this->params['breadcrumbs'][]=	['label' => $wbs_current_node->name,'url' => Url::toRoute(['works_of_estimate/index', 
											'idBR' =>$idBR, 
											'id_node' => $idWbs, ])];
			}
			$this->params['breadcrumbs'][] = ['label' => 'Изменение параметров работы','url' => Url::toRoute(['works_of_estimate/update', 
											'idWorksOfEstimate'=>$idWorksOfEstimate,
											'idBR'=>$idBR,
											'idWbs'=>$idWbs,
											'idEstimateWorkPackages'=>$idEstimateWorkPackages,
											'page_number'=>1,
											 ])];

$this->title = 'Изменение трудозатрат по работе: '.$WorkOfEstimate->WorkName;
$this->params['breadcrumbs'][] = 'Изменение трудозатрат по работе';
?>
 
 <?php 
 
   $form = ActiveForm::begin();
 ?>
  <h3><?= Html::encode($this->title) ?></h3>
    <?= '<b>BR-'. $WBSInfo['BRNumber'].' "'.$WBSInfo['BRName'].'"</b>' ?>
    <br>
    Результат: " <b><?= $WBSInfo['name'].'"'  ?></b>
    <br>
    Оценка трудозатрат: <b> "<?= Html::encode($EWP_BrInfo['EstimateName']).'"</b> от <b>'. $EWP_BrInfo['dataEstimate'].'  '.$EWP_BrInfo['finished']?></b>
    <br>
    <br> 
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
