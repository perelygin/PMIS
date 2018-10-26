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
			if(count($Print_wbs)>0){
				$id = $Print_wbs[0]['id'];
				//$idWOf=$Print_wbs[0]['idWorksOfEstimate'];
				$idWOf=(is_null($Print_wbs[0]['idWorksOfEstimate'])) ? 0 : $Print_wbs[0]['idWorksOfEstimate'];
				echo '<tr><td colspan =4> <b>Результат: '.$Print_wbs[0]['name'].'</b></td></tr>';
				echo '<tr><td>  &nbsp&nbsp</td><td colspan =3> <i> Работа: '.$Print_wbs[0]['WorkName'].'</i></td></tr>';
				foreach($Print_wbs as $pwbs){
					if($pwbs['id'] == $id  and $pwbs['idWorksOfEstimate']==$idWOf){
						echo '<tr><td>  &nbsp&nbsp</td><td>&nbsp&nbsp&nbsp&nbsp</td><td>'.$pwbs['fio'].'</td><td>'.$pwbs['workEffort'].'</td></tr>';
					} elseif($pwbs['id'] == $id  and $pwbs['idWorksOfEstimate']<>$idWOf){
						//$idWOf=$pwbs['idWorksOfEstimate'];
						$idWOf=(is_null($pwbs['idWorksOfEstimate'])) ? 0 : $pwbs['idWorksOfEstimate'];
						echo '<tr><td>  &nbsp&nbsp</td><td colspan =3> <i> Работа: '.$pwbs['WorkName'].'</i></td></tr>';
						echo '<tr><td width = "5%" >  &nbsp&nbsp</td><td></td><td>'.$pwbs['fio'].'</td><td width = "5%">'.$pwbs['workEffort'].'</td></tr>';
					} elseif($pwbs['id'] <> $id  and $pwbs['idWorksOfEstimate']==$idWOf){
						$id = $pwbs['id']; 
						$idWOf=(is_null($pwbs['idWorksOfEstimate'])) ? 0 : $pwbs['idWorksOfEstimate'];
						echo '<tr><td colspan =4> <b>Результат: '.$pwbs['name'].'</b></td></tr>';
						echo '<tr><td>  &nbsp&nbsp</td><td colspan =3><i>Работа: '.$pwbs['WorkName'].'</i> </td></tr>';
						echo '<tr><td width = "5%">  &nbsp&nbsp</td><td>&nbsp&nbsp&nbsp&nbsp</td><td>'.$pwbs['fio'].'</td><td>'.$pwbs['workEffort'].'</td></tr>';
						
					} elseif($pwbs['id'] <> $id  and $pwbs['idWorksOfEstimate']<>$idWOf){
						$id = $pwbs['id'];
						//$idWOf=$pwbs['idWorksOfEstimate'];
						$idWOf=(is_null($pwbs['idWorksOfEstimate'])) ? 0 : $pwbs['idWorksOfEstimate'];
						echo '<tr><td colspan =4> <b>Результат: '.$pwbs['name'].'</b></td></tr>';
						echo '<tr><td>  &nbsp&nbsp</td><td colspan =3><i>Работа: '.$pwbs['WorkName'].'</i> </td></tr>';
						echo '<tr><td width = "5%">  &nbsp&nbsp</td><td>&nbsp&nbsp&nbsp&nbsp</td><td>'.$pwbs['fio'].'</td><td>'.$pwbs['workEffort'].'</td></tr>';
					}
				}
			}
		  ?>
		  
		 
	  </table>
    <br>
    <table border = "1" cellpadding="4" cellspacing="2">
		<?php
		$total =0;
		if(count($Print_wbs_sum)>0){
			foreach($Print_wbs_sum as $ars){
			   $total =$total + $ars['summ'];
			}
			$total10 = $total/10;
			foreach($Print_wbs_sum as $pwbss){
			  if($pwbss['idRole'] ==6 ){  //инженер по тестированию
				    $a = $pwbss['summ']+$total10;
					echo('<tr><td>'.$pwbss['RoleName'].'</td><td>'.$pwbss['summ'].' + 10%('.$total10.')='.$a.'</td></tr>');  
					
				} else {
					echo('<tr><td>'.$pwbss['RoleName'].'</td><td>'.$pwbss['summ'].'</td></tr>');
					
				}
				
			}
		}
		$a = $total10 + $total;
		echo('<tr><td><b>Итого</b></td><td><b>'.$a.'</b></td></tr>');
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
