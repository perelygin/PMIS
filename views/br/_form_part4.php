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
			  $url2 = Url::to(['br/add_estimate', 'idBR'=>$model->idBR]);;
			 echo  Html::a('<span class="glyphicon glyphicon-plus"></span>', $url2,['title' => 'Добавить оценку трудозатрат',]);
			?>  
	      </div>
	      <div class="col-sm-4">
					   
		  </div>
		  <div class="col-sm-4">
					    
		  </div>
		 </div>
	   <div class="row">
		   <div class="col-sm-4">
			<?php echo GridView::widget([
					'dataProvider' => $EstimateListdataProvider,
					]);
			?>	
	      </div>
	      
	   </div>
 	</div>
 	
 	
 	
 			
				
 
  
