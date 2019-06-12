<?php 
use app\models\Wbs;
use app\models\EstimateWorkPackages;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


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
    Выбери файл для разбора
    <?= $form->field($model, 'DbkFile')->fileInput() ?>
</div>		


<?php 
echo(Html::submitButton('Обработать файл', [
						//'span class' => 'glyphicon glyphicon-bishop',
						'title'=>'распарсить dbk',
						'name'=>'btn',
						'value' => 'prs_'])).'     ';	
echo(Html::submitButton('Отмена', [
						//'span class' => 'glyphicon glyphicon-bishop',
						//'title'=>'Создать инциденты в mantis',
						'name'=>'btn',
						'value' => 'cancl_']));
ActiveForm::end(); 
?>
