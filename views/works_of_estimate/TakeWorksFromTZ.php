<?php 
use app\models\Wbs;
use app\models\EstimateWorkPackages;
use app\models\AnalysisDBKtemp;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


		$TextForWorks = AnalysisDBKtemp::getRecords();
		$mode = AnalysisDBKtemp::getRecordCount(); 
		
        $EWP_BrInfo = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEstimateWorkPackages])->getBrInfo();
		//ищем родителей для rootid
			$wbs_current_node = Wbs::findOne(['id'=>$id_node]);
			if(!is_null($wbs_current_node)){
				$parents = $wbs_current_node->parents()->all();	
				foreach($parents as $prn){
					$this->params['breadcrumbs'][] = ['label' => $prn['name'], 
					'url' => Url::toRoute(['br/update', 
											'id' =>$idBR, 
											'page_number' => 3, 
											'root_id'=>$prn['id']])];
				}
				
				
				$this->params['breadcrumbs'][]=	['label' => $wbs_current_node->name,'url' => Url::toRoute(['works_of_estimate/index', 
											'idBR' =>$idBR, 
											'id_node' => $id_node, ])];
			}
			
  $this->title = "Генерация перечня работ на основе файла Технического задания(*.dbk)";
  $this->params['breadcrumbs'][] = $this->title;
  $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); 
  
  
?>
<div class="works-of-estimate-view">

     <h3><?php echo 'BR '.$EWP_BrInfo['BRNumber'].' "'.$EWP_BrInfo['BRName'].'"'  ?> </h3> 
     Оценка трудозатрат: <b> <?php echo $EWP_BrInfo['EstimateName'] ?></b>  от <b> <?php echo $EWP_BrInfo['dataEstimate'].' '.$EWP_BrInfo['finished']   ?>  </b>
     <br>
    
    <?php 
    
		if($mode == 0){ //нет записей во временной таблице
			echo 'Выбери файл для разбора<Br>';
			echo $form->field($model, 'DbkFile')->fileInput();
		}else{
			echo(Html::submitButton('Генерация работ', [
						//'span class' => 'glyphicon glyphicon-bishop',
						'title'=>'Генерировать работы в текущем результате',
						'name'=>'btn',
						'value' => 'mnt_'])).'     ';	
			echo(Html::submitButton('Отмена', [
						//'span class' => 'glyphicon glyphicon-bishop',
						//'title'=>'Создать инциденты в mantis',
						'name'=>'btn',
						'value' => 'cancl_']));
		    echo '<br/>';
			if($TextForWorks){
			echo '<table  border="1" cellpadding="5" width="100%">';
			 foreach($TextForWorks as $tfw){
				 
				 echo '<tr><td><b>'.$tfw['Title'].'</b></td><td><input name="selectedReq[]" type="checkbox"  value= '.$tfw['idAnalysisDBKtemp'].'></td></tr>';
				 echo '<tr><td colspan="2">'.$tfw['Text'].'</td></tr>';
				 }
			echo '</table>';
			}
		}
		
		
    ?>
</div>		


<?php 
echo '<br>';
if($mode == 0){ //нет записей во временной таблице
	echo(Html::submitButton('Обработать файл', [
						//'span class' => 'glyphicon glyphicon-bishop',
						'title'=>'распарсить dbk',
						'name'=>'btn',
						'value' => 'prs_'])).'     ';	

}else{
	echo(Html::submitButton('Генерация работ', [
						//'span class' => 'glyphicon glyphicon-bishop',
						'title'=>'Генерировать работы в текущем результате',
						'name'=>'btn',
						'value' => 'mnt_'])).'     ';	
	}

ActiveForm::end(); 
?>
