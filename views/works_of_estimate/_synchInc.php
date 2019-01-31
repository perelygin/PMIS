<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */


?>
<?php 
//$form = ActiveForm::begin([
        //'action' => ['index','idBR'=>$idBR, 'id_node'=>$id_node],
        //'method' => 'post',
        //'id' =>'w112'
    //]); 
    ?>
    
	<div class="row">
		<div class="col-sm-12">   
		   <?php
				echo('
				 <p>Тут можно создать работы на основании инцидентов mantis. Для этого, указываем головной инцидент. Как правило 
				 это инцидент с БФТЗ(выводится список работ с инцидентами по результатам с типом "БФТЗ"). 
				 <br> 
				 Запросом к mantis получаем перечень связанных инцидентов, и отмечаем те из них,  по которым нужно создавать работы.
				 <br>
				 Работы создаются в последней оценке трудозатрат.В качестве исполнителя указывается текущий ответственный. Трудозатраты по созданной работе равны 0.
				 <br></p>');
				?> 
		</div>   
	</div>					
	<div class="row">
		<div class="col-sm-6"> 
				   <?php
						echo( Html::submitButton('', [
										'span class' => 'glyphicon glyphicon-knight',
										'title'=>'Получить перечень связанных инцидентов',
										'name'=>'btn',
										'value' => 'mnt1_']).'<b> Выбери инцидент с БФТЗ: </b>
						   </p>
						    <table border = "1" cellpadding="4" cellspacing="2">
							 <tr><th>Результат</th><th>Работа</th><th>Номер инцидента</th><th></th></tr>
						  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=4>&nbsp;</td></tr>');
						  if(!empty($mantis_links)){
							  $i=0;
							  foreach($mantis_links as $mtl){
								$checked='';
								if($i == 0)  $checked = 'checked'; 
								echo('<tr><td>'.$mtl['name'].'</td><td>'.$mtl['WorkName'].'</td><td>'.$mtl['mantisNumber']
								.'</td><td> <input name="mantis_link" type="radio" value='.$mtl['mantisNumber'].' '.$checked.' ></td></tr>');  
								$i=$i+1;
							  }
						  }	  
						 echo('</table>');
				   ?>
		</div>   
		<div class="col-sm-6"> 
			<?php
			echo( Html::submitButton('', [
						'span class' => 'glyphicon glyphicon-bishop',
						'title'=>'Регистрация работ по выбранным инцидентам',
						'name'=>'btn',
						'value' => 'mnt2_']).'<b> Выбери инциденты для регистрации работ: </b>
		   </p>
		    <table border = "1" cellpadding="4" cellspacing="2">
			 <tr><th>Инцидент</th><th>Название</th><th>Ответственный</th><th></th></tr>
		  <tr><td bgcolor="#FFFFFF" style="line-height:10px;" colspan=4>&nbsp;</td></tr>');
		   if(!empty($related_issue)){
			foreach($related_issue as $rli){
				echo('<tr><td>'.$rli['mantisNumber'].'</td><td>'.$rli['name'].'</td><td>'.$rli['handler']
								.'</td><td> <input name="relatedissue[]" type="checkbox" value='.$rli['mantisNumber'].'></td></tr>');
			}	
		   }
		   echo('</table>');
			?>
		</div>					
					


    <?php //ActiveForm::end(); ?>
