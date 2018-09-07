<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
    
use app\models\Wbs;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchWorksOfEstimate */
/* @var $dataProvider yii\data\ActiveDataProvider */


    
		//ищем родителей для rootid
		  
			$wbs_current_node = Wbs::findOne(['id'=>$id_node]);
			if(!is_null($wbs_current_node)){
				$parents = $wbs_current_node->parents()->all();	
				foreach($parents as $prn){
					$this->params['breadcrumbs_wbs'][] = ['label' => $prn['name'], 
					'url' => Url::toRoute(['br/update', 
											'id' =>$idBR, 
											'page_number' => 3, 
											'root_id'=>$prn['id']])];
				}
				$this->params['breadcrumbs_wbs'][]=	['label' => $wbs_current_node->name];
			}
		
		
		$this->title = 'Перечень работ,  которые необходимо выполнить для достижения результата:  "' . $wbs_current_node->name . '"';

?>
<!-- <pre> <?= $idEstimateWorkPackages ?></pre>  -->
<div class="works-of-estimate-index">

    <h1><?= Html::encode($this->title) ?></h1>
   

	   <div class="container">
		   <div class="row">
				<div class="col-sm4">
				   <?= Breadcrumbs::widget([
				    'homeLink' => ['label' => 'WBS'],
				    'links' => isset($this->params['breadcrumbs_wbs']) ? $this->params['breadcrumbs_wbs'] : [],
				]) ?>
			    </div>
		   </div> 
		   <div class="row">
				<div class="col-sm4">
				   <?php
				     $url1 = Url::to(['site/help']);
					 echo  Html::a('<span class="glyphicon glyphicon-question-sign"></span>', $url1.'#WorkBreakdownStructure',['title' => 'Помощь по разделу',]);
					 echo "   ";
					 $url2 = Url::to(['works_of_estimate/create', 'idBR'=>$idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages , 'idWbs'=>$id_node]);;
					 echo  Html::a('<span class="glyphicon glyphicon-plus"></span>', $url2,['title' => 'Добавить работу',]);
					 echo "   ";
					  $url2 = Url::to(['works_of_estimate/works_template', 'idBR'=>$idBR]);;
					 echo  Html::a('<span class="glyphicon glyphicon-save-file"></span>', $url2,['title' => 'Добавить работы по шаблону',]);
				   ?>
			    </div>
		   </div> 		   	
		   <div class="row">
				<div class="col-sm4">
					<?php  echo $this->render('_search', ['model' => $searchModel, 'idBR'=>$idBR, 'id_node'=>$id_node, 'idEstimateWorkPackages'=>$idEstimateWorkPackages]); ?>				   
			    </div>
		   </div> 
	   </div> 	
    <?php
    //GridView::widget([
        //'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            ////'idWorksOfEstimate',
            ////'idEstimateWorkPackages',
            //'WorkName',
            ////'idWbs',
            ////'WorkDescription',
            ////'deleted',

            //['class' => 'yii\grid\ActionColumn'],
        //],
    //]); 
    ?>
    <div class="works-of-estimate-search">
	 <?php $form1 = ActiveForm::begin([
        'action' => ['index1','idBR'=>$idBR, 'id_node'=>$id_node],
        'method' => 'post',
        'id' =>'w222'
		]); 	
    ?>
    
    <table>
    <?php
    // echo '<pre>'.var_dump($VwListOfWorkEffort).'</pre>';
   
        $id = $VwListOfWorkEffort[0]['idWorksOfEstimate'];
        $i =0;
        echo('<tr><td><b>'.$VwListOfWorkEffort[$i]['WorkName'].'</td><td></td><td></td><td></td></tr>');
        
		foreach($VwListOfWorkEffort as $vlwe){
			
		    if($vlwe['idWorksOfEstimate'] == $id){
				if(isset($vlwe['workEffort'])){
					echo('<tr><td></td><td>'.$vlwe['RoleName'].'</td><td>'
						.$vlwe['fio'].'</td><td>'.$form1->field($vlwe, 'workEffort',
						['inputOptions' => ['name'=>'VwListOfWorkEffort['.$vlwe['idLaborExpenditures'].']']]).
							    '</td></tr>');	
			    }
			}	else{
				 $id = $vlwe['idWorksOfEstimate'];	
				 echo('<tr><td><b>'.$vlwe['WorkName'].'</td><td></td><td></td><td></td></tr>');
				 if(isset($vlwe['workEffort'])){
					 echo('<tr><td></td><td>'.$vlwe['RoleName'].'</td><td>'
					 .$vlwe['fio'].'</td><td>'.$form1->field($vlwe, 'workEffort',
					 ['inputOptions' => ['name'=>'VwListOfWorkEffort['.$vlwe['idLaborExpenditures'].']']]).'</td></tr>');		
				 }	 
			}
			
			
			$i =$i+1;
	        //echo('</td><td colspan="2"><b>'.$vlwe['WorkName'].': </b></td></tr>');
		}
     ?>
    </table>
     <div class="form-group">
       <?php         
         echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) 
        ?>
       
    </div>
    <?php ActiveForm::end(); ?> 
</div>
