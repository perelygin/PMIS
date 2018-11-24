<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\EstimateWorkPackages */
/* @var $form ActiveForm */
?>
<div class="PrintEstimateWorkPackages">
    <h3><?= 'Оценка трудозатрат по BR-'.Html::encode($BR->BRNumber.'  "'.$BR->BRName.'"') ?></h3>
    
    <h4>Пакет оценок: "<?= Html::encode($EWP->EstimateName).'" от '. Html::encode($EWP->dataEstimate)?></h4>
    <?php 
    
    $form = ActiveForm::begin(); 
       
    ?>
      <div class="form-group">
		  
            <?php
                  echo Html::submitButton('Обновить', ['class' => 'btn btn-primary',
                 		'title'=>'Обновить  страницу',
						'name'=>'btn',
						'value' => 'reload'
                   ]).'  '.
				   Html::submitButton('в Excel', ['class' => 'btn btn-primary',
						'title'=>'Выгрузить в Excel',
						'name'=>'btn',
						'value' => 'excel'
				   ]) ;
              ?>
      </div>
      <table border = "1" cellpadding="4" cellspacing="2">
	  <?php 
	    $strhead = '<tr><td></td><td></td>';
	    $strEnd ='';
	    $arraySum =array(); //для подсчета итогов
	    $arraySum1 =array(); //для подсчета итогов с приведеним к ролям из договора
	    
	    foreach($RoleHeader as $rh){
			$strhead =$strhead.'<td> &nbsp'.$rh['RoleName'].'&nbsp </td>';
			$strEnd = $strEnd.'<td></td>';
			$arraySum[$rh['RoleName']] = 0;
		}
	    foreach($RoleTarifHeader as $rth){
			$arraySum1[$rth['TariffName']] = 0;
		}
		
		
		echo($strhead.'</tr>');
	    if(count($print_WOEs)>0){
			$id = -1; //$print_WOEs[0]['id'];
			 foreach($print_WOEs as $pwoe){
				$print_wef = Yii::$app->db->createCommand($sql)->bindValue(':idwoe',$pwoe['idWorksOfEstimate'])->queryAll(); //выбрали трудозатраты по ролям  для работы
				$str = '<tr><td>&nbsp&nbsp&nbsp&nbsp</td><td>'.$pwoe['WorkName'].'</td>';
				foreach($print_wef as $pwef){
					$str = $str.'<td align="center">'.$pwef['sumWE'].'</td>'; //.' '.$pwef['RoleName']
					$arraySum[$pwef['RoleName']] = $arraySum[$pwef['RoleName']] + $pwef['sumWE']; //подсчет итогов
					$arraySum1[$pwef['TariffName']] = $arraySum1[$pwef['TariffName']] + $pwef['sumWE']; //подсчет итогов
				}
				$str = $str.'</tr>';
					
				if($pwoe['id'] == $id){
					echo($str); 
				} else{
					echo('<tr><td colspan =2><b>Результат: '.$pwoe['name'].'</b></td>'.$strEnd);
					echo($str); 
					$id = $pwoe['id'];
				}	
			}
		}
		
		//итоги
		$total =0;
		$strbottom0 = '<tr><td></td><td>Итого</td>';
		foreach($arraySum as $ars => $v){
		  $strbottom0 =$strbottom0.'<td align="center"> &nbsp'.$v.'&nbsp </td>';
		  $total =$total + $v;
		}
		$total10 = $total/10;
		echo $strbottom0;
		
		$strbottom1 = '<tr><td></td><td>Дополнительное тестирование (10% от общих трудозатрат</td>';
		foreach($arraySum as $ars => $v){
		  if($ars=='Инженер по тестированию' ){  //инженер по тестированию
			  $strbottom1 =$strbottom1.'<td align="center"> &nbsp'.$total10.'&nbsp </td>';
		 
		  } else {
			  $strbottom1 =$strbottom1.'<td> </td>';
		  }
			}
		echo $strbottom1;
		$strbottom2 = '<tr><td></td><td><b>Итого с учетом доп. затрат</b></td>';
		foreach($arraySum as $ars => $v){
		  if($ars=='Инженер по тестированию' ){  //инженер по тестированию
			  
			  $a = $v+$total10;
			  $strbottom2 =$strbottom2.'<td align="center"> <b>&nbsp'.$a.'&nbsp </b></td>';
		 
		  } else {
			  $strbottom2 =$strbottom2.'<td align="center"> <b>&nbsp'.$v.'&nbsp </b></td>';
		  }
		}
		echo $strbottom2;
	  ?>
		  
		 
	  </table>
	  
    <br>
    
    <div class="container">
	   <div class="row">
		  <div class="col-sm-3">
		    <table border = "1" cellpadding="4" cellspacing="2">
				<?php
					foreach($arraySum as $ars => $v){
						
					  if($ars=='Инженер по тестированию' ){  //инженер по тестированию
						  $a = $v+$total10;
						   	echo('<tr><td>'.$ars.'</td><td>'.$a.'</td></tr>');  
					  } else {
							echo('<tr><td>'.$ars.'</td><td>'.$v.'</td></tr>');
						
					  }
						
					}
				//}
				echo('<tr><td><b>Итого</b></td><td><b>'.($total+$total10).'</b></td></tr>');
				?>	
			</table>
		</div>
		 <div class="col-sm-3">
			  <table border = "1" cellpadding="4" cellspacing="2">
			  <?php
		 	    //Итоги с приведеним к ролям из договора
		 	          $total = 0 ;
		 	    	  foreach($arraySum1 as $ars => $v){
					  $total =$total + $v;	
					  if($ars=='Инженер по тестированию ПО' ){  //инженер по тестированию
						  $a = $v+$total10;
						   	echo('<tr><td>'.$ars.'</td><td>'.$a.'</td></tr>');  
					  } else {
							echo('<tr><td>'.$ars.'</td><td>'.$v.'</td></tr>');
						
					  }
						
					}
					$total10 = $total/10;
					echo('<tr><td><b>Итого</b></td><td><b>'.($total+$total10).'</b></td></tr>');
		 	  ?>
			  </table>
		 </div>
		</div>
	 	
	 	
	 	
	 	
      <div class="form-group">
           <?php
                  echo Html::submitButton('Обновить', ['class' => 'btn btn-primary',
                 		'title'=>'Обновить  страницу',
						'name'=>'btn',
						'value' => 'reload'
                   ]).'  '.
				   Html::submitButton('в Excel', ['class' => 'btn btn-primary',
						'title'=>'Выгрузить в Excel',
						'name'=>'btn',
						'value' => 'excel'
				   ]) ;
              ?>
      </div>
    <?php ActiveForm::end(); ?>

</div><!-- UpdateEstimateWorkPackages -->
