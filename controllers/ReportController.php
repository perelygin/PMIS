<?php

namespace app\controllers;
use Yii;
use app\models\VwReport1;
use app\models\VwReport1Search;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ReportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    //сводный отчет по результатам. Предусматривает фильтрацию  по номеру BR, текущему ответственному исполнителю и т.д.
    public function actionReport1()
    {
		
        $searchModel = new VwReport1Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index_report1', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
  
}