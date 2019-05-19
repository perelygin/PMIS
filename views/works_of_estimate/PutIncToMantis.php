<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\models\Wbs;
use app\models\EstimateWorkPackages;
use app\models\BusinessRequests;
use app\models\vw_settings; 
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */
         //название оценки
         
         //$EstimateWorkPackages = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEstimateWorkPackages]);
         $EWP_BrInfo = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEstimateWorkPackages])->getBrInfo();

			 
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
	//Wbs::findOne(['idBr'=>$idBr,'depth'=>'0'])
		$Results = $BR->getResults();
		$items = ArrayHelper::map($Results,'id','name');
		$params = [
		];
		
		//путь к мантиссе из настроек	
		$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path']);
		if (!is_null($settings)) $url_mantis = $settings->enm_str_value; //путь к мантиссе
		  else $url_mantis = '';
		
		//перечень инцидентов для привязки
		$mantis_links = array();
		 if($wbs_current_node->idResultType == 2 or $wbs_current_node->idResultType == 5
									or $wbs_current_node->idResultType == 7)  //БФТЗ или абонемент или МТ банка
		  {
			  $mantis_links = $BR->getMantisNumbers(1);
		  }
		 if($wbs_current_node->idResultType == 3 or $wbs_current_node->idResultType == 4)  //ПО или прочие
		  {
			  $mantis_links = $BR->getMantisNumbers(2);
		  }
		  if($wbs_current_node->idResultType == 6)  // тесты внутренние
		  {
			  $mantis_links = $BR->getMantisNumbers(6);
			 
		  }
			$WBSInfo = $wbs_current_node->getWbsInfo();	 
		
		
		  
$this->title = "Массовое создание инцидентов в mantis";


$this->params['breadcrumbs'][] = $this->title;
?>
<div class="works-of-estimate-view">

    <h1><?= Html::encode($this->title) ?></h1>
    Перечень работ,  которые необходимо выполнить для достижения результата:  <b> <?php echo $wbs_current_node->name?> </b>
    <br>
    Оценка трудозатрат: <b> <?php echo $EWP_BrInfo['EstimateName'] ?></b>  от <b> <?php echo $EWP_BrInfo['dataEstimate'].' '.$EWP_BrInfo['finished']   ?>  </b>
        
    <br>
    Выбери работы,  по которым нужно создать инциденты в mantis:
    <p>
     <?php 
		$form = ActiveForm::begin(); 
        
        if(count($VwListOfWorkEffort)>0){ // по элементу wbs есть работы
		echo('<table border = "1" cellpadding="4" cellspacing="2">');  
        $id = $VwListOfWorkEffort[0]['idWorksOfEstimate'];
        $i =0;
        $checkbox_str = (!empty($VwListOfWorkEffort[$i]['mantisNumber'])) ?  ' ' :'<input name="selectedWorks[]" type="checkbox" checked="checked" value='.$id.'>'; 
        
        echo('<tr><td colspan="2" ><b>'
                .Html::a($VwListOfWorkEffort[$i]['mantisNumber'], $url_mantis.$VwListOfWorkEffort[$i]['mantisNumber'],['target' => '_blank']).' '
		        .$VwListOfWorkEffort[$i]['WorkName']
		        .'</td><td>' 
		        .$checkbox_str
		        .'</td></tr>');
		 
        
		foreach($VwListOfWorkEffort as $vlwe){
			if($vlwe['idWorksOfEstimate'] == $id){
				if(isset($vlwe['workEffort'])){
					echo('<tr><td>'
						.$vlwe['team_member']
						.'</td><td>'
						.$vlwe['workEffort'].' ч.д.'
						.'</td><td></td></tr>');	
			    }
			}	else{
				 $id = $vlwe['idWorksOfEstimate'];	
 				 $checkbox_str = (!empty($vlwe['mantisNumber'])) ?  ' ' :'<input name="selectedWorks[]" type="checkbox" checked="checked" value='.$id.'>'; 
				 echo('<tr><td colspan="2"><b>'
				 .Html::a($vlwe['mantisNumber'], $url_mantis.$vlwe['mantisNumber'],['target' => '_blank']).' '
				 .$vlwe['WorkName']
				 .'</td><td>'
				 .$checkbox_str
 			     .'</td></tr>');
				 if(isset($vlwe['workEffort'])){
					 echo('<tr><td>'
					 
					 .$vlwe['team_member']
					 
					 .'</td><td>'
					 .$vlwe['workEffort'].' ч.д.'
					 
					 .'</td><td></td></tr>');		
				 }	 
			}
			
			
			$i =$i+1;
	      } 
	      echo '  </table>';
	      echo '<br>';
		}
         ?>
       
       
        
    </p>
    <div class="container">
		<div class="row">
			<div class="col-sm-6">
			<?php 
				if($WBSInfo['idResultType'] == 2 or $WBSInfo['idResultType'] == 3 
											     or $WBSInfo['idResultType'] == 4 
											     or $WBSInfo['idResultType'] == 5
											     or $WBSInfo['idResultType'] == 6
											     or $WBSInfo['idResultType'] == 7){
				 echo('
				 <p><b>Перед созданием инцидентов в mantis выбери инцидент,  к котрому они будет привязаны: </b></p>
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
		<div class="col-sm-6">
			<?php 
			if($WBSInfo['idResultType'] == 2 or $WBSInfo['idResultType'] == 3 or $WBSInfo['idResultType'] == 4 ){
			 echo('
			 <p><b>Можно изменить проект mantis, в котором будетут созданы инциденты: </b></p>
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
    <?php         
       echo(Html::submitButton('Создать инциденты', [
						//'span class' => 'glyphicon glyphicon-bishop',
						'title'=>'Создать инциденты в mantis',
						'name'=>'btn',
						'value' => 'mnt_'])).'     ';	
		echo(Html::submitButton('Отмена', [
						//'span class' => 'glyphicon glyphicon-bishop',
						//'title'=>'Создать инциденты в mantis',
						'name'=>'btn',
						'value' => 'cancl_']));					
		ActiveForm::end(); 
     ?>


</div>
