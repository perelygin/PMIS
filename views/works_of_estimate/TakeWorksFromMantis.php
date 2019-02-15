<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\models\Wbs;
use app\models\EstimateWorkPackages;
use app\models\BusinessRequests;
use yii\helpers\Url;
use app\models\vw_settings; 
use app\models\RoleModel;


/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */

		//настройки
		$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path']);
		if (!is_null($settings)) $url_mantis = $settings->enm_str_value; //путь к мантиссе
		  else $url_mantis = '';
         //название оценки
         
         $EstimateWorkPackages = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEstimateWorkPackages]);
         if(!is_null($EstimateWorkPackages)){ 
			 $ewp_name = $EstimateWorkPackages->EstimateName;
			 $ewp_date = $EstimateWorkPackages->dataEstimate;
			 $ewp_finished = ($EstimateWorkPackages->isFinished())? 'Закрыта':'Активна';
			 }
		//ищем родителей для rootid
			$wbs_current_node = Wbs::findOne(['id'=>$id_node]);
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
											'id_node' => $id_node, ])];
			}
		//перечень результатов
		$BR = BusinessRequests::findOne(['idBR'=>$idBR]);
				
		//готовим массив для dropdownist c ролями
		$ResultType = RoleModel::find()->where(['idRoleModelType'=>$BR->get_BRRoleModelType()])->all();
		$items1 = ArrayHelper::map($ResultType,'idRole','RoleName');
		$params1 = [
		];
		
	//Wbs::findOne(['idBr'=>$idBr,'depth'=>'0'])
		$Results = $BR->getResults();
		$items = ArrayHelper::map($Results,'id','name');
		$params = [
		];
			

$this->title = "Создание работ";


$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
$form = ActiveForm::begin(); 
    ?>
    
	<div class="row">
		<div class="col-sm-12">   
		   <?php
				echo('
				 <p>Тут можно создать работы на основании инцидентов mantis. Для этого, указываем головной инцидент. Как правило 
				 это инцидент с БФТЗ(выводится список работ с инцидентами по результатам с типом "БФТЗ"). 
				 <br> 
				 Запросом к mantis получаем перечень связанных инцидентов, и отмечаем те из них,  по которым нужно создавать работы.
				 <br>
				 Работы создаются в последней оценке трудозатрат.В качестве исполнителя указывается текущий ответственный. Трудозатраты по созданной работе равны 0.
				 <br></p>');
				?> 
		</div>   
	</div>					
	<div class="row">
		<div class="col-sm-6"> 
				   <?php
						echo( '<b> Выбери инцидент с БФТЗ и нажми кнопку: </b>'. Html::submitButton('', [
										'span class' => 'glyphicon glyphicon-knight',
										'title'=>'Получить перечень связанных инцидентов',
										'name'=>'btn',
										'value' => 'mnt1_']).
						   '</p>
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
				   ?>
		</div>   
		<div class="col-sm-6"> 
			<?php
			echo('<b> Выбери инциденты по которым будут регистрироваться работы: </b></p>
		    <table border = "1" cellpadding="4" cellspacing="2">
			 <tr><th>Инцидент</th><th>Название</th><th>Ответственный</th><th>Проект</th><th></th></tr>
		  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=5>&nbsp;</td></tr>');
		   if(!empty($related_issue)){
			foreach($related_issue as $rli){
				echo('<tr><td>'.
				Html::a($rli['mantisNumber'], $url_mantis.$rli['mantisNumber'],['target' => '_blank'])
				.'</td><td>'.$rli['name'].'</td><td>'.$rli['handler'].
								'</td><td>'.$rli['project'].'</td>
								<td> <input name="relatedissue[]" type="checkbox" value='.$rli['mantisNumber'].'></td></tr>');
			}	
		   }
		   echo('</table>');
		   echo('Этих логинов mantis нет в команде проекта');
		   echo('<table border = "1" cellpadding="4" cellspacing="2">
			 <tr><th></th><th>Логин</th><th>ФИО</th><th>Роль</th></tr>
		  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=4>&nbsp;</td></tr>');
		   if(!empty($missingMembers)){
			foreach($missingMembers as $msm){
				
				//воттакой dropdown.....
				echo('<tr><td>');
				if($msm['id']>0){  //т.е. человек есть в справочнике людей
					echo(Html::submitButton('', [
							'span class' => 'glyphicon glyphicon-plus-sign',
							'title'=>'Добавить в команду ',
							'name'=>'btn',
							'value' => 'add_'.$msm['id']]));	 
					 }
				 echo('</td><td>'.$msm['handler'].'</td><td>'.$msm['fio'].'</td><td>
				<p><select name="idRole['.$msm['id'].']">');	 
				
				
				foreach($ResultType as $rlt){
					echo('<option value='.$rlt->idRole.'_'.$BR->getParentId($rlt->idRole).'>'.$rlt->RoleName.'</option>');
				}
				echo('</select></p>
				 </td>');
				 
				 
				 echo('</tr>');
			}	
		   }
		   echo('</table>');
			echo('<br>');		   

			?>
		</div>					
    </div>
    <div class="row">
		<div class="col-sm-4"> 
			</div>
		<div class="col-sm-4"> 
			<?php 
			echo(Html::submitButton('Регистрация работ по выбранным инцидентам', [
						//'span class' => 'glyphicon glyphicon-bishop',
						'title'=>'Регистрация работ по выбранным инцидентам',
						'name'=>'btn',
						'value' => 'mnt2_']));				
			?>
		</div>
		<div class="col-sm-4"> 
			</div>
	</div>	
<?php ActiveForm::end(); ?>
