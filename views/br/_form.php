<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\VwListOfBR */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vw-list-of-br-form">


    <?php $form = ActiveForm::begin(); ?>

  		<?php 
  		
  		$item1 = array('label' => 'Общая информация','content' => $this->render('_form_part1', ['model' => $model, 'form' => $form]));
  		$item2 = array('label' => 'Команда','content' => $this->render('_form_part2', ['model' => $model,'prj_comm_model'=>$prj_comm_model, 'form' => $form]),);
  		$item3 = array('label' => 'Структура работ','content' => $this->render('_form_part3', ['root_id'=>$root_id,'wbs_leaves'=>$wbs_leaves, 'model' => $model, 'form' => $form]));
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
  		//var_dump($items);die;
  		echo Tabs::widget([		  	'items' => $items			]);
		?>
		<div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
