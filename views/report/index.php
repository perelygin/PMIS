<?php
/* @var $this yii\web\View */
  use yii\helpers\Html;
  use yii\helpers\Url;
?>
<h1>Отчеты</h1>

<div class="container">
	   <div class="row">
		  <div class="col-sm-6">
			  <?php echo Html::a('Сводный отчет по статусу результатов', Url::toRoute(['report/report1']),['title' => '',]); ?>
		  </div>
		  <div class="col-sm-6">
			  <?php // echo Html::a('Сетевой график', Url::toRoute(['report/report2']),['title' => '',]); ?>
		  </div>
		</div>			  
</div>

