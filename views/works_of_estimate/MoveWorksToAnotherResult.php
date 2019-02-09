<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\models\Wbs;
use app\models\EstimateWorkPackages;
use app\models\BusinessRequests;
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
			

$this->title = "Перемещение работ между результатами";


$this->params['breadcrumbs'][] = $this->title;
?>
<div class="works-of-estimate-view">

    <h1><?= Html::encode($this->title) ?></h1>
    Перечень работ,  которые необходимо выполнить для достижения результата:  <b> <?php echo $wbs_current_node->name?> </b>
    <br>
    Оценка трудозатрат: <b> <?php echo $EWP_BrInfo['EstimateName'] ?></b>  от <b> <?php echo $EWP_BrInfo['dataEstimate'].' '.$EWP_BrInfo['finished']   ?>  </b>
        
    <br>
    Выбери работы,  которые нужно перенести:
    <p>
     <?php 
		$form = ActiveForm::begin(); 
        
        if(count($VwListOfWorkEffort)>0){ // по элементу wbs есть работы
		echo('<table border = "1" cellpadding="4" cellspacing="2">');  
        $id = $VwListOfWorkEffort[0]['idWorksOfEstimate'];
        $i =0;
        
        echo('<tr><td colspan="2" ><b>'
		        .$VwListOfWorkEffort[$i]['WorkName']
		        .'</td><td> <input name="selectedWorks[]" type="checkbox" value='.$id.'></td></tr>');
        
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
				
				 echo('<tr><td colspan="2"><b>' .$vlwe['WorkName'].'</td><td> <input name="selectedWorks[]" type="checkbox" value='.$id.'></td></tr>');
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
        Выбери результат, в который будут перенесены работы. <b>Внимание, работы переносятся в пределах выбранной оценки!!!</b>
        <br>
        <?= $form->field($model, 'NewResult')->dropDownList($items,$params) ?>
    </p>
    <?php         
        echo Html::submitButton('Выпонить перенос', ['class' => 'btn btn-primary']); 
		ActiveForm::end(); 
     ?>


</div>
