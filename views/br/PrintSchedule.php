<?php
// не используется

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\EstimateWorkPackages;
use app\models\BusinessRequests;


$EWP = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEWP]);
$EWP_BrInfo = $EWP->getBrInfo();
$dataBeg = $EWP_BrInfo['BRDateBegin'];
if(!empty($dataBeg)){
	
	$dBRBeg =  $dataBeg;
	}else{
		$dBRBeg =  date("Y-m-d");
		}
$dataEnd = $EWP->getScheduleLastData($idEWP);
if($dataEnd){
	 $dBREnd =  $dataEnd['WorkEnd'];
	} else{
		$dBREnd =   date("Y-m-d");
		}
		
		//echo $dBeg->format('Y-m-d').'<br>';
		//echo $dEnd->format('Y-m-d').'<br>';
/* @var $this yii\web\View */
/* @var $model app\models\EstimateWorkPackages */
/* @var $form ActiveForm */
?>
<div class="PrintEstimateWorkPackages">
    <h3><?= 'Диаграма Ганта для BR-'.Html::encode($EWP_BrInfo['BRNumber'].'  "'.$EWP_BrInfo['BRName'].'"') ?></h3>
	Оценка трудозатрат: <b> <?php echo $EWP_BrInfo['EstimateName'] ?></b>  от <b> <?php echo $EWP_BrInfo['dataEstimate'].' '.$EWP_BrInfo['finished']   ?>  </b>
    <table border = "1" cellpadding="4" cellspacing="2"> 
			   <tr><th>Работа</th><th>Дата начала</th><th>Дата окончания</th><th>Длительность</th><th>Задержка</th><th></th></tr>
			  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=4>&nbsp;</td></tr>
			  
    <?php 
    
    $form = ActiveForm::begin(); 
    if(count($SCHData)>0){
		$id = $SCHData[0]['id'];
		    echo '<tr><td><b>'.$SCHData[0]['name'].'</td><td></td><td></td><td></td><td></td>';	
		foreach($SCHData as $sch){
			
			if($sch['id']==$id){
				echo '<tr><td>'.$sch['WorkName'].'</td><td>'.$sch['WorkBegin'].'</td><td>'.$sch['WorkEnd'].'</td><td>'.$sch['duration'].'</td><td>'.$sch['lag'].'</td>'
				.$EWP->getDayRowTable($dBRBeg,$dBREnd,$sch['WorkBegin'],$sch['WorkEnd']);	
			}else{
				echo '<tr><td><b>'.$sch['name'].'</td><td></td><td></td><td></td><td></td>';	
				echo '<tr><td>'.$sch['WorkName'].'</td><td>'.$sch['WorkBegin'].'</td><td>'.$sch['WorkEnd'].'</td><td>'.$sch['duration'].'</td><td>'.$sch['lag'].'</td>'
				.$EWP->getDayRowTable($dBRBeg,$dBREnd,$sch['WorkBegin'],$sch['WorkEnd']);		
				$id = $sch['id'];
				}
			
		
		}
	}
    
    
       
    ?>
    </table>
	 	
      <div class="form-group">
           <?php
                  echo Html::submitButton('Обновить', ['class' => 'btn btn-primary',
                 		'title'=>'Обновить  страницу',
						'name'=>'btn',
						'value' => 'reload'
                   ]);
				   
              ?>
      </div>
    <?php ActiveForm::end(); ?>

</div><!-- UpdateEstimateWorkPackages -->
