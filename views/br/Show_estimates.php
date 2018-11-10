 <?php 
	use app\models\Organization;
	use app\models\RoleModelType;
	use app\models\LifeCycleType;
	use app\models\Wbs;
	use app\models\EstimateWorkPackages;
    use yii\helpers\ArrayHelper;
    use app\models\Projects;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\widgets\Breadcrumbs;
    
    $EstimateWorkPackages = EstimateWorkPackages::find()->where(['deleted' => 0, 'idBR'=>$idBR]);
		$items1 = ArrayHelper::map($EstimateWorkPackages,'idEstimateWorkPackages','EstimateName'.' от '.'dataEstimate');
		$params1 = [
			'prompt' => 'Выберите оценку',
			'disabled'=>"disabled"
		];
    
		//ищем родителей для rootid
		  
			$wbs_current_node = Wbs::findOne(['id'=>$id_node]);
			if(!is_null($wbs_current_node)){
				$parents = $wbs_current_node->parents()->all();	
				foreach($parents as $prn){
					$this->params['breadcrumbs_wbs'][] = ['label' => $prn['name'], 'url' => Url::toRoute(['br/update', 'id' =>$idBR, 'page_number' => 3, 'root_id'=>$prn['id']])];
				}
				$this->params['breadcrumbs_wbs'][]=	['label' => $wbs_current_node->name];
			}
		
		
		$this->title = 'Перечень работ,  которые необходимо выполнить для достижения результата:  "' . $wbs_current_node->name . '"';
 ?>
  
   <h1><?= Html::encode($this->title) ?></h1>
  
   <div class="container">
		<div class="row">
		  <div class="col-sm-4">
		    <?php  
		     $url1 = Url::to(['site/help']);
			 echo  Html::a('<span class="glyphicon glyphicon-question-sign"></span>', $url1.'#WorkBreakdownStructure',['title' => 'Помощь по разделу',]);
			 echo "   ";
			  $url2 = Url::to(['br/create_work', 'idBR'=>$idBR]);;
			 echo  Html::a('<span class="glyphicon glyphicon-plus"></span>', $url2,['title' => 'Добавить работу',]);
			 echo "   ";
			  $url2 = Url::to(['br/works_template', 'idBR'=>$idBR]);;
			 echo  Html::a('<span class="glyphicon glyphicon-save-file"></span>', $url2,['title' => 'Добавить работы по шаблону',]);
			?>  
	      </div>
	      <div class="col-sm-4">
					   
		  </div>
		  <div class="col-sm-4">
					    
		  </div>
		 </div>
	   <div class="row">
			<div class="col-sm4">
			   <?= Breadcrumbs::widget([
			    'homeLink' => ['label' => 'WBS'],
			    'links' => isset($this->params['breadcrumbs_wbs']) ? $this->params['breadcrumbs_wbs'] : [],
			]) ?>
		    </div>
	   </div> 	 

	   <div class="row">
		  <div class="col-sm-4">
		   
	      </div>
	      <div class="col-sm-4">
					   
		  </div>
		  <div class="col-sm-4">
					    
		  </div>
		 </div>
	   <div class="row">
		   <div class="col-sm">
			<?php echo GridView::widget([
					'dataProvider' => $WorksOfEstimateProvider,
					
					//'columns' => [
					    //'dataEstimate',
					    //[
					    //'label' => 'Наименование оценки',
					    ////'headerOptions' => ['width' => '200'],
					    //'format' => 'raw',
					    //'value' => function($data){
							//return Html::a(
									           //'  '.$data->EstimateName,
									           //Url::toRoute(['br/show_br_estimates', 'id' =>$data->idBR]),
									           //['title' => 'показать трудозатраты по BR', 'align' => 'right']
									          //);
								
					     //}
					    //],
					    //[
				            //'class' => 'yii\grid\ActionColumn',
				            //'headerOptions' => ['width' => '120'],
				            //'template' => '{update_ewp} {delete_ewp}',
				            ////Замыкание в анонимной функции PHP- я нихера не понял как это работает  -(((
				            //'buttons' => [
				                //'delete_ewp' => function ($url,$model){  
									//$url = Url::to(['br/delete_ewp', 'idEWP' =>$model->idEstimateWorkPackages]);
				                    //return Html::a(
				                    //'<span class="glyphicon glyphicon-trash"></span>', 
				                    //$url,['title' => 'Удалить оценку']);
				                //},
				                //'update_ewp' => function ($url,$model){  
									//$url = Url::to(['br/update_estimate_work_packages', 'idEWP' =>$model->idEstimateWorkPackages ,'idBR'=>$model->idBR]);
				                    //return Html::a(
				                    //'<span class="glyphicon glyphicon-pencil"></span>', 
				                    //$url,['title' => 'Изменить оценку']);
				                //},
				            //],
				            
				        //],
					    
					    
					    
					    
					//],
					]);
			?>	
	      </div>
	      
	   </div>
 	</div>
 	
 	
 	
 			
				
 
  
