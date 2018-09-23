<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
//use SoapClient;

$this->title = 'ТЕСТ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

  <?php 
  //echo("Тест");
  //phpinfo();
  //$WSDL_POINT = 'http://localhost/mantis/api/soap/mantisconnect.php?wsdl';
  
  //$client = new SoapClient('http://ws.gismeteo.ru/Weather/Weather.asmx?WSDL');
  //$client = new SoapClient('http://192.168.1.147/mantis/api/soap/mantisconnect.php?wsdl');
  $client = new SoapClient('http://192.168.1.147/mantis/api/soap/mantisconnect.php?wsdl');
  
 // $result = $client->mc_version();
 $result = $client->mc_issue_get([
   'username'=>'perelygin',
   'password'=>'141186ptv',
   'issue_id'=>'0000001',
	]);
 //$result = $client->GetSunInfo([
    //'serial' => '...',
    //'townID' => 57,
    //'date' => '2014-01-31',
    //]
//);
  echo var_dump($result);
  ?>

	
</div>
