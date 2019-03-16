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


$this->title = 'Vw Report1s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vw-report1-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search_report1', ['model' => $searchModel]); ?>
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
		    echo '<tr><th>Проект</th><th>Результат проекта</th><th>Статус результата</th><th>Ответственный</th><th>Плановая версия</th><th>Организация отв.</th></tr>';
		    if(!empty($dataProvider)){
				$i = $dataProvider[0]['idBr'];
			}else {
				$i=0;
				}
		    foreach($dataProvider as $dp_str){
				 if($i <> $dp_str->idBr){
					   echo '<tr bgcolor="#b1b84f" ><td colspan="6">&nbsp</td></tr>';
					  $i = $dp_str->idBr ;
				} 
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
				  $a = EstimateWorkPackages::findOne($idEstPckg);
				  if(is_null($a)) {echo($idEstPckg." ".$dp_str->idBr);  die;}
				  $WorksList = $a->getWorksList($dp_str->id);
				  
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
