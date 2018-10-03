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
            <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
      </div>
      <table border = "1" cellpadding="4" cellspacing="2">
		  <?php 
			if(count($Print_wbs)>0){
				$id = $Print_wbs[0]['id'];
				$idWOf=$Print_wbs[0]['idWorksOfEstimate'];
				echo '<tr><td colspan =4> <b>Результат: '.$Print_wbs[0]['name'].'</b></td></tr>';
				echo '<tr><td>  &nbsp&nbsp</td><td colspan =3> <i> Работа: '.$Print_wbs[0]['WorkName'].'</i></td></tr>';
				foreach($Print_wbs as $pwbs){
					if($pwbs['id'] == $id  and $pwbs['idWorksOfEstimate']==$idWOf){
						echo '<tr><td>  &nbsp&nbsp</td><td>&nbsp&nbsp&nbsp&nbsp</td><td>'.$pwbs['fio'].'</td><td>'.$pwbs['workEffort'].'</td></tr>';
					} elseif($pwbs['id'] == $id  and $pwbs['idWorksOfEstimate']<>$idWOf){
						$idWOf=$pwbs['idWorksOfEstimate'];
						echo '<tr><td>  &nbsp&nbsp</td><td colspan =3> <i> Работа: '.$pwbs['WorkName'].'</i></td></tr>';
						echo '<tr><td width = "5%" >  &nbsp&nbsp</td><td></td><td>'.$pwbs['fio'].'</td><td width = "5%">'.$pwbs['workEffort'].'</td></tr>';
					} elseif($pwbs['id'] <> $id  and $pwbs['idWorksOfEstimate']==$idWOf){
						$id = $pwbs['id']; 
						echo ('такого быть не может'); die;
					} elseif($pwbs['id'] <> $id  and $pwbs['idWorksOfEstimate']<>$idWOf){
						$id = $pwbs['id'];
						$idWOf=$pwbs['idWorksOfEstimate'];
						echo '<tr><td colspan =4> <b>Результат: '.$pwbs['name'].'</b></td></tr>';
						echo '<tr><td>  &nbsp&nbsp</td><td colspan =3><i>Работа: '.$pwbs['WorkName'].'</i> </td></tr>';
						echo '<tr><td width = "5%">  &nbsp&nbsp</td><td>&nbsp&nbsp&nbsp&nbsp</td><td>'.$pwbs['fio'].'</td><td>'.$pwbs['workEffort'].'</td></tr>';
					}
				}
			}
		  ?>
		  
		 
	  </table>
  
      <div class="form-group">
            <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
      </div>
    <?php ActiveForm::end(); ?>

</div><!-- UpdateEstimateWorkPackages -->
