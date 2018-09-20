<?php

namespace app\controllers;

use Yii;
use app\models\WorksOfEstimate;
use app\models\SearchWorksOfEstimate;
use app\models\EstimateWorkPackages;
use app\models\VwListOfWorkEffort;
use app\models\WorkEffort;
use app\models\ProjectCommand;
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
	
	
    public function actionIndex($id_node,$idBR,$idEWP=-1)  //$idEWP - идентификатор пакета оценок
    {
        
       	$a = Yii::$app->request->post();
       	$searchModel = new SearchWorksOfEstimate();
       	$idEstimateWorkPackages = $idEWP;
       	
       	//if(isset($a['workEffort']) or isset($a['team_member'])){  //Если на форме нажали кнопку "сохранить" под оценками трудозатрат,  сохраняем трудозатраты в базе и переотображаем форму с тем же пакетом оценок
			////$idEstimateWorkPackages = $idEWP;

			//foreach($a['workEffort'] as $key => $value){
				//$WorkEffort = WorkEffort::findOne($key);
				//$WorkEffort->workEffort = $value;
				//if(!$WorkEffort ->save()) Yii::$app->session->addFlash('error','ошибка сохраненния трудозатарт WorkEffort' );
			//}
			//foreach($a['team_member'] as $key => $value){
				//$WorkEffort = WorkEffort::findOne($key);
				//$WorkEffort->idTeamMember = $value;
				//if(!$WorkEffort ->save()) Yii::$app->session->addFlash('error','ошибка сохраненния членов команды WorkEffort' );
			//}
			////Yii::$app->session->addFlash('error',$idEstimateWorkPackages);
			
		//} 
		if (isset($a['SearchWorksOfEstimate'])){ 
			if ($searchModel->load(Yii::$app->request->post())){    //если idEstimateWorkPackages(пакет оценок) был выбран на форме,  то используем его
				 
			   $idEstimateWorkPackages = $searchModel->idEstimateWorkPackages;
	 
			   
			}
		}	
		 
		if($idEstimateWorkPackages == -1){ //если все еще -1, то ищем любой по этой BR
			   $BREstimateList = EstimateWorkPackages::find()->where(['deleted' => 0, 'idBR'=>$idBR])->one();
			   if(is_null($BREstimateList)){
				 Yii::$app->session->addFlash('error',"Для данной BR нет ни одной оценки трудозатрат. Создайте ее пожалуйста");
				 return $this->redirect(['br/update','id' => $idBR, 'page_number'=>4]);
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
				//return $this->redirect(['index', 'id_node' => $idWbs ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);
				return $this->redirect(['update', 'idWorksOfEstimate'=>$modelWOS->idWorksOfEstimate,'idWbs' => $idWbs ,'idBR' => $idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages]);
	   }
	   
    }
    
    public function actionCreate_workeffort($idWorksOfEstimate,$idBR,$idWbs,$idEstimateWorkPackages)
    {
       //$modelWE = new WorkEffort();
       //$modelWE->idWorksOfEstimate = $idWorksOfEstimate;
       //$modelWE->workEffort = 0;
       //$idAnyTeamMember = ProjectCommand::getAnyTeamMember($idBR);
       //if($idAnyTeamMember != -1){
		   //$modelWE->idTeamMember = $idAnyTeamMember;
	   //} else {
		   //Yii::$app->session->addFlash('error',"для данной BR нет ни одного члена команды. Регистрация трудозатрат невозможна ");
		   //return $this->redirect(['index','id_node'=>$idWbs,'idBR' => $idBR]);
	   //}
       
	   //$modelWE->save();
	   //if($modelWE->hasErrors()){
			//Yii::$app->session->addFlash('error',"Ошибка регистрации трудозатрат ");
	   //}
	   //return $this->redirect(['update','idWorksOfEstimate'=>$idWorksOfEstimate, 'idWbs' => $idWbs ,'idBR' => $idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages]);
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
    public function actionUpdate($idWorksOfEstimate,$idBR,$idWbs,$idEstimateWorkPackages)
    {
        $model = $this->findModel($idWorksOfEstimate);
		
		$a = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//////
			
	        if(isset($a['workEffort']) or isset($a['team_member'])){  //  сохраняем трудозатраты в базе 
				foreach($a['workEffort'] as $key => $value){
					$WorkEffort = WorkEffort::findOne($key);
					$WorkEffort->workEffort = $value;
					if(!$WorkEffort ->save()) Yii::$app->session->addFlash('error','ошибка сохраненния трудозатарт WorkEffort' );
				}
				foreach($a['team_member'] as $key => $value){
					$WorkEffort = WorkEffort::findOne($key);
					$WorkEffort->idTeamMember = $value;
					if(!$WorkEffort ->save()) Yii::$app->session->addFlash('error','ошибка сохраненния членов команды WorkEffort' );
				}
			} 
	    if(isset($a['btn'])) {   // анализируем нажатые кнопки
				$btn_info = explode("_", $a['btn']);
				if($btn_info[0] == 'add') {   // добавление трудозатрат в работу
					   $modelWE = new WorkEffort();
				       $modelWE->idWorksOfEstimate = $idWorksOfEstimate;
				       $modelWE->workEffort = 0;
				       $idAnyTeamMember = ProjectCommand::getAnyTeamMember($idBR);
				       if($idAnyTeamMember != -1){
						   $modelWE->idTeamMember = $idAnyTeamMember;
					   } else {
						   Yii::$app->session->addFlash('error',"для данной BR нет ни одного члена команды. Регистрация трудозатрат невозможна ");
						   return $this->redirect(['index','id_node'=>$idWbs,'idBR' => $idBR]);
					   }
					   $modelWE->save();
					   if($modelWE->hasErrors()){
							Yii::$app->session->addFlash('error',"Ошибка регистрации трудозатрат ");
					   }
			
				} elseif($btn_info[0] == 'del'){ //удаление трудозатрат из работы
					   $modelWE = new WorkEffort();
				       $modelWE->findOne($btn_info[1])->delete();    //$idLaborExpenditures  =$btn_info[1] 
				       if($modelWE->hasErrors()){
								Yii::$app->session->addFlash('error',"Ошибка удаления трудозатрат по работе " );
					   }
					}elseif($btn_info[0] == 'save'){  //сохранение формы
						return $this->redirect(['index', 'id_node' => $idWbs ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);			
						}
			}
			
		
			
            
        }
        
		$VwListOfWorkEffort = VwListOfWorkEffort::find()->where([
			'idEstimateWorkPackages'=>$idEstimateWorkPackages, 
			'idWbs'=>$idWbs,
			'idWorksOfEstimate'=>$idWorksOfEstimate])->orderBy('idLaborExpenditures')->all();
			
         return $this->render('update', [
            'model' => $model,
            'VwListOfWorkEffort'=>$VwListOfWorkEffort,
            'idBR'=>$idBR
        ]);
    }


	public function actionUpdate_workeffort()
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
    public function actionDeletework($idWorksOfEstimate,$idBR,$idWbs,$idEstimateWorkPackages)
    {
        
        $this->findModel($idWorksOfEstimate)->delete();
        return $this->redirect(['index', 'id_node' => $idWbs ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);
    }

/**
 * 
 * Удаление оценки трудозатрат из работы
 */
    public function actionDelete_workeffort($idEstimateWorkPackages,$idWbs,$idBR,$idLaborExpenditures,$idWorksOfEstimate)
    {
       //$modelWE = new WorkEffort();
       //$modelWE->findOne($idLaborExpenditures)->delete();
       
       //if($modelWE->hasErrors()){
				//Yii::$app->session->addFlash('error',"Ошибка удаления трудозатрат по работе " );
				////return $this->redirect(['index','id_node'=>$idWbs,'idBR' => $idBR]); 
	   ////}else{
				////return $this->redirect(['index', 'id_node' => $idWbs ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);
	   //}
	   //return $this->redirect(['update','idWorksOfEstimate'=>$idWorksOfEstimate, 'idWbs' => $idWbs ,'idBR' => $idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages]);
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
