<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use app\models\ResultType;

/* @var $this yii\web\View */
/* @var $model app\models\Wbs */
/* @var $form ActiveForm */


		//готовим массив для dropdownist c типом результатов
		
		$ResultType = ResultType::find()->where(['deleted'=>0])->all();
		$items1 = ArrayHelper::map($ResultType,'idResultType','ResultTypeName');
		$params1 = [
		
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
								'span class' => 'glyphicon glyphicon-knight',
								'title'=>'Создать инцидент  в mantis',
								'name'=>'btn',
								'value' => 'crtm_'])
			.'    '
			.Html::submitButton('', [
								'span class' => 'glyphicon glyphicon-th',
								'title'=>'табличная форма',
								'name'=>'btn',
								'value' => 'tblf_'])
			.'     '
			.Html::submitButton('', [
								'span class' => 'glyphicon glyphicon-save-file',
								'title'=>'выгрузить в excel',
								'name'=>'btn',
								'value' => 'excl_'])
			.'     '
			.Html::a($model->mantis, $url4,['title' => '',])
		  ?>
	    </div>  
   </div> 	
   <div class="row">
		<div class="col-sm-6">
			<?= $form->field($model, 'name') ?>   
	    </div>
	    <div class="col-sm-6">
			<?= $form->field($model, 'idResultType')->dropDownList($items1,$params1) ?>   
	    </div>
	    <div class="col-sm-4">
			
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
