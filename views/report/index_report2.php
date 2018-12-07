<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\BusinessRequests;
use app\models\EstimateWorkPackages;
use app\models\vw_settings; 

/* @var $this yii\web\View */
/* @var $searchModel app\models\VwReport1Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path']);
		if (!is_null($settings)) $url_mantis = $settings->enm_str_value; //путь к мантиссе
		  else $url_mantis = '';


$this->title = 'Сетевой график';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vw-report1-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search_report1', ['model' => $searchModel]); ?>


 <table border = "1" cellpadding="4" cellspacing="2">
   <?php
     $st = '<tr>';
     for($i = 1; $i <= 100; $i++){
		 if ($i>50 and $i<60)
			$st	 = $st.'<td bgcolor="#ffcc00" >_</td>'; 
		 else
			$st	 = $st.'<td bgcolor="#FFFFFF" >_</td>'; 
		 }
	 $st	 = $st.'</tr>';
	 echo $st;
   ?>
 </table>  
    
      <table border = "1" cellpadding="4" cellspacing="2">
		  <?php 
		    echo '<tr><th>Проект</th><th>Результат проекта</th><th>Статус результата</th><th>Ответственный</th><th>Плановая версия</th><th>Организация отв.</th></tr>';
		    foreach($dataProvider as $dp_str){
			 echo '<tr>
			  <td> BR-'.$dp_str->BRNumber.' '.$dp_str->BRName.'</td>
			  <td>'.Html::a($dp_str->name, Url::toRoute(['br/update_wbs_node', 'id_node'=>$dp_str->id,'idBR'=>$dp_str->idBr]),['title' => '','target' => '_blank']). '</td>
			  <td>'. $dp_str->ResultStatusName. '</td>
			  <td>'. $dp_str->fio.'</td>
			  <td>'. $dp_str->version_number.'</td>
			  <td>'. $dp_str->CustomerName.'</td>
			  </tr>';	
			  $idEstPckg = BusinessRequests::findOne(['idBR'=>$dp_str->idBr])->getLastEstimateId(); 
			  if(!is_null($idEstPckg)){  //если есть пакет оценок по BR
				  $WorksList = EstimateWorkPackages::findOne($idEstPckg)->getWorksList($dp_str->id);
				  if(count($WorksList)>0){
					foreach($WorksList as $wl){
					  echo '<tr>
					  <td></td>
					  <td colspan="4">'.Html::a($wl['mantisNumber'], $url_mantis.$wl['mantisNumber'],['target' => '_blank']).' '.$wl['WorkName']. '</td><td></td>
				
					  </tr>';	
				    }
				  }
				  
				  
			  } 
			 
			}
		  ?>
	  </table>
    
</div>