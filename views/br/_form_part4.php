 <?php 
	use app\models\Organization;
	use app\models\RoleModelType;
	use app\models\LifeCycleType;
    use yii\helpers\ArrayHelper;
    use app\models\Projects;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\grid\GridView;
		
 ?>
  
  
  
   <div class="container">
	   <div class="row">
		  <div class="col-sm-4">
		    <?php  
		     $url1 = Url::to(['site/help']);
			 echo  Html::a('<span class="glyphicon glyphicon-question-sign"></span>', $url1.'#WorkBreakdownStructure',['title' => 'Помощь по разделу',]);
			 echo "   ";
			  $url2 = Url::to(['br/create_ewp', 'idBR'=>$model->idBR]);;
			 echo  Html::a('<span class="glyphicon glyphicon-plus"></span>', $url2,['title' => 'Добавить оценку трудозатрат',]);
			?>  
	      </div>
	      <div class="col-sm-4">
					   
		  </div>
		  <div class="col-sm-4">
					    
		  </div>
		 </div>
	   <div class="row">
		   <div class="col-sm">
			<?php echo GridView::widget([
					'dataProvider' => $EstimateListdataProvider,
					'columns' => [
					    'dataEstimate',
					    [
					    'label' => 'Наименование оценки',
					    //'headerOptions' => ['width' => '200'],
					    'format' => 'raw',
					    'value' => function($data){
							return Html::a(
									           '  '.$data->EstimateName,
									           Url::toRoute(['br/update_estimate_work_packages', 'idEWP' =>$data->idEstimateWorkPackages,'idBR'=>$data->idBR]),
									           ['title' => 'показать трудозатраты по BR', 'align' => 'right']
									          );
								
					     }
					    ],
					    //'finished',
					    [
					      'attribute' => 'finished',
                          'format' => 'raw',
                          'value' => function ($model, $key, $index, $column) {
			                    $active = $model->{$column->attribute} === 1;
			                    return \yii\helpers\Html::tag(
			                        'span',
			                        $active ? 'Закрыта' : 'Активна',
			                        [
			                            'class' => 'label label-' . ($active ? 'danger' : 'success'),
			                        ]
			                    );
			                },
					    ],
							
					    
					    [
				            'class' => 'yii\grid\ActionColumn',
				            'headerOptions' => ['width' => '120'],
				            'template' => '{update_ewp} {delete_ewp} {copy_ewp} {print_ewp} {print_ewp1}',
				            //Замыкание в анонимной функции PHP- я нихера не понял как это работает  -(((
				            'buttons' => [
				                'delete_ewp' => function ($url,$model){  
									$url = Url::to(['br/delete_ewp', 'idEWP' =>$model->idEstimateWorkPackages]);
				                    return Html::a(
				                    '<span class="glyphicon glyphicon-trash"></span>', 
				                    $url,['title' => 'Удалить оценку']);
				                },
				                'copy_ewp' => function ($url,$model){  
									$url = Url::to(['br/copy_ewp', 'idEWP' =>$model->idEstimateWorkPackages]);
				                    return Html::a(
				                    '<span class="glyphicon glyphicon-duplicate"></span>', 
				                    $url,['title' => 'Копировать оценку']);
				                },
				                'update_ewp' => function ($url,$model){  
									$url = Url::to(['br/update_estimate_work_packages', 'idEWP' =>$model->idEstimateWorkPackages ,'idBR'=>$model->idBR]);
				                    return Html::a(
				                    '<span class="glyphicon glyphicon-pencil"></span>', 
				                    $url,['title' => 'Изменить оценку']);
				                },
				                'print_ewp' => function ($url,$model){  
									$url = Url::to(['br/print_schedule', 'idEWP' =>$model->idEstimateWorkPackages ,'idBR'=>$model->idBR]);
				                    return Html::a(
				                    '<span class="glyphicon glyphicon-retweet"></span>', 
				                    $url,['title' => 'Диаграма Ганта','target' => '_blank']);
				                },
				                'print_ewp1' => function ($url,$model){  
									$url = Url::to(['br/print_estimate_work_packages_grouped', 'idEWP' =>$model->idEstimateWorkPackages ,'idBR'=>$model->idBR]);
				                    return Html::a(
				                    '<span class="glyphicon glyphicon-th"></span>', 
				                    $url,['title' => 'Показать всю оценку с группировкой по ролям','target' => '_blank']);
				                },
				            ],
				            
				        ],
					    
					    
					    
					    
					],
					]);
			?>	
	      </div>
	      
	   </div>
 	</div>
 	
 	
 	
 			
				
 
  
