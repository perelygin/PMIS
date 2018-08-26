<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Organization;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VwListOfBRSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

 $Organization = Organization::find()->all();
 $items = ArrayHelper::map($Organization,'CustomerName','CustomerName');


$this->title = 'Выберите человека';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vw-list-of-people-index">
 <?php 
  $idBr = $model_w['idBr'];
  $idRole = $model_w['idRole'];
  $ParentId = $model_w['ParentId'];
  
  //var_dump($model_w);
  //echo($idBr.' '.$idRole.' '.$ParentId);
  ?>
    <h1><?= Html::encode($this->title) ?></h1>
    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
             [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Выбери', 
            'headerOptions' => ['width' => '80'],
            'template' => '{add_user_to_role}',
            //Замыкание в анонимной функции PHP- я нихера не понял как это работает  -(((
            'buttons' => [
                'add_user_to_role' => function ($url,$model) use($idBr,$idRole,$ParentId){  
					 $url = Url::to(['br/add_user_to_role', 'idBr'=>$idBr,'idRole'=>$idRole,'ParentId'=>$ParentId,'idHuman' => $model->idHuman]);
                    return Html::a(
                    '<span class="glyphicon glyphicon-ok"></span>', 
                    $url,['title' => 'Выбери человека']);
                },
                 
                
            ],
        ],
           /*
            * Подход,  с использованием checkBox не подошел потому что для того что бы получитьв контроллере id выбранных записей,  нужно делать ActiveForm. 
            * Для анализа того, была ли нажата кнопка submit(да и любая другая) нужно ставить атрибут name = submit,  а и-за  этолго на кнопку приходится жать дважды
            * 
            */
            //['class' => 'yii\grid\SerialColumn'],
            //['class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => function($dataProvider) {
                //return ['value' => $dataProvider->idHuman];
				//},
			//],
            [
            'attribute'=>'fio',
            'label'=>'ФИО',
            ],
            [
            'attribute'=>'CustomerName',
            'label'=>'Организация',
            'format'=>'text', // Возможные варианты: raw, html
             'filter' => $items
        ],
 
 
        ],
    ]); 
    
    
    ?>
    <?php $form = ActiveForm::begin(); ?>
	<div class="form-group">
        <?php
        //echo  Html::submitButton('Выбрать', ['class' => 'btn btn-success']);
     //   echo  Html::submitButton('Отмена', ['class' => 'btn btn-success','name'=>'submit']);
        echo Button::widget([
				    'label' => 'Отмена',
				    'options' => [
			        'class' => 'btn-success',
					'style' => 'margin:5px',
					//'value'=>'btn_select',
					//'name'=>'submit'
					//'formaction' => 'index.php?r=/br/update'  
					'formaction' => Url::to(['br/update', 'id'=>$idBr,'page_number'=>2])
				    ]
   
			]);
        //echo Button::widget([
				    //'label' => 'Отмена',
				    //'options' => [
			        //'class' => 'btn-primary',
			        //'style' => 'margin:5px',
			        //'value'=>'btn_cancel',
			        //'name'=>'submit'
				    //]
   
			//]);
?>
        </div>
        
    <?php ActiveForm::end(); ?>
     
    
</div>
