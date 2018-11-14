
<?php
 ////скрипт для обновления страницы при добавлении оценки трудозатрат
		//$script = <<< JS
		//var b = document.getElementById("btn_pls1");
			//b.onclick = function() {
				////alert("Спасибо, что выбрали!"); 
				//document.getElementById("ddd1").submit();
				//return false;
			//};
			
			////b.addEventListener("click", function() {alert('Еще раз спасибо!')}, false); 
//JS;
////маркер конца строки, обязательно сразу, без пробелов и табуляции
		
		//$this->registerJs($script, yii\web\View::POS_READY);
?>

<?php


use app\models\EstimateWorkPackages;
use app\models\Wbs;
use app\models\VwProjectCommand;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
//use app\models\Wbs;



/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */

$this->title = ' ';
$EWP_BrInfo = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$model->idEstimateWorkPackages])->getBrInfo();
$WBSInfo = Wbs::findOne(['id'=>$model->idWbs])->getWbsInfo();

$ProjectCommand = VwProjectCommand::find()->where(['idBR'=>$idBR])->all();
$items1 = ArrayHelper::map($ProjectCommand,'id','team_member');
$params1 = [

];
?>
<div class="works-of-estimate-update">

    <h3>Работа, необходимая для достижения результата: "<?= $WBSInfo['name'].'"'  ?></h3>
    
    <h4>Пакет оценок: "<?= Html::encode($EWP_BrInfo['EstimateName']).'" от '. $EWP_BrInfo['dataEstimate']?></h4>
	
    <?php ///$form = ActiveForm::begin(); ?>
    
    
     <?php $form = ActiveForm::begin([
        'action' => ['update','idWorksOfEstimate'=>$model->idWorksOfEstimate,'idBR'=>$idBR,'idWbs'=>$model->idWbs,'idEstimateWorkPackages'=>$model->idEstimateWorkPackages],  
        'method' => 'post',
        'id' =>"frm1"]); 
     ?>
    
    
<div class="container">
   <div class="row">
		<div class="col-sm-9">
		    <?= $form->field($model, 'WorkName')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-sm-3">
		    <?= $form->field($model, 'mantisNumber') ?>
	    </div>
   </div> 
   
   <div class="row">
	   <div class="col-sm-6">
			<?php echo $form->field($model, 'WorkDescription')->widget(Widget::className(), [
					    'settings' => [
					        'lang' => 'ru',
					        'minHeight' => 150,
					        'plugins' => [
								'fullscreen',
					        ],
					    ],
					]);
			?>
	    </div>
		<div class="col-sm-6">
		<?php
			 //$url2 = Url::to(['works_of_estimate/create_workeffort',
			//'idBR'=>$idBR,
			//'idEstimateWorkPackages'=>$model->idEstimateWorkPackages,
			//'idWbs'=>$model->idWbs, 
			//'idWorksOfEstimate'=>$model->idWorksOfEstimate ]);  //добавление трудозатрат в работу
			echo '<p><b>Трудозатраты</b>   '
				//.Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
				//$url2,['title' => 'Добавить трудозатраты по работе',
						//'id'=>'btn_pls',
						//'onclick'=>'document.getElementById("frm1").submit();return true;']).'</p>';		
			 .Html::submitButton('', [
						'span class' => 'glyphicon glyphicon-plus-sign',
						'title'=>'Добавить трудозатраты по работе',
						'name'=>'btn',
						'value' => 'add_']).'</p>'; 
		?>
		   
		   <table border = "1" cellpadding="4" cellspacing="2"> 
			   <tr><th></th><th>Исполнтель</th><th></th></tr>
			  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=3>&nbsp;</td></tr>
			   <?php
			   	
						
			    if(count($VwListOfWorkEffort)>0){ // по работе  есть оценки трудозатрат
					foreach($VwListOfWorkEffort as $vlwe){
						//$url4 = Url::to(['works_of_estimate/delete_workeffort',
						 //'idBR'=>$idBR,
						 //'idEstimateWorkPackages'=>$model->idEstimateWorkPackages ,
						 //'idWbs'=>$model->idWbs,
						 //'idWorksOfEstimate'=>$model->idWorksOfEstimate, 
						 //'idLaborExpenditures'=>$vlwe['idLaborExpenditures']]);   //удаление трудозарат из работы	
						 		
						if(isset($vlwe['workEffort'])){
							echo('<tr><td>  '
							//.Html::a('<span class="glyphicon glyphicon-minus"></span>', $url4,['title' => 'Удалить трудозатраты по работе',])
							.Html::submitButton('', [
								'span class' => 'glyphicon glyphicon-plus-minus-sign',
								'title'=>'Удалить трудозатраты по работе',
								'name'=>'btn',
								'value' => 'del_'.$vlwe['idLaborExpenditures']])
								.'</td><td>'
								.$form->field($vlwe, 'idTeamMember',['inputOptions' => ['name'=>'team_member['.$vlwe['idLaborExpenditures'].']']])->dropDownList($items1,$params1)
								.'</td><td>'.$form->field($vlwe, 'workEffort',
								['inputOptions' => ['name'=>'workEffort['.$vlwe['idLaborExpenditures'].']']]).
								'</td></tr>');	
					    }
					}	
				}	
			   ?>
		   </table>
		   
	    </div>
   </div> 
</div> 	
    
  
   
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success',
											'name'=>'btn',
											'value' => 'save_']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>



