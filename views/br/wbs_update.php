<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model app\models\Wbs */
/* @var $form ActiveForm */
?>
<div class="wbs_update">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name') ?>
        
        <?php 
			echo $form->field($model, 'description')->widget(Widget::className(), [
			    'settings' => [
			        'lang' => 'ru',
			        'minHeight' => 200,
			        'plugins' => [
			            
			            'fullscreen',
			        ],
			    ],
			]);
        ?>
    
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- wbs_update -->
