<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */
/* @var $form yii\widgets\ActiveForm */
?>

 <div class="row">
	<div class="col-sm-12">
		<?php  if($Workdates){
			echo '<b>Дата начала работы: </b> '.$Workdates['WorkBegin'].'<br>';
			echo '<b>Дата окончания работы: </b> '.$Workdates['WorkEnd'];
			}?>
	</div>
</div>
 <div class="row">
	<div class="col-sm-12">
		<?php 
			echo '<p><b> Работы предшественики</b>   '
				 .Html::submitButton('', [
						'span class' => 'glyphicon glyphicon-plus-sign',
						'title'=>'Указать задачу предшественика',
						'name'=>'btn',
						'value' => 'addpw_']).'</p>'; 
		?>
		<table border = "1" cellpadding="4" cellspacing="2"> 
			   <tr><th></th><th>Результат</th><th>Работа</th><th>Задержка</th><th></th></tr>
			  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=4>&nbsp;</td></tr>
			  <?php
			  if(count($ListPrevWorks)>0){ // по работе  есть связанные задачи
					foreach($ListPrevWorks as $lpw){
							echo('<tr><td>  '
							.Html::submitButton('', [
								'span class' => 'glyphicon glyphicon-minus-sign',
								'title'=>'Удалить трудозатраты по работе',
								'name'=>'btn',
								'value' => 'dellnk_'.$lpw['idLink']]).' '
							.Html::submitButton('', [
								'span class' => 'glyphicon glyphicon-pencil',
								'title'=>'Изменить трудозатраты по работе',
								'name'=>'btn',
								'value' => 'editlnk_'.$lpw['idLink']])	
								.'</td><td>'.$lpw['name']
								.'</td><td>'.$lpw['WorkName']
								.'</td><td>'.$lpw['lag']
								.'</td></tr>');	
					}
			  }			
			  ?>
		</table>	  
	</div>
	<div class="col-sm-6">
		
	</div>
</div>
