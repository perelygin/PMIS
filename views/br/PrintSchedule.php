<?php
// не используется

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\EstimateWorkPackages;
use app\models\BusinessRequests;

$EWP_BrInfo = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEWP])->getBrInfo();
/* @var $this yii\web\View */
/* @var $model app\models\EstimateWorkPackages */
/* @var $form ActiveForm */
?>
<div class="PrintEstimateWorkPackages">
    <h3><?= 'Диаграма Ганта для BR-'.Html::encode($EWP_BrInfo['BRNumber'].'  "'.$EWP_BrInfo['BRName'].'"') ?></h3>
	Оценка трудозатрат: <b> <?php echo $EWP_BrInfo['EstimateName'] ?></b>  от <b> <?php echo $EWP_BrInfo['dataEstimate'].' '.$EWP_BrInfo['finished']   ?>  </b>
    
    <?php 
    
    $form = ActiveForm::begin(); 
       
    ?>
    
	 	
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
