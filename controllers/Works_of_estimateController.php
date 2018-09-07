<?php

namespace app\controllers;

use Yii;
use app\models\WorksOfEstimate;
use app\models\SearchWorksOfEstimate;
use app\models\EstimateWorkPackages;
use app\models\VwListOfWorkEffort;
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
     
    public function actionIndex1($id_node,$idBR)
    {
		 echo('index1');
		 echo(var_dump(Yii::$app->request->post())); 
		 die;
	} 
	
	
    public function actionIndex($id_node,$idBR)
    {
        
        		
        $searchModel = new SearchWorksOfEstimate();
        if ($searchModel->load(Yii::$app->request->post())){    //если idEstimateWorkPackages(пакет оценок) был выбран на форме,  то используем его
		   $idEstimateWorkPackages = $searchModel->idEstimateWorkPackages;
		} else{ //иначе ищем любой по этой BR
		   $BREstimateList = EstimateWorkPackages::find()->where(['deleted' => 0, 'idBR'=>$idBR])->one();
		   if(is_null($BREstimateList)){
			 Yii::$app->session->addFlash('error',"Для данной BR нет ни одной оценки трудозатрат. Создайте ее пожалуйста");
			 return $this->redirect(['update','id' => $idBR, 'page_number'=>4]);
		   } 
		   $idEstimateWorkPackages = $BREstimateList->idEstimateWorkPackages;
		}
         
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id_node,$idEstimateWorkPackages);
        
        $VwListOfWorkEffort = VwListOfWorkEffort::find()->where(['idEstimateWorkPackages'=>$idEstimateWorkPackages, 'idWbs'=>$id_node])->orderBy('idWorksOfEstimate')->all();
   
		return $this->render('index', [
				 'idBR'=>$idBR,
                 'id_node'=>$id_node,
                 'idEstimateWorkPackages'=>$idEstimateWorkPackages,  //значение по умолчанию для фильтра
                 'VwListOfWorkEffort'=>$VwListOfWorkEffort,
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
    public function actionCreate($idEstimateWorkPackages,$idWbs,$idBR)
    {
       $modelWOS = new WorksOfEstimate();
	   $modelWOS->idEstimateWorkPackages = $idEstimateWorkPackages;
	   $modelWOS->idWbs = $idWbs;
	   $modelWOS->WorkName = 'Название работы';
	   $modelWOS->WorkDescription = 'Описание работы';
	   $modelWOS->save();
	   if($modelWOS->hasErrors()){
				Yii::$app->session->addFlash('error',"Ошибка сохранения работ ");
				return $this->redirect(['index','id_node'=>$idWbs,'idBR' => $idBR]); 
	   }else{


	   }
	   
    }
    ///**
     //* Creates a new WorksOfEstimate model.
     //* If creation is successful, the browser will be redirected to the 'view' page.
     //* @return mixed
     //* создает новую работу в оценке и передает на форму редактирования
     //*/
    //public function actionCreate_work()
    //{
        //$model = new WorksOfEstimate();

        //if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->idWorksOfEstimate]);
        //}

        //return $this->render('create', [
            //'model' => $model,
        //]);
    //}

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
