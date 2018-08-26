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
  		$item3 = array('label' => 'Структура работ','content' => $this->render('_form_part3', ['model' => $model, 'form' => $form]));
	  	switch ($page_number) { //оперделяем активную вкладку
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
  		echo Tabs::widget([
  		  	'items' => $items
		    //'items' => [
		        //[
		            //'label' => 'Общая информация',
		            //'content' => $this->render('_form_part1', ['model' => $model, 'form' => $form]),
		            //'active' => true
		        //],
		        //[
		            //'label' => 'Команда',
		            //'content' => $this->render('_form_part2', ['model' => $model,'prj_comm_model'=>$prj_comm_model, 'form' => $form]),
		            ////'active' => true
		        //],
		        //[
		            //'label' => 'Структура работ',
		            //'content' => '<h2> В работе может быть сразу несколько пакетов. Поэтому говорить о текущем статусе BR неправильно</h2>',
		            //'headerOptions' => [
		                //'id' => 'headerOptions'
		            //],
		            //'options' => [
		                //'id' => 'options'
		            //]
		        //],
		        //[
		            //'label' => 'Бюджет',
		            //'content' => '<h2>Вы можете добавить любое количество табов. Просто опишите их структуру в массиве.</h2>'
		        //],
		        //[
		            //'label' => 'Выпадающий список табов',
		            //'items' => [
		                //[
		                    //'label' => 'Первый таб из выпадающего списка',
		                    //'content' => '<h2>Обновите свои познания в Yii 2 and Twitter Bootstrap. Все возможнсти уже обернуты в удобные интерфейсы.</h2>'
		                //],
		                //[
		                    //'label' => 'Второй таб из выпадающего списка',
		                    //'content' => '<h2>Один в поле не воин, а двое - уже компания.</h2>'
		                //],
		                //[
		                    //'label' => 'Это третий таб из выпадающего списка',
		                    //'content' => '<h2>Третий не лишний!</h2>'
		                //]
		            //]
		        //]
		    //]
		]);
		?>
		<div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
