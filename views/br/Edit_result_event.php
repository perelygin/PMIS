<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Wbs;
use app\models\VwProjectCommand;
use app\models\BusinessRequests;
use app\models\vw_settings; 
use vova07\imperavi\Widget;
//use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;


/* @var $this yii\web\View */
/* @var $model app\models\ResultEvents */
/* @var $form ActiveForm */

$WBSInfo = Wbs::findOne(['id'=>$model->idwbs])->getWbsInfo();
$BR = BusinessRequests::findOne(['idBR'=>$WBSInfo['idBr']]);
$mantis_links = $BR->getMantisNumbers(3,$model->idwbs); //перечень инцидентов по результату
 
//готовим массив для dropdownist c членами команды
$ProjectCommand = VwProjectCommand::find()->where(['idBR'=>$WBSInfo['idBr']])->all();
$items1 = ArrayHelper::map($ProjectCommand,'id','team_member');
$params1 = [

];

$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path']);
	if (!is_null($settings)) $url_mantis = $settings->enm_str_value; //путь к мантиссе
	  else $url_mantis = '';
//phpinfo();
//die;
		$this->title = "BR-". $WBSInfo['BRNumber']." ".$WBSInfo['BRName'];
?>
    <h3><?= Html::encode($this->title) ?></h3>
    <h4>Событие по результату "<?= $WBSInfo['name'].'"'  ?></h4>
    
   
    
<div class="Edit_result_event">
       
    <?php $form = ActiveForm::begin(); ?>
    
    
    <div class="container">

		
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
		  <div class="col-sm-12">
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
	  
	  <div class="row">
			  <div class="col-sm-2">
				<?php 
				
					echo  Html::submitButton('', [
							'span class' => 'glyphicon glyphicon-knight',
							'title'=>'Генерация комментария в инциденте  mantis',
							'name'=>'btn',
							'value' => 'mant_']);
				 if(!empty($model->ResultEventsMantis)){
					  echo $form->field($model, 'ResultEventsMantis');
					 }
				
				?>
			</div>	
			<div class="col-sm-6">
			  <?php 
			  if(empty($model->ResultEventsMantis)){
			   echo('
				 <p><b>Перед созданием комментария  в mantis выбери инцидент,  к котрому он будет привязан: </b></p>
				    <table border = "1" cellpadding="4" cellspacing="2">
					 <tr><th>Результат</th><th>Работа</th><th>Номер инцидента</th><th></th></tr>
				  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=4>&nbsp;</td></tr>');
					  if(!empty($mantis_links)){
						  foreach($mantis_links as $mtl){
							echo('<tr><td>'.$mtl['name'].'</td><td>'.$mtl['WorkName'].'</td><td>'.$mtl['mantisNumber']
							.'</td><td> <input name="mantis_link" type="radio" value='.$mtl['mantisNumber'].'></td></tr>');  
						  }
					  }	  
					  
					 echo('</table>');
			 } else{
				
				echo Html::a($model->ResultEventsMantis, $url_mantis.$model->ResultEventsMantis,['target' => '_blank']);
				 }
			  ?>
		  </div>
		  	  
	  </div>
	  
	  
	  
	</div>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary',
												 'name'=>'btn',
												 'value' => 'save_']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- Edit_result_event -->
