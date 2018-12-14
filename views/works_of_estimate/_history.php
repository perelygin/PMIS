<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model app\models\WorksOfEstimate */


?>
<div class="works-of-estimate-view">
    <?= GridView::widget([
        'dataProvider' => $LogDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
             'idSystemlog',
            // 'IdTypeObject',
            // 'idObject',
             'SystemLogString',
            // 'IdUser',
             'DataChange' 
        ],
    ]); ?>

    
</div>
