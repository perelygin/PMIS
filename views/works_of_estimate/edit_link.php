
<?php
 
?>

<?php


use app\models\LinkType;
use app\models\Wbs;
use app\models\EstimateWorkPackages;
use app\models\WorksOfEstimate;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Collapse;
use yii\bootstrap\Tabs;
use vova07\imperavi\Widget;

//Данные для выпадающего списка типа услуг
$LinkType = LinkType::find()->all();
$items1 = ArrayHelper::map($LinkType,'idLinkType','LinkTypeName');
$params1 = [
];

$wbs_current_node = Wbs::findOne(['id'=>$idWbs]);
$WBSInfo = $wbs_current_node->getWbsInfo();
$EWP_BrInfo = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEstimateWorkPackages])->getBrInfo();



$this->title = "BR-". $WBSInfo['BRNumber']." ".$WBSInfo['BRName'];

?>
<div class="works-of-estimate-update">
    <h3><?= Html::encode($this->title) ?></h3>
      <h4>Изменение параметров связи между работами</h4>
    Оценка трудозатрат: <b> "<?= Html::encode($EWP_BrInfo['EstimateName']).'"</b> от <b>'. $EWP_BrInfo['dataEstimate'].'  '.$EWP_BrInfo['finished']?></b>
    <br>
    <b> Работа предшественик: </b>
     <?php 
	$WOEInfo = WorksOfEstimate::GetWOEInfo($model->idFirstWork);				
       echo $WOEInfo['WorkName'].'<b> Результат </b>'.$WOEInfo['name']; 
     ?>
    <br>
    <b> Работа:    </b>
    <?php 
	$WOEInfo = WorksOfEstimate::GetWOEInfo($model->idSecondWork);				
       echo $WOEInfo['WorkName'].'<b> Результат </b>'.$WOEInfo['name']; 
     ?>
    
	
     
    
     <?php $form = ActiveForm::begin();?>
    
    
<div class="container">
	
   <div class="row">
		<div class="col-sm-4">
		    <?= $form->field($model, 'idLinkType')->dropDownList($items1,$params1) ?>
	    </div>
	    <div class="col-sm-4">
		    <?= $form->field($model,'lag')->textInput(['maxlength' => true]) ?>
	    </div>
   </div> 
   <div class="row">
		<div class="col-sm-6">
		 <?php echo  Html::submitButton('Сохранить', ['class' => 'btn btn-success',
											'name'=>'btn',
											'value' => 'save_']);
		?>
	    </div>
	    <div class="col-sm-6">
		  <?php echo  Html::submitButton('Отмена', ['class' => 'btn btn-success',
											'name'=>'btn',
											'value' => 'cancl_']);
		  ?>
	    </div>
   </div> 
  
       <div class="form-group">
        
    </div>
    
</div> 	
    
  
   


    <?php ActiveForm::end(); ?>
</div>



