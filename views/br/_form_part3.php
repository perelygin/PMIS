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
			} //else throw new \yii\web\NotFoundHttpException('Запись не найдена');
			
			
			
			
 ?>
  
  
  
   <div class="container">
	   <div class="row">
		   <div class="col-sm">
			   <?= Breadcrumbs::widget([
			    'homeLink' => ['label' => 'WBS', 'url' => '/course'],
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
			            //['class' => 'yii\grid\SerialColumn'],
			
			            //'id',
			            //'depth',
			            
	 	                   //[
				            //'format' => 'raw',
				            //'value' => function($data){
								//$haveChild = $data->rgt - $data->lft;
								//if($haveChild > 1){  //есть подчиненные узлы
									 //return Html::tag('p', '', ['class' => 'glyphicon glyphicon-fullscreen']);
								//}else{
									//return  Html::tag('p', '', ['class' => 'glyphicon glyphicon-minus']);
								//}
							//},
				        //],
			            
			            [
					    'label' => 'name',
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
								//Html::a(
									            //'  '.$data->name,
									            //'www.hyandex.ru',
									             //['title' => 'Выбери узел', 
									             //'class' => 'glyphicon glyphicon-minus',]
												//);	
								}

					    }
					],	
			            ['class' => 'yii\grid\ActionColumn'],
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
 	
 	
 	
 			
				
 
  
