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
use app\models\vw_ResultEvents;
use app\models\ResultEvents;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use app\models\Wbs;
use app\models\WbsSearch;
use app\models\EstimateWorkPackages;
use app\models\WorksOfEstimate;
use SoapClient;


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
		
		
		$QueryEstimateList = EstimateWorkPackages::find()->where(['deleted' => 0,'idBR' => $id]);
        $EstimateListdataProvider = new ActiveDataProvider([
            'query' => $QueryEstimateList,
   
        ]);
		
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
            'root_id'=>$root->id,  //для wbs
            'EstimateListdataProvider' => $EstimateListdataProvider, //для трудозатрат
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
     /*
     * добавляет новый узел в WBS 
     * 
     */
     
    public function actionAdd_wbs_child($idBR, $parent_node_id)   //
    {
		$parent_node = Wbs::findOne(['id'=>$parent_node_id]);
		$new_child = new Wbs(['name' => 'новый узел']);
		$new_child->mantis = 'www.mantis.com';
		$new_child->idBr = $idBR;
		$new_child->appendTo($parent_node);
		$new_child->save();
		if($new_child->hasErrors()){
			Yii::$app->session->addFlash('error',"Ошибка сохранения дочернего узла WBS ");
			//echo('<pre> '.print_r($new_child->errors).'</pre>'); die;
		}
        return $this->redirect(['update_wbs_node','idBR' => $idBR, 'id_node'=>$new_child->id]);
        
    }
    /**
     * Удаляет узел,  если у него нет подчиненных узлов
     * 
     */
    public function actionDelete_wbs_node($id_node,$idBR)
    {
		$Node = Wbs::findOne(['id'=>$id_node]);
        $children = $Node->children()->all();
       // var_dump($children);die;
		if(!empty($children)){
			Yii::$app->session->addFlash('error',"Не могу удалить узел - есть подчиненные узлы ");
			return $this->redirect(['update','id' => $idBR, 'page_number'=>3, 'root_id'=>$id_node]);
		}else{
			$parent = $Node->parents(1)->one();
			$deleted_node_name =$Node->name;
			$Number_of_deleted = $Node->deleteWithChildren();
			Yii::$app->session->addFlash('success',"Узел (".$deleted_node_name.") удален");
			return $this->redirect(['update','id' => $idBR, 'page_number'=>3, 'root_id'=>$parent->id]);
		}
   }
    public function actionUpdate_wbs_node($id_node,$idBR)
    {
		//if ($model = Wbs::findOne(['id'=>$id_node]) !== null) {
		    $a = Yii::$app->request->post();
		    //$request = Yii::$app->getRequest();
		    Yii::$app->getUser()->setReturnUrl( Yii::$app->getRequest()->getUrl()); ///Запомнили текущую страницу
		   
			$model = Wbs::findOne(['id'=>$id_node]);
			if(!is_null($model)){
				if ($model->load(Yii::$app->request->post()) && $model->validate()) {
		            $model->save();
		            $parent = $model->parents(1)->one();
		            
		            if(isset($a['btn'])) {   // анализируем нажатые кнопки
						$btn_info = explode("_", $a['btn']);
						if($btn_info[0] == 'crtm') {   // создание инцидента mantis
							//$WSDL_POINT = 'http://192.168.1.147/mantis/api/soap/mantisconnect.php?wsdl';
							//$client = new nusoap_client($WSDL_POINT, false);
							
							
							
							  $username = 'perelygin';
							  $password = '141186ptv';
							  $issue_id = 1;
							  $client = new SoapClient('http://172.16.2.135/mantis/api/soap/mantisconnect.php?wsdl', array('trace'=>1,'exceptions' => 0));
							  $result =  $client->mc_issue_get($username, $password, $issue_id);
							  if (is_soap_fault($result)){
								  Yii::$app->session->addFlash('error',"Ошибка SOAP: (faultcode: ".$result->faultcode." faultstring: ".$result->faultstring);
								//trigger_error("Ошибка SOAP: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
							   }
							   //echo($result->project->id.'  '.$result->project->name );
							   //echo('<br>');
							   
							   //echo "<pre>".print_r($result)."</pre>"; die;
							   
							   $issue = array(
									'project' => array( 'name' => 'ВТБ' ),
									'category' => 'General',
									'summary' => 'Sample Summary ' . time(),
									'description' => 'Sample Description ' . time(),
								);
								 $result =  $client->mc_issue_add($username, $password, $issue);
								 if (is_soap_fault($result)){
								  Yii::$app->session->addFlash('error',"Ошибка SOAP: (faultcode: ".$result->faultcode." faultstring: ".$result->faultstring);
								
							   }
							   print_r($result); die;
							//try{
								 //$client = new SoapClient('http://172.16.2.135/mantis/api/soap/mantisconnect.php?wsdl');
								 ////if (is_soap_fault($result)){
										////echo 'gиздец';  die;
									 ////}
								////var_dump($result); die;
								//}
								//catch(SoapFault $fault){
									//trigger_error("Ошибка SOAP: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
								//}

							//return $this->render('wbs_update', [   'model' => $model,   ]);
						}
						if($btn_info[0] == 'addevent') {   //Добавить событие для результата
						
							return  $this->redirect(['create_result_event','idWbs' => $id_node]);
						}		
						if($btn_info[0] == 'del') {   //Удалить событие для результата
							$modelRE = ResultEvents::findOne($btn_info[1]); //...->delete();    //$idLaborExpenditures  =$btn_info[1] (int)
							if(!is_null($modelRE)){
								
								$modelRE->deleted = 1;
								$modelRE->save();
								if($modelRE->hasErrors()){
									Yii::$app->session->addFlash('error',"Ошибка удаления события " );
								}
							}
							
						}
						if($btn_info[0] == 'edit') {   //изменить событие для результата
							
							return  $this->redirect(['update_result_event','idResultEvents' => $btn_info[1]]);
							
						}
						if($btn_info[0] == 'save') {   //Добавить событие для результата
							return  $this->redirect(['update','id' => $idBR, 'page_number'=>3, 'root_id'=>$parent->id]);
						}	
		            }
		            // получаем список  событий для результата
		           
		            
		            
		            
		            //
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
		    
			}
			$events = vw_ResultEvents::find()->where(['deleted' => 0, 'idwbs'=>$id_node])->all();
		    return $this->render('wbs_update', [
		        'model' => $model,
		        'events'=>$events,
		    ]);
   }
   /*
    * Создает оценку трудозатрат для BR
    */
   public function actionCreate_ewp($idBR){
	   $EstimateWorkPackages =  new EstimateWorkPackages();
	   $EstimateWorkPackages->EstimateName = 'Предварительная оценка';
	   $EstimateWorkPackages->idBR = $idBR;
	   $EstimateWorkPackages->dataEstimate = date("Y-m-d");   //'2018-09-10'
	   $EstimateWorkPackages->save();
	   if($EstimateWorkPackages->hasErrors()){
			Yii::$app->session->addFlash('error',"Ошибка сохранения оценки работ ");
			return 	$this->redirect(['update','id' => $idBR, 'page_number'=>4]); 		
	   }else{
		    return $this->redirect(['update_estimate_work_packages','idBR' => $idBR, 'idEWP'=>$EstimateWorkPackages->idEstimateWorkPackages]); 
	   }
	   
	  
   }
   
   /*
    * Удаляет оценку трудозатрат для BR
    */
   public function actionDelete_ewp($idEWP){
	   $EstimateWorkPackages =  EstimateWorkPackages::findOne($idEWP);
	   if(!is_null($EstimateWorkPackages)){
		   $EstimateWorkPackages->deleted = 1;
		   $EstimateWorkPackages-> save();
		    if($EstimateWorkPackages->hasErrors()){
					Yii::$app->session->addFlash('error',"Ошибка сохранения оценки работ ");
			}		
		   }
	   return 	$this->redirect(['update','id' => $EstimateWorkPackages->idBR, 'page_number'=>4]);   
	  
   }
     
   
    /*
    * корректировка оценки трудозатрат для BR
    */
   public function actionUpdate_estimate_work_packages($idEWP,$idBR){
	   $EstimateWorkPackages =  EstimateWorkPackages::findOne($idEWP);
	  	if(!is_null($EstimateWorkPackages)){
				if ($EstimateWorkPackages->load(Yii::$app->request->post()) && $EstimateWorkPackages->validate()) {
		            $EstimateWorkPackages->save();
		          if($EstimateWorkPackages->hasErrors()){
					$ErrorsArray = $EstimateWorkPackages->getErrors(); 	 
					foreach ($ErrorsArray as $key => $value1){
						foreach($value1 as $value2){
							   
								Yii::$app->session->addFlash('error',"Ошибка сохранения. Реквизит ".$key." ".$value2);
						}
					}
					echo var_dump($ErrorsArray); die;
					
				  }
		            return  $this->redirect(['update','id' => $idBR, 'page_number'=>4]);
		        } 		    
			}
	
		    return $this->render('UpdateEstimateWorkPackages', [
		        'model' => $EstimateWorkPackages,
		    ]);
	   
	  
   }
   
   public function actionUpdate_result_event($idResultEvents){
	   $model = ResultEvents::findOne($idResultEvents);
	   
	   if ($model->load(Yii::$app->request->post()) && $model->validate()) {
		   $model->save();
		   return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
		           
		}            
	   return $this->render('Edit_result_event', [
		        'model' => $model,
		    ]);
   }
   
   /*
    * регистрация события по результату
    *///
   
   public function actionCreate_result_event($idWbs){
	   $WBSInfo = Wbs::findOne(['id'=>$idWbs])->getWbsInfo();
	   $idAnyTeamMember = ProjectCommand::getAnyTeamMember($WBSInfo['idBr']);
	   $model = new ResultEvents();
	   $model->idwbs = $idWbs;
	   $model->ResultEventsName = 'Новое событие';
	   $model->ResultEventsDate = date("Y-m-d H:i:s");
	   $model->ResultEventResponsible = $idAnyTeamMember;
	   if ($model->validate()){
		   $model->save();
		   return  $this->redirect(['update_result_event','idResultEvents' => $model->idResultEvents]);	            
		} else {
			Yii::$app->session->addFlash('error',"Ошибка создания. ");
			return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
		}   
	  
   }
   /*
    //* отображает перечень работ, которые необходимо выполнить что бы получить данный результат, и трудозатраты на них
    //*/
    //public function actionShow_estimates($id_node,$idBR,$idEstimateWorkPackages = -1){
	 ////пытаемся найти конкретный пакет оценок
	 //$BREstimateList = EstimateWorkPackages::find()->where(['deleted' => 0, 'idBR'=>$idBR,'idEstimateWorkPackages'=>$idEstimateWorkPackages])->one();
	 ////если конкретный не нашли, то проверка на то, что по даной BR есть хотя бы одна оценка
	 //if(!isset($BREstimateList)){
		  //$BREstimateList = EstimateWorkPackages::find()->where(['deleted' => 0, 'idBR'=>$idBR])->one();
		   //if(is_null($BREstimateList)){
			 //Yii::$app->session->addFlash('error',"Для данной BR нет ни одной оценки трудозатрат. Создайте ее пожалуйста");
			 //return $this->redirect(['update','id' => $idBR, 'page_number'=>4]);
		   //}
     //}	 
         ////выводим форму с перечнем работ, необходимых для достижения результата.  на форме,  в обязательном порядке указывается пакет оценок.
		 //$WorksOfEstimate =  new WorksOfEstimate();
		 //$QueryWorksOfEstimate = $WorksOfEstimate->find()->where(['deleted' => 0, 'idWbs'=>$id_node, 'idEstimateWorkPackages'=>$idEstimateWorkPackages]);
	        //$WorksOfEstimateProvider = new ActiveDataProvider([
	            //'query' => $QueryWorksOfEstimate,
	   
	        //]);
       //return $this->render('Show_estimates', [
            //'idBR' => $idBR,
            //'id_node'=>$id_node,
            //'idEstimateWorkPackages'=>$idEstimateWorkPackages,
            //'WorksOfEstimateProvider' => $WorksOfEstimateProvider,
            
        //]);  
   
		  
        
	//}
  }
