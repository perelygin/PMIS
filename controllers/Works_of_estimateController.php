<?php

namespace app\controllers;

use Yii;
use app\models\WorksOfEstimate;
use app\models\SearchWorksOfEstimate;
use app\models\EstimateWorkPackages;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Works_of_estimateController implements the CRUD actions for WorksOfEstimate model.
 */
class Works_of_estimateController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

/**
     //* Lists all WorksOfEstimate models.
     //* @return mixed
     //*/
    //public function actionIndex()
    //{
        //$searchModel = new SearchWorksOfEstimate();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //echo 'жопа';die;
        //return $this->render('index', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
        //]);
    //}
    
    
    
    /**
     * Lists all WorksOfEstimate models.
     * @return mixed
     */
    public function actionIndex($id_node,$idBR,$idEstimateWorkPackages=-1)
    {
		 //пытаемся найти конкретный пакет оценок
		 $BREstimateList = EstimateWorkPackages::find()->where(['deleted' => 0, 'idBR'=>$idBR,'idEstimateWorkPackages'=>$idEstimateWorkPackages])->one();
		 //если конкретный не нашли, то проверка на то, что по даной BR есть хотя бы одна оценка
		 if(!isset($BREstimateList)){
			  $BREstimateList = EstimateWorkPackages::find()->where(['deleted' => 0, 'idBR'=>$idBR])->one();
			   if(is_null($BREstimateList)){
				 Yii::$app->session->addFlash('error',"Для данной BR нет ни одной оценки трудозатрат. Создайте ее пожалуйста");
				 return $this->redirect(['update','id' => $idBR, 'page_number'=>4]);
			   }
	     }	 
		
        $searchModel = new SearchWorksOfEstimate();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id_node,$BREstimateList->idEstimateWorkPackages);
		//$searchModel -> idEstimateWorkPackages = $BREstimateList->idEstimateWorkPackages;
        return $this->render('index', [
				 'idBR'=>$idBR,
                 'id_node'=>$id_node,
                 'idEstimateWorkPackages'=>$BREstimateList->idEstimateWorkPackages,  //значение по умолчанию для фильтра
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorksOfEstimate model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new WorksOfEstimate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WorksOfEstimate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idWorksOfEstimate]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WorksOfEstimate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idWorksOfEstimate]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing WorksOfEstimate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WorksOfEstimate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorksOfEstimate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorksOfEstimate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
