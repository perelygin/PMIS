<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Wbs;
use app\models\VwProjectCommand;
use vova07\imperavi\Widget;
//use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;


/* @var $this yii\web\View */
/* @var $model app\models\ResultEvents */
/* @var $form ActiveForm */

$WBSInfo = Wbs::findOne(['id'=>$model->idwbs])->getWbsInfo();

//готовим массив для dropdownist c членами команды
$ProjectCommand = VwProjectCommand::find()->where(['idBR'=>$WBSInfo['idBr']])->all();
$items1 = ArrayHelper::map($ProjectCommand,'id','team_member');
$params1 = [

];
//phpinfo();
//die;
?>

    <h3>Событие по результату "<?= $WBSInfo['name'].'"'  ?></h3>
    
   
    
<div class="Edit_result_event">
       
    <?php $form = ActiveForm::begin(); ?>
    
    
    <div class="container">
		<div class="row">
			<div class="col-sm">
				
				<?php
				   $url4=' ';
					echo Html::submitButton('', [
										'span class' => 'glyphicon glyphicon-knight',
										'title'=>'Создать комментарий  в mantis',
										'name'=>'btn',
										'value' => 'crtm_'])
					.'    '
					.Html::a($model->ResultEventsMantis.'Перейти к комментарию в Mantis', $url4,['title' => '',])
				?>
			</div>
		</div>
		
		
		
		
		
	  <div class="row">
        <div class="col-sm-4">
			<?= $form->field($model, 'ResultEventsDate')->widget(DateTimePicker::className(),[
			    'pluginOptions' => [
			        'autoclose'=>true,
			        'format' => 'yyyy-mm-dd hh:ii:ss',
			         'todayHighlight' => true
			    ]
			]) ?>
		</div>
		<div class="col-sm-4">
			<?= $form->field($model, 'ResultEventsName') ?>
		</div> 
		<div class="col-sm-4">
			<?= $form->field($model, 'ResultEventResponsible')->dropDownList($items1,$params1) ?>  
		</div> 
	  </div>	 
	   <div class="row">
		  <div class="col-sm-6">
			  <?php 
				echo $form->field($model, 'ResultEventsDescription')->widget(Widget::className(), [
				    'settings' => [
				        'lang' => 'ru',
				        'minHeight' => 200,
				        'plugins' => [
			            'fullscreen',
				        ],
				    ],
				]);
	        ?>
		  </div>	  
	  </div>
	</div>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- Edit_result_event -->
