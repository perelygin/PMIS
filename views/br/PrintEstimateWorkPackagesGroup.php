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
	    
	    foreach($RoleHeader as $rh){
			$strhead =$strhead.'<td> &nbsp'.$rh['RoleName'].'&nbsp </td>';
			$strEnd = $strEnd.'<td></td>';
			$arraySum[$rh['RoleName']] = 0;
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
	  ?>
		  
		 
	  </table>
	  
    <br>
    <table border = "1" cellpadding="4" cellspacing="2">
		<?php
		 //print_r($arraySum);die;
		$total =0;
		//if(count($Print_wbs_sum)>0){
			foreach($arraySum as $ars => $v){
				
			  if($ars=='Инженер по тестированию' ){  //инженер по тестированию
				    $test = $v;
				    $test10 = $test*0.1;
				    $test10p=$test+$test10;
					echo('<tr><td>'.$v.'</td><td>'.$v.' + 10%('.$test10.')='.$test10p.'</td></tr>');  
					$total =$total + $test10p;
				} else {
					echo('<tr><td>'.$ars.'</td><td>'.$v.'</td></tr>');
					$total =$total +$v;
				}
				
			}
		//}
		echo('<tr><td><b>Итого</b></td><td><b>'.$total.'</b></td></tr>');
		?>	
	</table>
	 	
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
