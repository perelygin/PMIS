
<?php
 ////скрипт для обновления страницы при добавлении оценки трудозатрат
		//$script = <<< JS
		//var b = document.getElementById("btn_pls1");
			//b.onclick = function() {
				////alert("Спасибо, что выбрали!"); 
				//document.getElementById("ddd1").submit();
				//return false;
			//};
			
			////b.addEventListener("click", function() {alert('Еще раз спасибо!')}, false); 
//JS;
////маркер конца строки, обязательно сразу, без пробелов и табуляции
		
		//$this->registerJs($script, yii\web\View::POS_READY);
?>

<?php


use app\models\EstimateWorkPackages;
use app\models\Wbs;
use app\models\VwProjectCommand;
use app\models\BusinessRequests;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Collapse;
use vova07\imperavi\Widget;
use app\models\vw_settings; 
//use app\models\Wbs;



/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */

$EWP_BrInfo = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$model->idEstimateWorkPackages])->getBrInfo();
$WBS = Wbs::findOne(['id'=>$model->idWbs]);
$WBSInfo = $WBS->getWbsInfo();
$BR = BusinessRequests::findOne(['idBR'=>$idBR]);
$mantis_links = array();
 if($WBS->idResultType == 2)  //БФТЗ
  {
	  $mantis_links = $BR->getMantisNumbers(1);
  }
 if($WBS->idResultType == 3 or $WBS->idResultType == 4)  //ПО
  {
	  $mantis_links = $BR->getMantisNumbers(2);
  }


$this->title = "BR-". $WBSInfo['BRNumber']." ".$WBSInfo['BRName'];
$ProjectCommand = VwProjectCommand::find()->where(['idBR'=>$idBR])->all();
$items1 = ArrayHelper::map($ProjectCommand,'id','team_member');
$params1 = [

];

$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path']);
	if (!is_null($settings)) $url_mantis = $settings->enm_str_value; //путь к мантиссе
	  else $url_mantis = '';
?>
<div class="works-of-estimate-update">
    <h3><?= Html::encode($this->title) ?></h3>
    <h4>Работа, необходимая для достижения результата: "<?= $WBSInfo['name'].'"'  ?>
        <br>
        Пакет оценок: "<?= Html::encode($EWP_BrInfo['EstimateName']).'" от '. $EWP_BrInfo['dataEstimate']?>
    </h4>
    
    
	
    <?php ///$form = ActiveForm::begin(); ?>
    
    
     <?php $form = ActiveForm::begin([
        'action' => ['update','idWorksOfEstimate'=>$model->idWorksOfEstimate,'idBR'=>$idBR,'idWbs'=>$model->idWbs,'idEstimateWorkPackages'=>$model->idEstimateWorkPackages],  
        'method' => 'post',
        'id' =>"frm1"]); 
     ?>
    
    
