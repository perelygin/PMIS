<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\bootstrap\Collapse;

use app\models\vw_settings; 
use app\models\Wbs;
use app\models\VwProjectCommand;
use app\models\BusinessRequests;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchWorksOfEstimate */
/* @var $dataProvider yii\data\ActiveDataProvider */

		//ищем родителей для rootid
			$wbs_current_node = Wbs::findOne(['id'=>$id_node]);
			if(!is_null($wbs_current_node)){
				$parents = $wbs_current_node->parents()->all();	
				foreach($parents as $prn){
					$this->params['breadcrumbs_wbs'][] = ['label' => $prn['name'], 
					'url' => Url::toRoute(['br/update', 
											'id' =>$idBR, 
											'page_number' => 3, 
											'root_id'=>$prn['id']])];
				}
				$this->params['breadcrumbs_wbs'][]=	['label' => $wbs_current_node->name];
			}
		//готовим массив для dropdownist c членами команды
		
		$ProjectCommand = VwProjectCommand::find()->where(['idBR'=>$idBR])->all();
		$items1 = ArrayHelper::map($ProjectCommand,'id','team_member');
		$params1 = [
		];
		
		$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path']);
		if (!is_null($settings)) $url_mantis = $settings->enm_str_value; //путь к мантиссе
		  else $url_mantis = '';
	
	$this->title = 'Перечень работ,  которые необходимо выполнить для достижения результата:  "' . $wbs_current_node->name . '"';

