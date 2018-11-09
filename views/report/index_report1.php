<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VwReport1Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vw Report1s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vw-report1-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search_report1', ['model' => $searchModel]); ?>

  
    <?php
     //GridView::widget([
        //'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'BRNumber',
            //'BRName',
            //'id',
            //'idBr',
            //'idOrgResponsible',
            ////'name',
            ////'idResultStatus',
            ////'mantis',
            ////'ResultStatusName',
            ////'fio',
            ////'CustomerName',

        //],
    //]); 
    
     
    ?>
    
      <table border = "1" cellpadding="4" cellspacing="2">
		  <?php 
		  
		    foreach($dataProvider as $dp_str){
			  echo '<tr>
			  <td> BR-'.$dp_str->BRNumber.' '.$dp_str->BRName.'</td>
			  <td>'. $dp_str->name. '</td>
			  <td>'. $dp_str->ResultStatusName. '</td>
			  <td>'. $dp_str->fio.'</td>
			  <td>'. $dp_str->CustomerName.'<td>
			  </tr>';	
			  
			}
		  ?>
	  </table>
    
</div>
