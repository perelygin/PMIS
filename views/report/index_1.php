<?php
/* @var $this yii\web\View */
?>
<h1>Отчеты</h1>

<p>
    $this->params['breadcrumbs_wbs'][] = ['label' => $prn['name'], 'url' => Url::toRoute(['br/update', 'id' =>$model->idBR, 'page_number' => 3, 'root_id'=>$prn['id']])];
</p>