?>
<!-- <pre> <?= var_dump($ProjectCommand) ?></pre>  -->
<div class="works-of-estimate-index">

    <h1><?= Html::encode($this->title) ?></h1>
   

	   <div class="container">
		   <div class="row">
				<div class="col-sm">
				   <?= Breadcrumbs::widget([
				    'homeLink' => ['label' => 'WBS'],
				    'links' => isset($this->params['breadcrumbs_wbs']) ? $this->params['breadcrumbs_wbs'] : [],
				]) ?>
			    </div>
		   </div> 
		  	   	
		   <div class="row">
				<div class="col-sm-12">
					<?php  echo $this->render('_search', ['model' => $searchModel, 'idBR'=>$idBR, 'id_node'=>$id_node, 'idEstimateWorkPackages'=>$idEstimateWorkPackages,'related_issue'=>$related_issue]); ?>				   
			    </div>

		   </div> 
		   
		    <div class="row">
				<div class="col-sm-4">
				 	
				   <?php
				     $url1 = Url::to(['site/help']);
					 echo  Html::a('<span class="glyphicon glyphicon-question-sign"></span>', $url1.'#SystemDesc23',['title' => 'Помощь по разделу',]);
					 echo "   ";
					 $url2 = Url::to(['works_of_estimate/create', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node]);;
					 echo  Html::a('<span class="glyphicon glyphicon-plus-sign"></span>', $url2,['title' => 'Добавить работу',]);
					 echo "   ";
					 if($wbs_current_node->idResultType == 3){ //тольо для работ по созданию ПО
						 $url6 = Url::to(['works_of_estimate/put_inc_to_mantis', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node]);;
						 echo  Html::a('<span class="glyphicon glyphicon-knight"></span>', $url6,['title' => 'Cоздать инциденты на основе работ',]);
						 echo "   ";
						  $url7 = Url::to(['works_of_estimate/take_works_from_tz', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node]);;
						 echo  Html::a('<span class="glyphicon glyphicon-book"></span>', $url7,['title' => 'Cоздать работы на основе ТЗ(*.dbk)',]);
						 echo "   ";
					 }	 
					 $url3 = Url::to(['works_of_estimate/take_works_from_mantis', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node]);;
					 echo  Html::a('<span class="glyphicon glyphicon-bishop"></span>', $url3,['title' => 'Cоздать работы на основе инцидентов mantis',]);
					 echo "   ";
					 $url4 = Url::to(['works_of_estimate/move_works_to_another_result', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node]);;
					 echo  Html::a('<span class="glyphicon glyphicon-resize-horizontal"></span>', $url4,['title' => 'Перемещение работ в другой результат',]);
					 echo "   ";
					 $url5 = Url::to(['works_of_estimate/line_up_works', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node]);;
					 echo  Html::a('<span class="glyphicon glyphicon-retweet"></span>', $url5,['title' => 'Выстроить работы последовательно в графике',]);
					 
					 echo "   ";
					 //$url2 = Url::to(['works_of_estimate/works_template', 'idBR'=>$idBR]);;
					 //echo  Html::a('<span class="glyphicon glyphicon-save-file"></span>', $url2,['title' => 'Добавить работы по шаблону',]);
				   ?>

			    </div>
		   </div> 
		   <div class="row">
			   <div class="col-sm-12">
				    <?php $form1 = ActiveForm::begin([
			        'action' => ['index','idBR'=>$idBR, 'id_node'=>$id_node,'idEWP'=>$idEstimateWorkPackages],
			        'method' => 'post',
			        'id' =>'w222'
					]); 	
      // echo '<pre>'.var_dump($VwListOfWorkEffort).'</pre>';
     //die;
      if(count($VwListOfWorkEffort)>0){ // по элементу wbs есть работы
		echo('<table border = "1" cellpadding="4" cellspacing="2">');  
        $id = $VwListOfWorkEffort[0]['idWorksOfEstimate'];
        $i =0;
        $url2 = Url::to(['works_of_estimate/create_workeffort', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node, 'idWorksOfEstimate'=>$id ]);  //добавление трудозатрат в работу
        $url3 = Url::to(['works_of_estimate/update', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node, 'idWorksOfEstimate'=>$id]);             //изменить работу
        $url5 = Url::to(['works_of_estimate/deletework', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node, 'idWorksOfEstimate'=>$id]);             //Удалить работу
        echo('<tr><td colspan="4" ><b>'
		        //.Html::a('<span class="glyphicon glyphicon-plus"></span>', $url2,['title' => 'Добавить трудозатраты по работе',])
		        .Html::a('<span class="glyphicon glyphicon-minus-sign"></span>', $url5,['title' => 'Удалить работу',
																						'data' => [
																							'confirm' => 'Точно удаляем?',
																							'method' => 'post',	
																						],
																				 ])
				.Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url3,['title' => 'Изменить описание работы',])
				.Html::a($VwListOfWorkEffort[$i]['mantisNumber'], $url_mantis.$VwListOfWorkEffort[$i]['mantisNumber'],['target' => '_blank'])
				.' '
		        .$VwListOfWorkEffort[$i]['WorkName'].'</td></tr>');
        
		foreach($VwListOfWorkEffort as $vlwe){
			//print_r($vlwe);die;
			$url4 = Url::to(['works_of_estimate/delete_workeffort', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node, 'idWorksOfEstimate'=>$id, 'idLaborExpenditures'=>$vlwe['idLaborExpenditures']]);   //удаление трудозарат из работы			
			if($vlwe['idWorksOfEstimate'] == $id){
				if(isset($vlwe['workEffort'])){
					echo('<tr><td>'
						.$vlwe['team_member']
						.'</td><td>'
						.$vlwe['workEffort'].' ч.д.'
						.'</td><td>'
						.$vlwe['workEffortHour'].' ч.час.'
						.'</td></tr>');	
			    }
			}	else{
				 $id = $vlwe['idWorksOfEstimate'];	
				 $url2 = Url::to(['works_of_estimate/create_workeffort', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node, 'idWorksOfEstimate'=>$id ]);  //добавление трудозатрат в работу
				 $url3 = Url::to(['works_of_estimate/update', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node, 'idWorksOfEstimate'=>$id]);   //изменить работу
				 $url5 = Url::to(['works_of_estimate/deletework', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node, 'idWorksOfEstimate'=>$id]);             //Удалить работу
				 echo('<tr><td colspan="4"><b>'
				 //.Html::a('<span class="glyphicon glyphicon-plus"></span>', $url2,['title' => 'Добавить трудозатраты по работе',])
				 .Html::a('<span class="glyphicon glyphicon-minus-sign"></span>', $url5,['title' => 'Удалить работу',])
				 .Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url3,['title' => 'Изменить описание работы',])
				 .Html::a($vlwe['mantisNumber'], $url_mantis.$vlwe['mantisNumber'],['target' => '_blank'])
	 			 .' '
				 .$vlwe['WorkName'].'</td></tr>');
				 if(isset($vlwe['workEffort'])){
					 echo('<tr><td>'
					 .$vlwe['team_member']
					 .'</td><td>'
					 .$vlwe['workEffort'].' ч.д.'
					 .'</td><td>'
					 .$vlwe['workEffortHour'].' ч.час.'
					 .'</td></tr>');		
				 }	 
			}
			
			
			$i =$i+1;
	      } 
	      echo '  </table>';
	      echo '<br>';
		}
     ?>
			   </div>
		   </div> 
		   	
	   </div> 	
  
    <div class="works-of-estimate-search">
	
  
     <div class="form-group">
       <?php         
        // echo Html::submitButton('Сохранитьdddd', ['class' => 'btn btn-primary']) 
        ?>
       
    </div>
    <?php ActiveForm::end(); ?> 
</div>