<div class="container">
	<div class="row">
	    <div class="col-sm-12">
					<?php 
					
					echo  //Html::a('<span class="glyphicon glyphicon-question-sign"></span>', $url1.'#SystemDesc23',['title' => 'Помощь по разделу',]).
					Html::submitButton('', [
										'span class' => 'glyphicon glyphicon-question-sign',
										'title'=>'Помощь по разделу',
										'name'=>'btn',
										'value' => 'help_'])
					 ?>		
		</div>
	</div>
   <div class="row">
		<div class="col-sm-9">
		    <?= $form->field($model, 'WorkName')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-sm-3">
			
		</div>
			
			

   </div> 
   
   <div class="row">
	   <div class="col-sm-6">
			<?php echo $form->field($model, 'WorkDescription')->widget(Widget::className(), [
					    'settings' => [
					        'lang' => 'ru',
					        'minHeight' => 150,
					        'plugins' => [
								'fullscreen',
					        ],
					    ],
					]);
			?>
	    </div>
		<div class="col-sm-6">
		<?php
			 //$url2 = Url::to(['works_of_estimate/create_workeffort',
			//'idBR'=>$idBR,
			//'idEstimateWorkPackages'=>$model->idEstimateWorkPackages,
			//'idWbs'=>$model->idWbs, 
			//'idWorksOfEstimate'=>$model->idWorksOfEstimate ]);  //добавление трудозатрат в работу
			echo '<p><b>Трудозатраты</b>   '
				//.Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
				//$url2,['title' => 'Добавить трудозатраты по работе',
						//'id'=>'btn_pls',
						//'onclick'=>'document.getElementById("frm1").submit();return true;']).'</p>';		
			 .Html::submitButton('', [
						'span class' => 'glyphicon glyphicon-plus-sign',
						'title'=>'Добавить трудозатраты по работе',
						'name'=>'btn',
						'value' => 'add_']).'</p>'; 
		?>
		   
		   <table border = "1" cellpadding="4" cellspacing="2"> 
			   <tr><th></th><th>Исполнтель</th><th></th></tr>
			  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=3>&nbsp;</td></tr>
			   <?php
			   	
						
			    if(count($VwListOfWorkEffort)>0){ // по работе  есть оценки трудозатрат
					foreach($VwListOfWorkEffort as $vlwe){
						//$url4 = Url::to(['works_of_estimate/delete_workeffort',
						 //'idBR'=>$idBR,
						 //'idEstimateWorkPackages'=>$model->idEstimateWorkPackages ,
						 //'idWbs'=>$model->idWbs,
						 //'idWorksOfEstimate'=>$model->idWorksOfEstimate, 
						 //'idLaborExpenditures'=>$vlwe['idLaborExpenditures']]);   //удаление трудозарат из работы	
						 		
						if(isset($vlwe['workEffort'])){
							echo('<tr><td>  '
							//.Html::a('<span class="glyphicon glyphicon-minus"></span>', $url4,['title' => 'Удалить трудозатраты по работе',])
							.Html::submitButton('', [
								'span class' => 'glyphicon glyphicon-plus-minus-sign',
								'title'=>'Удалить трудозатраты по работе',
								'name'=>'btn',
								'value' => 'del_'.$vlwe['idLaborExpenditures']])
								.'</td><td>'
								.$form->field($vlwe, 'idTeamMember',['inputOptions' => ['name'=>'team_member['.$vlwe['idLaborExpenditures'].']']])->dropDownList($items1,$params1)
								.'</td><td>'.$form->field($vlwe, 'workEffort',
								['inputOptions' => ['name'=>'workEffort['.$vlwe['idLaborExpenditures'].']']]).
								'</td></tr>');	
					    }
					}	
				}	
			   ?>
		   </table>
		   
	    </div>
	    </div>
	<div class="row">    
		<div class="col-sm-2">
			<?php 
			if(empty($model->mantisNumber)){
				echo  Html::submitButton('', [
						'span class' => 'glyphicon glyphicon-knight',
						'title'=>'Генерация инцидента в mantis',
						'name'=>'btn',
						'value' => 'mant_'])
						 .$form->field($model, 'mantisNumber');
			} else{
				echo $form->field($model, 'mantisNumber').
				Html::a($model->mantisNumber, $url_mantis.$model->mantisNumber,['target' => '_blank']);
				}							 
			?>
		</div>
		
		<div class="col-sm-8">
			
			<?php 
			if(empty($model->mantisNumber) and ($WBSInfo['idResultType'] == 2 or $WBSInfo['idResultType'] == 3 or $WBSInfo['idResultType'] == 4)){
			 echo('
			 <p><b>Перед созданием инцидента в mantis выбери инцидент,  к котрому он будет привязан: </b></p>
			    <table border = "1" cellpadding="4" cellspacing="2">
				 <tr><th>Результат</th><th>Работа</th><th>Номер инцидента</th><th></th></tr>
			  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=4>&nbsp;</td></tr>');
			
			  if(!empty($mantis_links)){
				  foreach($mantis_links as $mtl){
					echo('<tr><td>'.$mtl['name'].'</td><td>'.$mtl['WorkName'].'</td><td>'.$mtl['mantisNumber']
					.'</td><td> <input name="mantis_link" type="radio" value='.$mtl['mantisNumber'].'></td></tr>');  
				  }
			  }	  
			  
			 echo('</table>');
		    } else{ 
				
				}
			?>
		</div>
	</div>	
    <div class="row">
		<div class="col-sm-12">
			<?php
			echo Collapse::widget([
			    'items' => [
			        [
			            'label' => 'История изменений',
			            'content' => $this->render('_history', ['model' => $model, 'form' => $form,'LogDataProvider' => $LogDataProvider]),
			            'contentOptions' => [],
			            'options' => []
			        ],
			    ]
			]);
			?>
		</div> 
	</div> 
   </div> 
   
       <div class="form-group">
        <?php echo  Html::submitButton('Сохранить', ['class' => 'btn btn-success',
											'name'=>'btn',
											'value' => 'save_']);
					
		?>
    </div>
    
</div> 	
    
  
   


    <?php ActiveForm::end(); ?>
</div>



