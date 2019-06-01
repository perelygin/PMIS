<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\BusinessRequests;
use app\models\EstimateWorkPackages;
use app\models\vw_settings; 
use app\models\WbsSchedule;
/* @var $this yii\web\View */
/* @var $searchModel app\models\VwReport1Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path']);
		if (!is_null($settings)) $url_mantis = $settings->enm_str_value; //путь к мантиссе
		  else $url_mantis = '';

$CurrentDate = \DateTime::createFromFormat('Y-m-d',  date("Y-m-d"));	 //текущая дата
$this->title = 'Сводный отчет по результатам.';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vw-report1-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php 
    echo ' Сегодня: '.$CurrentDate->format('d-m-Y');
    echo $this->render('_search_report1', ['model' => $searchModel]); ?>
<!--
	 <style type="text/css">
	   TABLE {
	    background: white; /* Цвет фона таблицы */
	    color: white; /* Цвет текста */
	   }
	   TD, TH {
	    background: maroon; /* Цвет фона ячеек */
	    padding: 5px; /* Поля вокруг текста */
	   }
	  </style>
-->
    
      <table border = "1" cellpadding="4" cellspacing="2">
		  <?php 
		    echo '<tr><th>Проект</th><th>Результат проекта</th>
					  <th>Статус результата</th>
					  <th>Ответственный</th>
					  <th>План. верс.</th>
					  <th>Организация отв.</th>
					  <th>Плановая дата реакции</th>
					  <th>План. дата результата</th>
					  </tr>';
		    if(!empty($dataProvider)){
				$i = $dataProvider[0]['idBr'];
				$BR = BusinessRequests::findOne($i);	 
				if(!is_null($BR)){   //  получаем id актуальной оценки для BR  покаждому результату.
						$LastEstimateId = $BR->getLastEstimateId();
				}	
			}else {
				$i=0;
				}
		    foreach($dataProvider as $dp_str){
				$dend_str = ""; // строка для даты по результату
				$reprd_str =""; //строка для даты по событию
				$color ='';
				$color1 ='';
				 if($i <> $dp_str->idBr){
					  echo '<tr bgcolor="#b1b84f" ><td colspan="8">&nbsp</td></tr>';
				      //получаем дату результата по последней оценке трудозатрат	 
					  $BR = BusinessRequests::findOne($dp_str->idBr);	 
					  if(!is_null($BR)){   //  получаем id актуальной оценки для BR  покаждому результату.
							$LastEstimateId = $BR->getLastEstimateId();
					  }	
					  $i = $dp_str->idBr ;
				 } 
			 if(!is_null($LastEstimateId)){
				 $dend =  WbsSchedule::getWbsEndDate($LastEstimateId,$dp_str->id);
				 if($dend){
					 $dend_str = $dend->format('d-m-Y');
					 }
				 
			 }
			 	$reprd = \DateTime::createFromFormat('Y-m-d', $dp_str->ResultEventsPlannedResponseDate);		 // дата окончания сохраняемой работы
			 	 if($reprd){
					 $reprd_str = $reprd->format('d-m-Y');
					 }
			 //цветовая диференциация штанов -)(дат)		 
			  
			  if($reprd<$CurrentDate){  //проверка  на просроченую реакцию
				  $color1 = 'style="color:red"';
				  } else{
					  $diff1 = $CurrentDate->diff($reprd);
					  if($diff1->days < 3){
						   $color1 = 'style="color:blue;  background:yellow"';
						  }else{
							  $color1 = '';
							  }
					  }
			  if($dend<$CurrentDate){  //проверка на просроченный результат
				  $color = 'style="color:red"';
				  } else{
					 $diff2 = $CurrentDate->diff($dend);
					  if($diff2->days < 3){
						   $color = 'style="color:blue ;  background:yellow"';
						  }else{
							  $color = '';
							  }
					  }		  
			 echo '<tr>
			  <td> BR-'.$dp_str->BRNumber.' '.$dp_str->BRName.'</td>
			  <td>'.Html::a($dp_str->name, Url::toRoute(['br/update_wbs_node', 'id_node'=>$dp_str->id,'idBR'=>$dp_str->idBr]),['title' => '','target' => '_blank']). '</td>
			  <td>'. $dp_str->ResultStatusName. '</td>
			  <td>'. $dp_str->fio.'</td>
			  <td>'. $dp_str->version_number.'</td>
			  <td>'. $dp_str->CustomerName.'</td>
			  <td '.$color1.'>'. $reprd_str.'</td>
			  <td '.$color.'>'. $dend_str.'</td>
			  </tr>';	
			  $idEstPckg = BusinessRequests::findOne(['idBR'=>$dp_str->idBr])->getLastEstimateId(); 
			  if(!is_null($idEstPckg)){  //если есть пакет оценок по BR
				  $a = EstimateWorkPackages::findOne($idEstPckg);
				  if(is_null($a)) {echo($idEstPckg." ".$dp_str->idBr);  die;}
				  $WorksList = $a->getWorksList($dp_str->id);
				  
				  if(count($WorksList)>0){
					foreach($WorksList as $wl){
					  echo '<tr>
					  <td></td>
					  <td colspan="7">'.Html::a($wl['mantisNumber'], $url_mantis.$wl['mantisNumber'],['target' => '_blank']).' '.$wl['WorkName']. '</td>
				
					  </tr>';	
				    }
				   
				  }
				  
				   
				  
			  } 
			  
			}
			
		  ?>
	  </table>
    
</div>
