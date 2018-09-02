 <?php 
	use app\models\Organization;
	use app\models\RoleModelType;
	use app\models\Wbs;
	//use app\models\LifeCycleType;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
     use yii\helpers\Url;
    use app\models\Projects;
    use yii\grid\GridView;
    use yii\widgets\Breadcrumbs;
    
    
		//$Organization = Projects::find()->all();
		//$items = ArrayHelper::map($Organization,'idProject','ProjectName');
		//$params = [
			//'prompt' => 'Выберите проект'
		//];
		
		//$RoleModelType = RoleModelType::find()->all();
		//$items1 = ArrayHelper::map($RoleModelType,'idRoleModelType','RoleModelTypeName');
		//$params1 = [
			//'prompt' => 'Выберите ролевую модель',
			//'disabled'=>"disabled"
		//];
		//ищем родителей для rootid
		  
			$wbs_current_node = Wbs::findOne(['id'=>$root_id]);
			if(!is_null($wbs_current_node)){
				$parents = $wbs_current_node->parents()->all();	
				foreach($parents as $prn){
					$this->params['breadcrumbs_wbs'][] = ['label' => $prn['name'], 'url' => Url::toRoute(['br/update', 'id' =>$model->idBR, 'page_number' => 3, 'root_id'=>$prn['id']])];
				}
				$this->params['breadcrumbs_wbs'][]=	['label' => $wbs_current_node->name];
			} //else throw new \yii\web\NotFoundHttpException('Запись не найдена');
			
			
			
			
 ?>
  
  
  <div class="container1">

  </div>  
   <div class="container">
	  <div class="row">
		<div class="col-sm-4">
		   <?php  
		     $url1 = Url::to(['site/help']);
			 echo  Html::a('<span class="glyphicon glyphicon-question-sign"></span>', $url1.'#WorkBreakdownStructure',['title' => 'Помощь по разделу',]);
			 echo "   ";
			  $url2 = Url::to(['br/add_wbs_child', 'idBR'=>$model->idBR,'parent_node_id'=>$root_id]);;
			 echo  Html::a('<span class="glyphicon glyphicon-plus"></span>', $url2,['title' => 'Добавить подчиненный узел',]);
			?>
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
		   <div class="col-sm">
			   
			  	 <?= GridView::widget([
			        'dataProvider' => $wbs_leaves,
			       // 'filterModel' => $searchModel,
			        'columns' => [
			            [
					    'label' => 'Результат проекта',
					    'format' => 'raw',
					    'value' => function($data){
							$haveChild = $data->rgt - $data->lft;
							if($haveChild > 1){  //есть подчиненные узлы
								return Html::a(
									           '  '.$data->name,
									           Url::toRoute(['br/update', 'id' =>$data->idBr, 'page_number' => 3, 'root_id'=> $data->id]),
									           ['title' => 'Выбери узел', 
									           'class' => 'glyphicon glyphicon-fullscreen',
									           'align' => 'right']
									          );
								} else{
								return Html::tag('p', '  '.$data->name, ['class' => 'glyphicon glyphicon-minus']);
								}

					    }
					],	
			           // ['class' => 'yii\grid\ActionColumn'],
			            [
				            'class' => 'yii\grid\ActionColumn',
				            'header'=>'Выбери', 
				            'headerOptions' => ['width' => '80'],
				            'template' => '{add_wbs_child} {update_wbs_node} {delete_wbs_node}',
				            //Замыкание в анонимной функции PHP- я нихера не понял как это работает  -(((
				            'buttons' => [
				                'add_wbs_child' => function ($url,$model){  
									$url = Url::to(['br/add_wbs_child', 'idBR'=>$model->idBr,'parent_node_id'=>$model->id]);
				                    return Html::a(
				                    '<span class="glyphicon glyphicon-plus"></span>', 
				                    $url,['title' => 'Добавить подчиненный узел']);
				                },
				                'update_wbs_node' => function ($url,$model){  
									$url = Url::to(['br/update_wbs_node', 'idBR'=>$model->idBr,'id_node'=>$model->id]);
				                    return Html::a(
				                    '<span class="glyphicon glyphicon-pencil"></span>', 
				                    $url,['title' => 'Изменить узел']);
				                },
				                'delete_wbs_node' => function ($url,$model){  
									$url = Url::to(['br/delete_wbs_node', 'idBR'=>$model->idBr,'id_node'=>$model->id]);
				                    return Html::a(
				                    '<span class="glyphicon glyphicon-trash"></span>', 
				                    $url,['title' => 'Удалить узел']);
				                },				        
				            ],
				            
				        ],
			        ],
			    ]); 
			  ?>   
	      </div>
	   </div>
	   <div class="row">
		   <div class="col-sm-6">
				
	      </div>
	      <div class="col-sm-6">
				
	      </div>
	   </div>
 	</div>
 	
 	
 	
 			
				
 
  
