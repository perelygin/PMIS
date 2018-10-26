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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
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
        Yii::$app->getUser()->setReturnUrl( Yii::$app->getRequest()->getUrl()); ///Запомнили текущую страницу
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
		if (!Yii::$app->user->can('BRCreate')) {   // проверка права на создание 
			Yii::$app->session->addFlash('error',"Нет прав на регистрацию");
			return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
		}
        
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
		 Yii::$app->getUser()->setReturnUrl( Yii::$app->getRequest()->getUrl()); ///Запомнили текущую страницу
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
	   if (!Yii::$app->user->can('BRDelete')) {   // проверка права на создание 
		   Yii::$app->session->addFlash('error',"Нет прав на удаление BR");
		   return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
	   }
		
       $BR = $this->findModel($id);
       $BR->BRDeleted = 1;
       $BR->save();
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
		if($parent_node->isWbsHasWork()){
			Yii::$app->session->addFlash('error',"Для этого результата есть оценка трудозатрат. Создание подчиненного узла не возможно");
			return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
		}
		
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
        /*
    * вывод  оценки трудозатрат для BR в таблицу. группировка по ролям
    */
   public function actionPrint_estimate_work_packages_grouped($idEWP,$idBR){
	   $BR = BusinessRequests::findOne($idBR);
	   $RoleModelType = $BR->get_BRRoleModelType();
	   $sql=   "SELECT 
					rlm.idRole,  
				    rlm.RoleName,
					tmp_tbl2.idWbs,
				    tmp_tbl2.idWorksOfEstimate,
				    tmp_tbl2.WorkName,
				    tmp_tbl2.sumWE
				FROM RoleModel as rlm
				LEFT OUTER JOIN 
				(Select idWbs,idWorksOfEstimate,WorkName,idRole, SUM(workEffort) as sumWE FROM
					(Select 
						wos.idWorksOfEstimate,  
						wos.WorkName,
						wos.idWbs,
						wef.workEffort,
						pc.idRole
					from WorksOfEstimate as wos
					LEFT OUTER JOIN WorkEffort wef ON wos.idWorksOfEstimate = wef.idWorksOfEstimate
					LEFT OUTER JOIN ProjectCommand pc ON wef.idTeamMember = pc.id
					where wos.idWorksOfEstimate =:idwoe) as tmp_tbl
				GROUP BY idRole) tmp_tbl2 ON rlm.idRole = tmp_tbl2.idRole
				where idRoleModelType = ".$RoleModelType
				." order by idRole";
				
		$sql1 = "SELECT 
						wbs.id,
						wbs.tree, 
						wbs.lft,
						wbs.rgt,
						wbs.depth,
						wbs.name,
						wbs.idBr,
						woe.idEstimateWorkPackages,
						woe.idWorksOfEstimate,
						woe.WorkName
						FROM wbs  
						LEFT OUTER JOIN (Select * from WorksOfEstimate where idEstimateWorkPackages= ".$idEWP." ) woe ON woe.idWbs=wbs.id
						where wbs.idBr = ".$idBR." and (woe.idEstimateWorkPackages = ".$idEWP." or isnull(woe.idEstimateWorkPackages)) 
			  		       and (wbs.rgt - wbs.lft) <= 1
						order by id,idWorksOfEstimate";
		$RM = new RoleModel();
		$RoleHeader = $RM->get_RoleModel($RoleModelType);
		$print_WOEs = Yii::$app->db->createCommand($sql1)->queryAll(); 				// выбрали все работы по BR
		$BR  = BusinessRequests::findOne($idBR);
	    $EWP = EstimateWorkPackages::findOne($idEWP); //оценка
		
		
	  $a = Yii::$app->request->post();
	  if(isset($a['btn'])) {   // анализируем нажатые кнопки
		   if($a['btn'] == 'excel'){
			   
		$strhead = '<tr><td></td><td></td>';
	    $strEnd ='';
	    $arraySum =array(); //для подсчета итогов
	    $Alfabet = 'ABCDEFGHIJKLMNOPQR';
	    
			   
			 $ex_row = 2;
			 $spreadsheet = new Spreadsheet();
			 $sheet = $spreadsheet->getActiveSheet();
			 $sheet->getColumnDimension('B')->setWidth(60);   
			  $sheet->getColumnDimension('C')->setWidth(12);  
			  $sheet->getColumnDimension('D')->setWidth(12);  
			  $sheet->getColumnDimension('E')->setWidth(12);  
			  $sheet->getColumnDimension('F')->setWidth(12);  
			  $sheet->getColumnDimension('G')->setWidth(12);  
			  $sheet->getColumnDimension('H')->setWidth(15);  
			  $sheet->getColumnDimension('I')->setWidth(12);  
			 
			 $sheet->setCellValue('A'.$ex_row, 'Оценка трудозатрат по BR-'.$BR->BRNumber.'  "'.$BR->BRName.'"');
			 $ex_row = $ex_row+1;
			 $sheet->setCellValue('A'.$ex_row, 'Пакет оценок: "'.$EWP->EstimateName.'" от '. $EWP->dataEstimate);
			 $ex_row = $ex_row+4;
			 $j=2;
			 $sheet->getRowDimension($ex_row)->setRowHeight(40);
			 foreach($RoleHeader as $rh){
				 $sheet->setCellValue(substr($Alfabet,$j,1).$ex_row,$rh['RoleName']);
				 $sheet->getStyle(substr($Alfabet,$j,1).$ex_row)->getAlignment()->setWrapText(true);
				 $sheet->getStyle(substr($Alfabet,$j,1).$ex_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				 $sheet->getStyle(substr($Alfabet,$j,1).$ex_row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

				 $arraySum[$rh['RoleName']] = 0;
				 $j=$j+1;
			 }
			  $ex_row = $ex_row+1;
			 if(count($print_WOEs)>0){
				 $id = -1;
				 foreach($print_WOEs as $pwoe){
					$print_wef = Yii::$app->db->createCommand($sql)->bindValue(':idwoe',$pwoe['idWorksOfEstimate'])->queryAll(); //выбрали трудозатраты по ролям  для работы
					if($pwoe['id'] == $id){
						$sheet->setCellValue('B'.$ex_row,$pwoe['WorkName']);
						$sheet->getStyle('B'.$ex_row)->getAlignment()->setWrapText(true);
						$sheet->getRowDimension($ex_row)->setRowHeight(-1);
						$j=2;
						foreach($print_wef as $pwef){
							$sheet->setCellValue(substr($Alfabet,$j,1).$ex_row,$pwef['sumWE']);
							$sheet->getStyle(substr($Alfabet,$j,1).$ex_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$sheet->getStyle(substr($Alfabet,$j,1).$ex_row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
							$arraySum[$pwef['RoleName']] = $arraySum[$pwef['RoleName']] + $pwef['sumWE']; //подсчет итогов
							$j=$j+1;
						}
						$ex_row = $ex_row+1;	
					} else{
						$sheet->setCellValue('A'.$ex_row, 'Результат:'.$pwoe['name']);
						$sheet->mergeCells('A'.$ex_row.':B'.$ex_row);
						$sheet->getStyle('A'.$ex_row)->getFont()->setBold(true);
						$ex_row = $ex_row+1;
						$sheet->setCellValue('B'.$ex_row,$pwoe['WorkName']);
						$sheet->getStyle('B'.$ex_row)->getAlignment()->setWrapText(true);
						$sheet->getRowDimension($ex_row)->setRowHeight(-1);
						$j=2;
						foreach($print_wef as $pwef){
							$sheet->setCellValue(substr($Alfabet,$j,1).$ex_row,$pwef['sumWE']);
							$sheet->getStyle(substr($Alfabet,$j,1).$ex_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$sheet->getStyle(substr($Alfabet,$j,1).$ex_row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
							$arraySum[$pwef['RoleName']] = $arraySum[$pwef['RoleName']] + $pwef['sumWE']; //подсчет итогов
							$j=$j+1;
						}
						$ex_row = $ex_row+1;
						$id = $pwoe['id'];
					}	
				}
				
				//итоги
				$totalsumm =0;
				$ex_row = $ex_row+1;
				$j=2;
				$sheet->setCellValue('B'.$ex_row,'Итого');
				$sheet->getStyle('B'.$ex_row)->getFont()->setBold(true);
				
				foreach($arraySum as $as => $v){
					$sheet->setCellValue(substr($Alfabet,$j,1).$ex_row,$v);
					$j=$j+1;
					$totalsumm = $totalsumm + $v;
				}
				$ex_row = $ex_row+1;
				$sheet->setCellValue('B'.$ex_row,'Дополнительное тестирование (10% от общих трудозатрат)');
				$sheet->getStyle('B'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension($ex_row)->setRowHeight(-1);
				$j=2;
				foreach($arraySum as $as => $v){
					if($as == 'Инженер по тестированию'){
						$sheet->setCellValue(substr($Alfabet,$j,1).$ex_row,$totalsumm/10);
						}
					$j=$j+1;
				}
				$ex_row = $ex_row+1;
				$j=2;
				
				$sheet->setCellValue('B'.$ex_row,'Итого с учетом доп. затрат');
				$sheet->getStyle('B'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension($ex_row)->setRowHeight(-1);
				$sheet->getStyle('B'.$ex_row)->getFont()->setBold(true);
				
				foreach($arraySum as $as => $v){
					if($as == 'Инженер по тестированию'){
						$sheet->setCellValue(substr($Alfabet,$j,1).$ex_row,$totalsumm/10+$v);
						$sheet->getStyle(substr($Alfabet,$j,1).$ex_row)->getFont()->setBold(true);
					} else{
						$sheet->setCellValue(substr($Alfabet,$j,1).$ex_row,$v);
						$sheet->getStyle(substr($Alfabet,$j,1).$ex_row)->getFont()->setBold(true);
					}
					$j=$j+1;
					
				}
				$ex_row = $ex_row+3;
				foreach($arraySum as $ars => $v){
					  if($ars=='Инженер по тестированию' ){  //инженер по тестированию
						  
						   	$sheet->setCellValue('B'.$ex_row,$ars);
							$sheet->setCellValue('C'.$ex_row,$totalsumm/10+$v);
							$ex_row = $ex_row+1; 
					  } else {
							$sheet->setCellValue('B'.$ex_row,$ars);
							$sheet->setCellValue('C'.$ex_row,$v);
							$ex_row = $ex_row+1;
						
					  }
				}
				$ex_row = $ex_row+2;
				$sheet->setCellValue('B'.$ex_row,'Итого');
				$sheet->setCellValue('C'.$ex_row,$totalsumm+$totalsumm/10);
				$sheet->getStyle('B'.$ex_row.':C'.$ex_row)->getFont()->setBold(true);
				
				$styleThinBlackBorderOutline = [
				    'borders' => [
					    'allBorders' => [
		                    'borderStyle' => Border::BORDER_THIN,
		                ],
				        //'outline' => [
				            //'borderStyle' => Border::BORDER_THIN,
				            //'color' => ['argb' => 'FF000000'],
				        //],
				    ],
				];
				$sheet->getStyle('A4:E10')->applyFromArray($styleThinBlackBorderOutline);
			 }
			$writer = new Xlsx($spreadsheet);
			$writer->save('hwd2.xlsx');
	        return Yii::$app->response->sendFile('/var/www/html/pmis_app/web/hwd2.xlsx')->send();  
		   }
	  }  
  	  return $this->render('PrintEstimateWorkPackagesGroup', [
	        'print_WOEs' => $print_WOEs ,
	        //'Print_wbs_sum'=>$Print_wbs_sum,
	        'BR'=>$BR,
	        'EWP'=>$EWP,
	        'sql'=>$sql,
	        'RoleHeader'=>$RoleHeader
	    ]); 
			
   }
      /*
    * вывод  оценки трудозатрат для BR в таблицу
    */
   public function actionPrint_estimate_work_packages($idEWP,$idBR){
	$sql1 = "SELECT 
		sum(wef.workEffort) as summ,
		rlm.RoleName,
		rlm.idRole
		FROM Yii2pmis.wbs  
		LEFT OUTER JOIN WorksOfEstimate woe ON woe.idWbs=wbs.id
		LEFT OUTER JOIN WorkEffort wef ON wef.idWorksOfEstimate=woe.idWorksOfEstimate
		LEFT OUTER JOIN ProjectCommand prc ON prc.id = wef.idTeamMember
		LEFT OUTER JOIN People ppl ON ppl.idHuman = prc.idHuman
		LEFT OUTER JOIN RoleModel rlm ON rlm.idRole = prc.idRole
		LEFT OUTER JOIN ResultStatus rst ON rst.idResultStatus = wbs.idResultStatus
		where wbs.idBr = ".$idBR." and woe.idEstimateWorkPackages =".$idEWP."
		group by rlm.idRole
		order by rlm.idRole";
		
			$sql = "SELECT 
						wbs.id,
						wbs.tree, 
						wbs.lft,
						wbs.rgt,
						wbs.depth,
						wbs.name,
						wbs.idBr,
						wbs.idOrgResponsible,
						woe.idEstimateWorkPackages,
						woe.idWorksOfEstimate,
						woe.WorkName,
						wef.workEffort,
						wef.idLaborExpenditures,
						rst.ResultStatusName,
						concat(rlm.RoleName,' ',ppl.Family,' ',ppl.Name) as fio
						FROM Yii2pmis.wbs  
						LEFT OUTER JOIN (Select * from WorksOfEstimate where idEstimateWorkPackages=".$idEWP.") woe ON woe.idWbs=wbs.id
						LEFT OUTER JOIN WorkEffort wef ON wef.idWorksOfEstimate=woe.idWorksOfEstimate
						LEFT OUTER JOIN ProjectCommand prc ON prc.id = wef.idTeamMember
						LEFT OUTER JOIN People ppl ON ppl.idHuman = prc.idHuman
						LEFT OUTER JOIN RoleModel rlm ON rlm.idRole = prc.idRole
						LEFT OUTER JOIN ResultStatus rst ON rst.idResultStatus = wbs.idResultStatus
			  		    where wbs.idBr = ".$idBR." and (woe.idEstimateWorkPackages =".$idEWP." or isnull(woe.idEstimateWorkPackages)) 
			  		       and (wbs.rgt - wbs.lft) <= 1
						order by lft,idWorksOfEstimate,idLaborExpenditures  ";
	  $Print_wbs = Yii::$app->db->createCommand($sql)->queryAll(); 
	  $Print_wbs_sum = Yii::$app->db->createCommand($sql1)->queryAll(); 
	  $BR = BusinessRequests::findOne($idBR);
	  $EWP= EstimateWorkPackages::findOne($idEWP); //оценка
	  
	  $a = Yii::$app->request->post();
	  if(isset($a['btn'])) {   // анализируем нажатые кнопки
		   if($a['btn'] == 'excel'){
			    $ex_row = 2;
				$spreadsheet = new Spreadsheet();
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->getColumnDimension('C')->setWidth(60);
				
				
						if(count($Print_wbs)>0){
							$id = $Print_wbs[0]['id'];
							$idWOf=(is_null($Print_wbs[0]['idWorksOfEstimate'])) ? 0 : $Print_wbs[0]['idWorksOfEstimate'];
							$sheet->setCellValue('A'.$ex_row, 'Оценка трудозатрат по BR-'.$BR->BRNumber.'  "'.$BR->BRName.'"');
							$ex_row = $ex_row+1;
							$sheet->setCellValue('A'.$ex_row, 'Пакет оценок: "'.$EWP->EstimateName.'" от '. $EWP->dataEstimate);
							$ex_row = $ex_row+4;
							
							$sheet->setCellValue('A'.$ex_row, 'Результат:'.$Print_wbs[0]['name']);
							$sheet->getStyle('A'.$ex_row)->getFont()->setBold(true);
							$sheet->mergeCells('A'.$ex_row.':C'.$ex_row);
							$sheet->getStyle('A'.$ex_row)->getAlignment()->setWrapText(true);
							$sheet->getRowDimension($ex_row)->setRowHeight(-1);
							
							$ex_row = $ex_row+1;
							$sheet->setCellValue('B'.$ex_row, 'Работа: '.$Print_wbs[0]['WorkName']);
							$sheet->mergeCells('B'.$ex_row.':C'.$ex_row);
							$sheet->getStyle('B'.$ex_row)->getAlignment()->setWrapText(true);
							$ex_row = $ex_row+1;
							foreach($Print_wbs as $pwbs){
								
								if($pwbs['id'] == $id  and $pwbs['idWorksOfEstimate']==$idWOf){
									$sheet->setCellValue('C'.$ex_row, $pwbs['fio'].' (ч.д.)');
									$sheet->getStyle('C'.$ex_row)->getAlignment()->setWrapText(true);
									$sheet->getRowDimension($ex_row)->setRowHeight(-1);
									$sheet->setCellValue('D'.$ex_row, $pwbs['workEffort']);
									$ex_row=$ex_row+1;
									//echo '<tr><td>  &nbsp&nbsp</td><td>&nbsp&nbsp&nbsp&nbsp</td><td>'.$pwbs['fio'].'</td><td>'.$pwbs['workEffort'].'</td></tr>';
								} elseif($pwbs['id'] == $id  and $pwbs['idWorksOfEstimate']<>$idWOf){
									//$idWOf=$pwbs['idWorksOfEstimate'];
									$idWOf=(is_null($pwbs['idWorksOfEstimate'])) ? 0 : $pwbs['idWorksOfEstimate'];
									$sheet->setCellValue('B'.$ex_row, 'Работа: '.$pwbs['WorkName']);
									$sheet->mergeCells('B'.$ex_row.':C'.$ex_row);
									$sheet->getStyle('B'.$ex_row)->getAlignment()->setWrapText(true);
									$sheet->getRowDimension($ex_row)->setRowHeight(-1);
									
									$sheet->setCellValue('C'.($ex_row+1), $pwbs['fio'].' (ч.д.)');
									$sheet->getStyle('C'.($ex_row+1))->getAlignment()->setWrapText(true);
									$sheet->getRowDimension($ex_row+1)->setRowHeight(-1);
									$sheet->setCellValue('D'.($ex_row+1), $pwbs['workEffort']);
									$ex_row=$ex_row+2;
									//echo '<tr><td>  &nbsp&nbsp</td><td colspan =3> <i> Работа: '.$pwbs['WorkName'].'</i></td></tr>';
									//echo '<tr><td width = "5%" >  &nbsp&nbsp</td><td></td><td>'.$pwbs['fio'].'</td><td width = "5%">'.$pwbs['workEffort'].'</td></tr>';
								} elseif($pwbs['id'] <> $id  and $pwbs['idWorksOfEstimate']==$idWOf){   // выводим результат с незаполненными работами.  все работы = null
									$id = $pwbs['id']; 
									$idWOf=(is_null($pwbs['idWorksOfEstimate'])) ? 0 : $pwbs['idWorksOfEstimate'];
									$sheet->setCellValue('A'.$ex_row, 'Результат:'.$pwbs['name']);
									$sheet->getStyle('A'.$ex_row)->getFont()->setBold(true);
									$sheet->mergeCells('A'.$ex_row.':C'.$ex_row);
									$sheet->getStyle('A'.$ex_row)->getAlignment()->setWrapText(true);
									$sheet->getRowDimension($ex_row)->setRowHeight(-1);
									
									$sheet->setCellValue('B'.($ex_row+1), 'Работа: '.$pwbs['WorkName']);
									$sheet->mergeCells('B'.($ex_row+1).':C'.($ex_row+1));
									$sheet->getStyle('B'.($ex_row+1))->getAlignment()->setWrapText(true);
									$sheet->getRowDimension($ex_row+1)->setRowHeight(-1);
									
									$sheet->setCellValue('C'.($ex_row+2), $pwbs['fio'].' (ч.д.)');
									$sheet->getStyle('C'.($ex_row+2))->getAlignment()->setWrapText(true);
									$sheet->getRowDimension($ex_row+2)->setRowHeight(-1);
									$sheet->setCellValue('D'.($ex_row+2), $pwbs['workEffort']);
									$ex_row=$ex_row+3;
								} elseif($pwbs['id'] <> $id  and $pwbs['idWorksOfEstimate']<>$idWOf){
									$id = $pwbs['id'];
									//$idWOf=$pwbs['idWorksOfEstimate'];
									$idWOf=(is_null($pwbs['idWorksOfEstimate'])) ? 0 : $pwbs['idWorksOfEstimate'];
									$sheet->setCellValue('A'.$ex_row, 'Результат:'.$pwbs['name']);
									$sheet->getStyle('A'.$ex_row)->getFont()->setBold(true);
									$sheet->mergeCells('A'.$ex_row.':C'.$ex_row);
									$sheet->getStyle('A'.$ex_row)->getAlignment()->setWrapText(true);
									$sheet->getRowDimension($ex_row)->setRowHeight(-1);
									
									$sheet->setCellValue('B'.($ex_row+1), 'Работа: '.$pwbs['WorkName']);
									$sheet->mergeCells('B'.($ex_row+1).':C'.($ex_row+1));
									$sheet->getStyle('B'.($ex_row+1))->getAlignment()->setWrapText(true);
									$sheet->getRowDimension($ex_row+1)->setRowHeight(-1);
									
									$sheet->setCellValue('C'.($ex_row+2), $pwbs['fio'].' (ч.д.)');
									$sheet->getStyle('C'.($ex_row+2))->getAlignment()->setWrapText(true);
									$sheet->getRowDimension($ex_row+2)->setRowHeight(-1);
									$sheet->setCellValue('D'.($ex_row+2), $pwbs['workEffort']);
									$ex_row=$ex_row+3;
									//echo '<tr><td colspan =4> <b>Результат: '.$pwbs['name'].'</b></td></tr>';
									//echo '<tr><td>  &nbsp&nbsp</td><td colspan =3><i>Работа: '.$pwbs['WorkName'].'</i> </td></tr>';
									//echo '<tr><td width = "5%">  &nbsp&nbsp</td><td>&nbsp&nbsp&nbsp&nbsp</td><td>'.$pwbs['fio'].'</td><td>'.$pwbs['workEffort'].'</td></tr>';
								}
							}
						}
						//$ex_row=$ex_row+5;
						//$total =0;
						//if(count($Print_wbs_sum)>0){
							
						//foreach($Print_wbs_sum as $pwbss){
						  //if($pwbss['idRole'] ==6 ){  //инженер по тестированию
							    //$test = $pwbss['summ'];
							    //$test10 = $test*0.1;
							    //$test10p=$test+$test10;
								//$total =$total + $test10p;
							//} else {
								//$total =$total +$pwbss['summ'];
							//}
							    //$sheet->setCellValue('C'.$ex_row, $pwbss['RoleName']);
								//$sheet->setCellValue('D'.$ex_row, $pwbss['summ']);
								//$ex_row=$ex_row+1;
						//}
					//}
					//$sheet->setCellValue('C'.$ex_row, 'Итого');
					//$sheet->setCellValue('D'.$ex_row, $total);
					//echo('<tr><td><b>Итого</b></td><td><b>'.$total.'</b></td></tr>');
							
							
				
				$writer = new Xlsx($spreadsheet);
				$writer->save('hwd1.xlsx');
					
		        return Yii::$app->response->sendFile('/var/www/html/pmis_app/web/hwd1.xlsx')->send();  
		   }
	  }	   
	  
	  
	  return $this->render('PrintEstimateWorkPackages', [
		        'Print_wbs' => $Print_wbs,
		        'Print_wbs_sum'=>$Print_wbs_sum,
		        'BR'=>$BR,
		        'EWP'=>$EWP,
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
	  // print_r($idAnyTeamMember);  die;
	   if(is_null($idAnyTeamMember)){
		   Yii::$app->session->addFlash('error',"Ошибка создания. Нет ни одного члена команды");
			 return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
		   }
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
