<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use app\models\ResultType;
use app\models\ResultStatus;
use app\models\SystemVersions;
/* @var $this yii\web\View */
/* @var $model app\models\Wbs */
/* @var $form ActiveForm */


		//готовим массив для dropdownist c типом результатов
		
		$ResultType = ResultType::find()->where(['deleted'=>0])->all();
		$items1 = ArrayHelper::map($ResultType,'idResultType','ResultTypeName');
		$params1 = [
		
		];
		
		//готовим массив для dropdownist cо статусом результата
		
		
		$ResultStatus = ResultStatus::find()->where(['deleted'=>0])->all();
		$items2 = ArrayHelper::map($ResultStatus,'idResultStatus','ResultStatusName');
		$params2 = [
		
		];
		//готовим массив для dropdownist c версиями
		
		
		$SystemVersions = SystemVersions::find()->where(['deleted'=>0,'released'=>0])->all();
		$items3 = ArrayHelper::map($SystemVersions,'idsystem_versions','version_number');
		$params3 = [
		
		];
?>




<div class="wbs_update">

<?php $form = ActiveForm::begin(); ?>


<div class="container">
   <div class="row">
	  	<div class="col-sm-4">
		  <?php
		   $url4='';
		    echo Html::submitButton('', [
								'span class' => 'glyphicon glyphicon-usd',
								'title'=>'Работы и трудозатраты по реализации  результата',
								'name'=>'btn',
								'value' => 'estm_'])
			//Html::submitButton('', [
								//'span class' => 'glyphicon glyphicon-knight',
								//'title'=>'Создать инцидент  в mantis',
								//'name'=>'btn',
								//'value' => 'crtm_']).'    '.
			//.'     '
			//.Html::a($model->mantis, $url4,['title' => '',])
		  ?>
	    </div>  
   </div> 	
   <div class="row">
		<div class="col-sm-3">
			<?= $form->field($model, 'name') ?>   
	    </div>
	    <div class="col-sm-3">
			<?= $form->field($model, 'idResultType')->dropDownList($items1,$params1) ?>   
	    </div>
	    <div class="col-sm-3">
			<?= $form->field($model, 'idResultStatus')->dropDownList($items2,$params2) ?>   
	    </div>
	    <div class="col-sm-3">
			<?= $form->field($model, 'idsystem_versions')->dropDownList($items3,$params3) ?>   
	    </div>
	    
   </div> 	    
   <div class="row">
	    

   </div> 
  <div class="row">
		<div class="col-sm-6">
			<?php 
				echo $form->field($model, 'description')->widget(Widget::className(), [
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
	   <div class="col-sm-6">
		   <p>
		    <?= Html::submitButton('', [
						'span class' => 'glyphicon glyphicon-plus-sign',
						'title'=>'Добавить событие по результату',
						'name'=>'btn',
						'value' => 'addevent'])?>
		   <b>События по результату</b>
		   </p>
		   <table border = "1" cellpadding="4" cellspacing="2"> 
			   <tr><th> &nbsp &nbsp &nbsp </th>
			       <th> &nbsp Дата &nbsp </th>
			       <th>&nbsp Событие &nbsp </th>
			       <th>&nbsp Ответственный &nbsp </th>
			       <th>&nbsp Mantis &nbsp </th>
			     </tr>
			   <?php
			   if(count($events)>0){ // по работе  есть оценки трудозатрат
					foreach($events as $evn){
					echo('<tr><td width=70 >'
					.Html::submitButton('', [
								'span class' => 'glyphicon glyphicon-minus',
								'title'=>'Удалить событие',
								'name'=>'btn',
								'value' => 'del_'.$evn['idResultEvents']])
					.' '
					.Html::submitButton('', [
								'span class' => 'glyphicon glyphicon-pencil',
								'title'=>'Изменить событие',
								'name'=>'btn',
								'value' => 'edit_'.$evn['idResultEvents']])				
					  .'</td><td width=75>'.$evn['ResultEventsDate'].'</td><td>'
								  .$evn['ResultEventsName'].'</td><td>'
								  .$evn['responsible'].'</td><td>'
								  .$evn['ResultEventsMantis'].'</td></tr>');
					}
				}else {
					echo '<tr><td colspan="5"> нет данных </td></tr>';
					}	
			   ?>
			   
			   </table>
	   </div>	    
   </div> 

        
        
        
    
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary',
												   'name'=>'btn',
												 'value' => 'save_']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div><!-- container -->

</div><!-- wbs_update -->
