<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<p>настройка ролей выполняется скриптом по нажатию кнопки</p>
<p>НЕ запускать при зарегистрированных пользователях</p>

<?php $form = ActiveForm::begin() ?>
<?= $form->field($model, 'name') ?>

<div class="form-group">
 <div>
 <?= Html::submitButton('RBAC init', ['class' => 'btn btn-success']) ?>
 </div>
</div>
<?php ActiveForm::end() ?>
