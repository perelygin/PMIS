<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use app\models\VwProjectCommand;

$ProjectCommand = VwProjectCommand::find()->where(['idBR'=>$idBR])->all();
$items1 = ArrayHelper::map($ProjectCommand,'id','team_member');
$params1 = [
];
/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */
/* @var $form yii\widgets\ActiveForm */
?>
   <div class="row">
	   
		<div class="col-sm-12">
		<?php
			
			echo '<p><b>Трудозатраты</b>   '
				 .Html::submitButton('', [
						'span class' => 'glyphicon glyphicon-plus-sign',
						'title'=>'Добавить трудозатраты по работе',
						'name'=>'btn',
						'value' => 'add_']).'</p>'; 
		?>
		   
		   <table border = "1" cellpadding="4" cellspacing="2"> 
			   <tr><th></th><th>Исполнитель</th><th>Тип услуги</th><th></th><th></th></tr>
			  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=4>&nbsp;</td></tr>
			   <?php
			   	
						
			    if(count($VwListOfWorkEffort)>0){ // по работе  есть оценки трудозатрат
					foreach($VwListOfWorkEffort as $vlwe){
						if(isset($vlwe['workEffort'])){
							echo('<tr><td>  '
							.Html::submitButton('', [
								'span class' => 'glyphicon glyphicon-minus-sign',
								'title'=>'Удалить трудозатраты по работе',
								'name'=>'btn',
								'value' => 'del_'.$vlwe['idLaborExpenditures']]).' '
							.Html::submitButton('', [
								'span class' => 'glyphicon glyphicon-pencil',
								'title'=>'Изменить трудозатраты по работе',
								'name'=>'btn',
								'value' => 'edit_'.$vlwe['idLaborExpenditures']])	
								.'</td><td>'.$vlwe['team_member']
								//.$form->field($vlwe, 'idTeamMember',['inputOptions' => ['name'=>'team_member['.$vlwe['idLaborExpenditures'].']']])->dropDownList($items1,$params1)
								.'</td><td>'.$vlwe['ServiceName']
								.'</td><td>'
								.$form->field($vlwe, 'workEffort',['inputOptions' => ['name'=>'workEffort['.$vlwe['idLaborExpenditures'].']']])
								.'</td><td>'.$form->field($vlwe, 'workEffortHour',['inputOptions' => ['name'=>'workEffortHour['.$vlwe['idLaborExpenditures'].']']])
								.'</td></tr>');	
					    }
					}	
				}	
			   ?>
		   </table>
		   
	    </div>
   </div>
