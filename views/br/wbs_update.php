<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use app\models\ResultType;

/* @var $this yii\web\View */
/* @var $model app\models\Wbs */
/* @var $form ActiveForm */


		//готовим массив для dropdownist c членами команды
		
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
		<div class="col-sm-4">
			<?= $form->field($model, 'name') ?>   
	    </div>
	    <div class="col-sm-4">
			<?= $form->field($model, 'idResultType')->dropDownList($items1,$params1) ?>   
	    </div>
	    <div class="col-sm-4">
			<?= $form->field($model, 'mantis') ?>   
	    </div>
   </div> 	    
   <div class="row">
	    

   </div> 
  <div class="row">
		<div class="col-sm">
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
   </div> 

        
        
        
    
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary',
												   'name'=>'btn',
												 'value' => 'save_']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div><!-- container -->

</div><!-- wbs_update -->
