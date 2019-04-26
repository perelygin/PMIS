<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\BusinessRequests;
use app\models\EstimateWorkPackages;
use app\models\vw_settings; 
use app\models\Weekends;

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

     
    
    <?php 
      $curentDate = \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));//текущая дата
      
      //calback функции для фильтров
       function TypeRes_0($var)
		{
		    if($var['TypeRes'] == 0){
				return true;
				} else{
					return false;
					}
		}
	   $ar_TypeRes_0 = array_filter($result, "TypeRes_0");  //фильтруем только бессрочные
		
	   function TypeRes_1($var)
		{
		    if($var['TypeRes'] == 1){
				return true;
				} else{
					return false;
					}
		}
		$ar_TypeRes_1 = array_filter($result, "TypeRes_1");  //фильтруем только просроченные
		
	   function TypeRes_2($var)
		{
		    if($var['TypeRes'] == 2){
				return true;
				} else{
					return false;
					}
		}
		$ar_TypeRes_2 = array_filter($result, "TypeRes_2");  // плановая дата больше чем через 7 дней
		
		function TypeRes_3($var)
		{
		    if($var['TypeRes'] == 3){
				return true;
				} else{
					return false;
					}
		}
		$ar_TypeRes_3 = array_filter($result, "TypeRes_3");  // плановая дата меньше чем через 7 дней
		
		//сортируем оставшиеся записи
		$volume  = array_column($ar_TypeRes_0, 'idBR');
		array_multisort($volume, SORT_DESC,$ar_TypeRes_0);
    ?>
     <h1><?php echo Html::encode($this->title).' на '.$curentDate->format('Y-m-d') ?></h1>
      <h3>Результаты в работе, срок окончания не определен</h3>
      <table border = "1" cellpadding="4" cellspacing="2">
		  <?php 
		    echo '<tr><th>Проект</th><th>Результат проекта</th><th>Ответственный</th><th>Плановая версия</th><th>Организация отв.</th></tr>';
		    foreach($ar_TypeRes_0 as $r){
				
			 echo '<tr>
			  <td> BR-'.$r['BRNumber'].' '.$r['BRName'].'</td>
			  <td>'.Html::a($r['name'], Url::toRoute(['br/update_wbs_node', 'id_node'=>$r['id'],'idBR'=>$r['idBR']]),['title' => '','target' => '_blank']). '</td>
			  <td>'. $r['fio'].'</td>
			  <td>'. $r['version_number'].'</td>
			  <td>'. $r['CustomerName'].'</td>
			  </tr>';	
			}
		  ?>
	  </table>
    <h3>Просроченные результаты в работе</h3>
     <table border = "1" cellpadding="4" cellspacing="2">
		  <?php 
		    echo '<tr><th>Проект</th><th>Результат проекта</th><th>Ответственный</th><th>Плановая версия</th><th>Организация отв.</th><th>Срок</th></tr>';
		    foreach($ar_TypeRes_1 as $r){
				
			 echo '<tr>
			  <td> BR-'.$r['BRNumber'].' '.$r['BRName'].'</td>
			  <td>'.Html::a($r['name'], Url::toRoute(['br/update_wbs_node', 'id_node'=>$r['id'],'idBR'=>$r['idBR']]),['title' => '','target' => '_blank']). '</td>
			  <td>'. $r['fio'].'</td>
			  <td>'. $r['version_number'].'</td>
			  <td>'. $r['CustomerName'].'</td>
			  <td>'. $r['DateEnd'].'</td>
			  </tr>';	
			}
		  ?>
	  </table>
    <h3>Результаты в работе, срок окончания - ближайшая неделя, начиная с 
    <?php 
    echo $curentDate->format('d-m-Y');
    ?>
    </h3>
     <table border = "1" cellpadding="4" cellspacing="2">
		  <?php 
		  
		    //Делаем строку с заголовками
		    $cDate = \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));//текущая дата
		    $d = new \DateInterval('P1D');
		    $a = 7;	// 7 дней
				$strTop = '';
				while($a>0){
					if(!Weekends::isWeekend($cDate)){
					  $strTop = $strTop.'<th width = "80">'.$cDate->format('D').'</th>';
					}else{
					  $strTop = $strTop.'<th bgcolor="#fb1c0d">'.$cDate->format('D').'</th>';
					}  
					   $cDate->add($d); 
					   $a=$a-1;
				  }
		    
		    echo '<tr><th>Проект</th><th>Результат проекта</th><th>Ответственный</th><th>Организация отв.</th>'.$strTop.'</tr>';
		    foreach($ar_TypeRes_3 as $r){
				//делаем строку с датами для результата
				$cDate = \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));//текущая дата
				$DateEnd = \DateTime::createFromFormat('Y-m-d', $r['DateEnd']);//дата окончания работы
				
				$a = 7;	// 7 дней
				$str = '';
				while($a>0){
					  
					  //if(!Weekends::isWeekend($dateA)){
					  if($cDate == $DateEnd){
						  $str = $str.'<td>'.$r['DateEndList'].'</td>';
					  }	else{
						  $str = $str.'<td>&nbsp&nbsp&nbsp</td>';
						  }					  
					  $cDate->add($d); 
					  $a=$a-1;
				  }
			 echo '<tr>
			  <td> BR-'.$r['BRNumber'].' '.$r['BRName'].'</td>
			  <td>'.Html::a($r['name'], Url::toRoute(['br/update_wbs_node', 'id_node'=>$r['id'],'idBR'=>$r['idBR']]),['title' => '','target' => '_blank']). '</td>
			  <td>'. $r['fio'].'</td>
			  <td>'. $r['CustomerName'].'</td>'
			  .$str.
			  '</tr>';	
			}
		  ?>
	 </table>
    <h3>Результаты в работе, срок окончания - больше недели</h3>
     <table border = "1" cellpadding="4" cellspacing="2">
		  <?php 
		    echo '<tr><th>Проект</th><th>Результат проекта</th><th>Ответственный</th><th>Плановая версия</th><th>Организация отв.</th><th>Срок</th></tr>';
		    foreach($ar_TypeRes_2 as $r){
				
			 echo '<tr>
			  <td> BR-'.$r['BRNumber'].' '.$r['BRName'].'</td>
			  <td>'.Html::a($r['name'], Url::toRoute(['br/update_wbs_node', 'id_node'=>$r['id'],'idBR'=>$r['idBR']]),['title' => '','target' => '_blank']). '</td>
			  <td>'. $r['fio'].'</td>
			  <td>'. $r['version_number'].'</td>
			  <td>'. $r['CustomerName'].'</td>
			  <td>'. $r['DateEnd'].'</td>
			  </tr>';	
			}
		  ?>
	  </table>
</div>
