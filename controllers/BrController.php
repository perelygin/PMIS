<?php

namespace app\controllers;

use Yii;
use app\models\VwListOfBR;
use app\models\VwListOfBRSearch;
use app\models\VwListOfPeopleSearch;
use app\models\BusinessRequests;  
use app\models\RoleModel;
use app\models\ProjectCommand;
use app\models\LifeCycleStages;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use app\models\Wbs;
use app\models\WbsSearch;



/**
 * BrController implements the CRUD actions for VwListOfBR model.
 */
class BrController extends Controller
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
     * Lists all VwListOfBR models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VwListOfBRSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VwListOfBR model.
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
     * Creates a new VwListOfBR model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BusinessRequests();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//создаем роли для BR
			$roleModel = new RoleModel();
			$roles = $roleModel->get_RoleModel($model->BRRoleModelType);
			foreach ($roles as $role) {
				$prjComm = new ProjectCommand();
				$prjComm->parent_id = 0;
				$prjComm->idBR = $model->idBR;
				$prjComm->idRole = $role['idRole'];
				$prjComm->idHuman = -1;
				$prjComm->save();
				if($prjComm->hasErrors()){
					Yii::$app->session->addFlash('error',"Ошибка сохранения по шаблону ролевой модели ");
				}
			}
			//создаем wBS по шаблону(два уровня)
			$LifeCycleStages = new LifeCycleStages();
			$wbs_template = $LifeCycleStages->getLifeCycleStages($model->BRLifeCycleType);
			//корень - номер BR
			$root = new Wbs(['name' => 'BR '.$model->BRNumber,'tree'=>$model->idBR]);
				$root->mantis = 'www.mantis.com';
				$root->idBr = $model->idBR;
				$root->makeRoot();
				$root->save();
				if($root->hasErrors()){
					Yii::$root->session->addFlash('error',"Ошибка сохранения по шаблону WBS. корневой узел ");
					echo('<pre> '.print_r($root->errors).'</pre>'); die;
				}
			foreach($wbs_template as $wbst){
				$stage_l1 = new Wbs(['name' => $wbst['StageName'],'tree'=>$model->idBR]);
				$stage_l1->mantis = 'www.mantis.com';
				$stage_l1->idBr = $model->idBR;
				$stage_l1->appendTo($root);
				$stage_l1->save();
				if($stage_l1->hasErrors()){
					Yii::$app->session->addFlash('error',"Ошибка сохранения по шаблону WBS.  Уровень 1 ");
					echo('<pre> '.print_r($stage_l1->errors).'</pre>'); die;
				}
				foreach($wbst['lvl2'] as $lvl2){ 
						$stage_l2 = new Wbs(['name' => $lvl2['StageName']]);
						$stage_l2->mantis = 'www.mantis.com';
						$stage_l2->idBr = $model->idBR;
						$stage_l2->appendTo($stage_l1);
						$stage_l2->save();
						if($stage_l2->hasErrors()){
							Yii::$app->session->addFlash('error',"Ошибка сохранения по шаблону WBS. Уровень 2 ");
							echo('<pre> '.print_r($stage_l2->errors).'</pre>'); die;
						}
				}
			}
			
			
			
            return $this->redirect(['update','id' => $model->idBR]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing VwListOfBR model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$page_number=1,$root_id = 0)   //id BR и номер активной вкладки
    {
		$model = $this->findModel($id);
		
		if($root_id <> 0){
			$root = Wbs::findOne(['id'=>$root_id]);	
		} else{
			
			$root = $model->findModelWbs($id);
		}
		
		$wbs_leaves_data = $root->children(1)->all();
		$wbs_provider = new ArrayDataProvider([
						    'allModels' => $wbs_leaves_data,
						    //'pagination' => [
						        //'pageSize' => 10,
						    //],
						    'sort' => [
						        'attributes' => ['name'],
						    ],
						]);
		//var_dump($wbs_leaves);die;
		
        $prjComm = new ProjectCommand();
		$prj_comm_model = $prjComm->get_RoleModel($id); //массив с описанием комманды BR
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else{
			 if($model->hasErrors()){
				$ErrorsArray = $model->getErrors(); 	 
				foreach ($ErrorsArray as $key => $value1){
					foreach($value1 as $value2){
							Yii::$app->session->addFlash('error',"Ошибка сохранения. Реквизит ".$key." ".$value2);
					}
				}	
				//echo '<pre>'; print_r($ErrorsArray); die;
			 }
		}

        return $this->render('update', [
            'model' => $model,
            'prj_comm_model'=>$prj_comm_model,
            'page_number' =>$page_number,
            'wbs_leaves'=>$wbs_provider,
            'root_id'=>$root_id  //для wbs
        ]);
    }

    /**
     * Deletes an existing VwListOfBR model.
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
     * Finds the VwListOfBR model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VwListOfBR the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BusinessRequests::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /*
     * добавляет ФЛ в команду проекта с заданной ролью.
     * 
     */
     
    public function actionAdd_user_to_role($idBr,$idRole,$ParentId,$idHuman=0)   
    {

	   $model_w = array('idBr'=>$idBr,'idRole'=>$idRole,'ParentId'=>$ParentId);
       $searchModel = new VwListOfPeopleSearch();
       $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       if($idHuman==0){
		  //выбор человека из списка
         return $this->render('select_human', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model_w'=> $model_w
        ]);
	  }	else{
		  
		$prjComm = new ProjectCommand();
		$prjComm->parent_id = $ParentId;
		$prjComm->idBR = $idBr;
		$prjComm->idRole = $idRole;
		$prjComm->idHuman = $idHuman;
		
		
		
		 if($prjComm->save()){
				
			return $this->redirect(['update','id' => $idBr, 'page_number'=>2]);
		 } 
		 else{
			 if($prjComm->hasErrors()){
				$ErrorsArray = $prjComm->getErrors(); 	 
				foreach ($ErrorsArray as $key => $value1){
					foreach($value1 as $value2){
							Yii::$app->session->addFlash('error',"Ошибка сохранения. Реквизит ".$key." ".$value2);
					}
				}	
				//echo '<pre>'; print_r($ErrorsArray); die;
			 }
			 // если не удалось сохранить  продолжаем выбирать
			return $this->render('select_human', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model_w'=> $model_w
        ]);
		 }
		
	
		
		
	 } 
		
        

    }
        /*
     * добавляет ФЛ в команду проекта с заданной ролью.
     * 
     */
     
    public function actionDelete_user_from_role($idPrjCom,$idBr)   //id - первичный ключ в projectCommand
    {
		$prjComm = new ProjectCommand();
        $prjComm->findOne($idPrjCom)->delete();

        //return $this->redirect(['index']);
        return $this->redirect(['update','id' => $idBr, 'page_number'=>2]);
    }
  }
