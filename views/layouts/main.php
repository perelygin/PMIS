<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    $items = array(
		['label' => 'Home', 'url' => ['/site/index']],
		['label' => 'О системе', 'url' => ['/site/about']],
		['label' => 'Справка', 'url'=>['/site/help']],
	    //['label' => 'Контакты', 'url' => ['/site/contact']],
	 	);
    
     
    $items[] = ['label' => 'Управление ролями', 'url' => ['/site/rbac']];
	if (Yii::$app->user->can('BRJournalView')) 	$items[] = ['label' => 'Проекты', 'items' => [
					//['label' => 'Перечень проектов', 'url'=>['/pmis/test1']],
					['label' => 'Перечень проектов', 'url'=>['/br/index']],
	            ]];
     
    if (Yii::$app->user->isGuest)	$items[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
	
				
     $items[] = Yii::$app->user->isGuest ? (
                  ['label' => 'Login', 'url' => ['/site/login']]
                ) : (
	                '<li>'
	                . Html::beginForm(['/site/logout'], 'post')
	                . Html::submitButton(
	                    'Logout (' . Yii::$app->user->identity->username . ')',
	                    ['class' => 'btn btn-link logout']
	                )
	                . Html::endForm()
	                . '</li>'
				) ;     
    
    
    
    
    
    //echo Nav::widget([
        //'options' => ['class' => 'navbar-nav navbar-right'],
        //'items' => [
            //['label' => 'Home', 'url' => ['/site/index']],
            //['label' => 'Контакты', 'url' => ['/site/contact']],
            //['label' => 'Справочная информация', 'items' => [
				//['label' => 'О системе', 'url'=>['/site/about']],
				//['label' => 'Справка', 'url'=>['/site/help']],
            //]],
            //['label' => 'Проектная работа', 'items' => [
				//['label' => 'Проекты', 'url'=>['/site/testsoup']],
				//['label' => 'Фазы проекта', 'url'=>['/br/index']],
            //]],
             
            //Yii::$app->user->isGuest ? ['label' => 'Регистрация', 'url' => ['/site/signup']] : ['label' => 'Личный кабинет', 'url' => ['/site/personalcabinet']],
            //Yii::$app->user->isGuest ? (
                  //['label' => 'Login', 'url' => ['/site/login']]
                
            //) : (
                //'<li>'
                //. Html::beginForm(['/site/logout'], 'post')
                //. Html::submitButton(
                    //'Logout (' . Yii::$app->user->identity->username . ')',
                    //['class' => 'btn btn-link logout']
                //)
                //. Html::endForm()
                //. '</li>'
            //)
            
            
          ////  ['label' => 'Регистрация', 'url' => ['/site/signup']]
            
        //],
    //]);
 
     echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
     ]);
 
    
    NavBar::end();

    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $this->render('main/flashes')?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
