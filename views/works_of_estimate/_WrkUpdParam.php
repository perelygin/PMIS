<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use app\models\vw_settings; 
use app\models\BusinessRequests;
/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */
/* @var $form yii\widgets\ActiveForm */


$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path']);
	if (!is_null($settings)) $url_mantis = $settings->enm_str_value; //путь к мантиссе
	  else $url_mantis = '';
	  
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
	$WBSInfo = $WBS->getWbsInfo();	  
?>

<div class="container">
	<div class="row">
	    <div class="col-sm-12">
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
		
		<div class="col-sm-4">
			
			<?php 
			if(empty($model->mantisNumber) and ($WBSInfo['idResultType'] == 2 or $WBSInfo['idResultType'] == 3 or $WBSInfo['idResultType'] == 4)){
			 echo('
			 <p><b>Перед созданием инцидента в mantis выбери инцидент,  к котрому он будет привязан: </b></p>
			    <table border = "1" cellpadding="4" cellspacing="2">
				 <tr><th>Результат</th><th>Работа</th><th>Номер инцидента</th><th></th></tr>
			  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=4>&nbsp;</td></tr>');
			
			  if(!empty($mantis_links)){
				  $i=0;
				  
				  foreach($mantis_links as $mtl){
					$checked='';
					if($i == 0)  $checked = 'checked'; 
					echo('<tr><td>'.$mtl['name'].'</td><td>'.$mtl['WorkName'].'</td><td>'.$mtl['mantisNumber']
					.'</td><td> <input name="mantis_link" type="radio" value='.$mtl['mantisNumber'].' '.$checked.' ></td></tr>');  
					$i=$i+1;
				  }
			  }	  
			  
			 echo('</table>');
		    } else{ 
				
				}
			?>
		</div>
		<div class="col-sm-4">
			<?php 
			//print_r($MantisPrjLstArray);die;
			if(empty($model->mantisNumber) and ($WBSInfo['idResultType'] == 2 or $WBSInfo['idResultType'] == 3 or $WBSInfo['idResultType'] == 4)){
			 echo('
			 <p><b>Можно изменить проект mantis, в котором будет создан инцидент: </b></p>
			    <table border = "1" cellpadding="4" cellspacing="2">
				 <tr><th>Название проекта mantis</th><th></th></tr>
			  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=2>&nbsp;</td></tr>');
			
			  if(!empty($MantisPrjLstArray)){
				  foreach($MantisPrjLstArray as $key=>$mpl){
					
					echo('<tr><td>'.$mpl['name'].'</td><td> <input name="mantis_prj" type="radio" value='.$key.' '.$mpl['Checked'].'></td></tr>');  
				  }
			  }	  
			  
			 echo('</table>');
		    } else{ 
				
				}
			?>	
		</div>			
		
		
	</div>	
</div>
