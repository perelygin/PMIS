<?php
/* @var $this yii\web\View */
  use yii\helpers\Html;
  use yii\helpers\Url;
?>
<h1>Отчеты</h1>

<p>
    <?php
       echo Html::a('Сводный отчет по статусу результатов', Url::toRoute(['report/report1']),['title' => '',]);
    ?>
</p>
