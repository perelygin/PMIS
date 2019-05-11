<?php
// не используется

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\EstimateWorkPackages;
use app\models\BusinessRequests;
use app\models\WorksOfEstimate;



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
	
	 //число дней между датами 
	  $dBeg = \DateTime::createFromFormat('Y-m-d', $dBRBeg);
	  $dEnd = \DateTime::createFromFormat('Y-m-d', $dBREnd);
	  $dif = $dBeg->diff($dEnd);	
	  $px_numb = 1000 + 18*$dif->days;
	  if($px_numb<1920){$px_numb =1920;}
	 //cho $dif->days; die;
?>
		<style type="text/css">
  		   .container {
			    width: <?php echo   $px_numb.'px;'?>
			    overflow: scroll;
			}
			.xuybex {
				font-size: 6pt;
			}
		</style>

    <h3><?= 'Диаграма Ганта для BR-'.Html::encode($EWP_BrInfo['BRNumber'].'  "'.$EWP_BrInfo['BRName'].'"') ?></h3>
	Оценка трудозатрат: <b> <?php echo $EWP_BrInfo['EstimateName'] ?></b>  от <b> <?php echo $EWP_BrInfo['dataEstimate'].' '.$EWP_BrInfo['finished']   ?>  </b>
<div class="container">
    <table border = "1" cellpadding="4" cellspacing="2" > 
	<?php 	
		echo  '<tr><th width= 600>Работа</th><th width = 80>Дата начала</th><th width = 80>Дата окончания</th><th width = 30>Длит.</th><th>Зд.</th>'
		      .$EWP->getDayRowTable($dBRBeg,$dBREnd,$dBRBeg,$dBREnd,3).'</tr>'
			  .'<tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=4>&nbsp;</td></tr>';
			  
			  	    
    
    $form = ActiveForm::begin(); 
    if(count($SCHData)>0){
		$id = $SCHData[0]['id'];
		    echo '<tr><td><b>'.$SCHData[0]['name'].'</td><td><b>'.$SCHData[0]['WbsBegin'].'</b></td><td><b>'.$SCHData[0]['WBSEnd'].'</b></td><td></td><td></td>'
		    .$EWP->getDayRowTable($dBRBeg,$dBREnd,$SCHData[0]['WorkBegin'],$SCHData[0]['WorkEnd'],2);
		foreach($SCHData as $sch){
			$woe = WorksOfEstimate::findOne(['idWorksOfEstimate'=>$sch['idWorksOfEstimate']]);
			
			
				$url = Url::to(['works_of_estimate/update', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEWP, 'idWbs'=>$sch['id'],
									'idWorksOfEstimate'=>$sch['idWorksOfEstimate'],'page_number'=>3]);             //изменить работу
				if($sch['id']==$id){
				  if($woe){
					echo '<tr><td>'.Html::a("<i>".$sch['idWorksOfEstimate']."</i> ".$sch['WorkName'], $url,['title' => 'Изменить описание работы'
																											,'target' => '_blank']).
					'</td><td>'.$sch['WorkBegin'].'</td><td>'.$sch['WorkEnd'].'</td><td>'.$sch['duration'].'</td><td>'.$sch['lag'].'</td>'
					.$EWP->getDayRowTable($dBRBeg,$dBREnd,$sch['WorkBegin'],$sch['WorkEnd'],1,$woe->getPNWOEList(1),$sch['idWorksOfEstimate'].' '.$sch['WorkName'],$woe->getPNWOEList(2));	
				  }
				}else{
					echo '<tr><td><b>'.$sch['name'].'</td><td><b>'.$sch['WbsBegin'].'</b></td><td><b>'.$sch['WBSEnd'].'</b></td><td></td><td></td>'
					.$EWP->getDayRowTable($dBRBeg,$dBREnd,$sch['WorkBegin'],$sch['WorkEnd'],2);
					if($woe){
						 echo '<tr><td>'.Html::a("<i>".$sch['idWorksOfEstimate']."</i> ".$sch['WorkName'], $url,['title' => 'Изменить описание работы'
																											   ,'target' => '_blank'])	
						.'</td><td>'.$sch['WorkBegin'].'</td><td>'.$sch['WorkEnd'].'</td><td>'.$sch['duration'].'</td><td>'.$sch['lag'].'</td>'
						.$EWP->getDayRowTable($dBRBeg,$dBREnd,$sch['WorkBegin'],$sch['WorkEnd'],1,$woe->getPNWOEList(1),$sch['idWorksOfEstimate'].' '.$sch['WorkName'],$woe->getPNWOEList(2));		
					}
						$id = $sch['id'];
				}
			
			
			
		
		}
	}
    
    
       
    ?>
    </table>
</div>	 	
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


