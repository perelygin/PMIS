
<?php
 
?>

<?php


use app\models\EstimateWorkPackages;
use app\models\Wbs;
use app\models\VwProjectCommand;
use app\models\ServiceType;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Collapse;
use yii\bootstrap\Tabs;
use vova07\imperavi\Widget;

//use app\models\Wbs;



/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */
//Данные для выпадающего списка типа услуг
$ServiceType = ServiceType::find()->all();
$items1 = ArrayHelper::map($ServiceType,'idServiceType','ServiceName');
$params1 = [
];

$EWP_BrInfo = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$model->idEstimateWorkPackages])->getBrInfo();
$wbs_current_node = Wbs::findOne(['id'=>$model->idWbs]);
$WBSInfo = $wbs_current_node->getWbsInfo();

//ищем родителей для rootid
			
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
											'id_node' => $model->idWbs, ])];
			}

$this->title = "BR-". $WBSInfo['BRNumber']." ".$WBSInfo['BRName'];
$this->params['breadcrumbs'][] = 'Изменение параметров работы';
?>
<div class="works-of-estimate-update">
    <h3><?= Html::encode($this->title) ?></h3>
    Работа, необходимая для достижения результата: " <b><?= $WBSInfo['name'].'"'  ?></b>
    <br>
    Оценка трудозатрат: <b> "<?= Html::encode($EWP_BrInfo['EstimateName']).'"</b> от <b>'. $EWP_BrInfo['dataEstimate'].'  '.$EWP_BrInfo['finished']?></b>
    
    
    
	
     
    
     <?php $form = ActiveForm::begin([
        'action' => ['update','idWorksOfEstimate'=>$model->idWorksOfEstimate,'idBR'=>$idBR,'idWbs'=>$model->idWbs,'idEstimateWorkPackages'=>$model->idEstimateWorkPackages],  
        'method' => 'post',
        'id' =>"frm1"]); 
        $item1 = array('label' => 'Трудозатраты','content' => $this->render('_WrkUpdWorkEfforts', ['idBR'=>$idBR,'VwListOfWorkEffort'=>$VwListOfWorkEffort,'form' => $form]));
        $item2 = array('label' => 'Mantis','content' => $this->render('_WrkUpdParam', ['idBR'=>$idBR,'WBS'=>$wbs_current_node,'model' => $model,'form' => $form, 'MantisPrjLstArray'=>$MantisPrjLstArray]));
		
		$item3 = array('label' => 'Расписание','content' => $this->render('_WrkUpdSchedule', ['form' => $form]));
		
		switch ($page_number) { //определяем активную вкладку
		    case 1:
		        $item1['active'] = true;
		        break;
		    case 2:
		        $item2['active'] = true;
		        break;
		    case 3:
		        $item3['active'] = true;
		        break;
		    default:
		         $item1['active'] = true;
		        break;
		}
		
		$items = array();
  		$items[]= $item1;
  		$items[]= $item2;
  		$items[]= $item3;
  		
     ?>
    
    
<div class="container">
	<div class="row">
	    <div class="col-sm-12">
					<?php 
					
					echo  Html::submitButton('', [
								'span class' => 'glyphicon glyphicon-question-sign',
								'title'=>'Помощь по разделу',
								'name'=>'btn',
								'value' => 'help_'])
					 ?>		
		</div>
	</div>
   <div class="row">
		<div class="col-sm-8">
		    <?= $form->field($model, 'WorkName')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-sm-4">
		    <?php // $form->field($model, 'ServiceType')->dropDownList($items1,$params1) ?>
	    </div>
   </div> 
   <div class="row">
		<div class="col-sm-12">
		    <?php echo Tabs::widget(['items' => $items]); ?>
	    </div>
   </div> 
   
   
   

		
    
    
    <div class="row">
		<div class="col-sm-12">
			<br>
			<?php
			echo Collapse::widget([
			    'items' => [
			        [
			            'label' => 'История изменений',
			            'content' => $this->render('_history', ['model' => $model, 'form' => $form,'LogDataProvider' => $LogDataProvider]),
			            'contentOptions' => [],
			            'options' => []
			        ],
			    ]
			]);
			?>
		</div> 
	</div> 
   </div> 
   
       <div class="form-group">
        <?php echo  Html::submitButton('Сохранить', ['class' => 'btn btn-success',
											'name'=>'btn',
											'value' => 'save_']);
					
		?>
    </div>
    
</div> 	
    
  
   


    <?php ActiveForm::end(); ?>
</div>



