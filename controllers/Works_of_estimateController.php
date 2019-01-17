<?php

namespace app\controllers;

use Yii;
use app\models\WorksOfEstimate;
use app\models\SearchWorksOfEstimate;
use app\models\EstimateWorkPackages;
use app\models\VwListOfWorkEffort;
use app\models\WorkEffort;
use app\models\ProjectCommand;
use app\models\Systemlog;
use app\models\Wbs;
use app\models\vw_settings; 
use app\models\BusinessRequests;
use app\models\User;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use SoapClient;

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
			   $BREstimateList = EstimateWorkPackages::find()->where(['deleted' => 0, 'idBR'=>$idBR])->orderBy('dataEstimate DESC')->one();
			   if(is_null($BREstimateList)){
				 Yii::$app->session->addFlash('error',"Для данной BR нет ни одной оценки трудозатрат. Создайте ее пожалуйста");
				 return $this->redirect(['br/update','id' => $idBR, 'page_number'=>4]);
			   } 
			   $idEstimateWorkPackages = $BREstimateList->idEstimateWorkPackages;
		}
		
    
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id_node,$idEstimateWorkPackages);
        
        $VwListOfWorkEffort = VwListOfWorkEffort::find()->where(['idEstimateWorkPackages'=>$idEstimateWorkPackages, 'idWbs'=>$id_node])->all();
   
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
		$ewp = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEstimateWorkPackages]); // Wbs::findOne(['id'=>$parent_node_id]);
		if($ewp->isFinished()){
			Yii::$app->session->addFlash('error',"Оценка трудозатрат закрыта(отправлена в банк). Для корректировки необходимо связаться с менеджером проекта");
				return $this->redirect(['index', 'id_node' => $idWbs ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);
			}
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
        //проверка на то, что оценка трудозатрат не закрыта
        $ewp = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEstimateWorkPackages]); 
		if($ewp->isFinished()){
			Yii::$app->session->addFlash('error',"Оценка трудозатрат закрыта(отправлена в банк). Для корректировки необходимо связаться с менеджером проекта");
				return $this->redirect(['index', 'id_node' => $idWbs ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);
			}
			
        $model = $this->findModel($idWorksOfEstimate);
		$a = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//////
			//  сохраняем трудозатраты в базе 
	        if(isset($a['workEffort']) or isset($a['team_member'])){  
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
				$sql='SELECT Sum(workEffort) as summ FROM WorkEffort  where idWorksOfEstimate = '.$idWorksOfEstimate;
			    $sumWef = Yii::$app->db->createCommand($sql)->queryScalar();
			    $SysLog = new Systemlog();
			    $SysLog->IdTypeObject = 4; 
			    $SysLog->IdUser = Yii::$app->user->getId();;
			    $SysLog->DataChange = date("Y-m-d H:i:s");
			    $SysLog->idObject = $idWorksOfEstimate;
			    $SysLog->SystemLogString = Yii::$app->user->identity->username.' => Трудозатраты по работе '.$idWorksOfEstimate.' изменены: '.$sumWef;
				$SysLog->save();
			} 
	    if(isset($a['btn'])) {   // анализируем нажатые кнопки
				$btn_info = explode("_", $a['btn']);
				
				if($btn_info[0] == 'add') {   // добавление трудозатрат в работу
					
					   $modelWE = new WorkEffort();
				       $modelWE->idWorksOfEstimate = $idWorksOfEstimate;
				       $modelWE->workEffort = 0;
				       $idAnyTeamMember = ProjectCommand::getAnyTeamMember($idBR);
				       if(!is_null($idAnyTeamMember)){
						   $modelWE->idTeamMember = $idAnyTeamMember;
					   } else {
						   Yii::$app->session->addFlash('error',"для данной BR нет ни одного члена команды. Регистрация трудозатрат невозможна ");
						   return $this->redirect(['index','id_node'=>$idWbs,'idBR' => $idBR]);
					   }
					   $modelWE->save();
					   
					   if($modelWE->hasErrors()){
							Yii::$app->session->addFlash('error',"Ошибка регистрации трудозатрат ");
					   }
			
				} 
				elseif($btn_info[0] == 'del'){ //удаление трудозатрат из работы
					   $modelWE = new WorkEffort();
				       $modelWE->findOne($btn_info[1])->delete();    //$idLaborExpenditures  =$btn_info[1] 
				       if($modelWE->hasErrors()){
								Yii::$app->session->addFlash('error',"Ошибка удаления трудозатрат по работе " );
					   }
				  }
				elseif($btn_info[0] == 'save'){  //сохранение формы
					   
						return $this->redirect(['index', 'id_node' => $idWbs ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);			
						}
				elseif($btn_info[0] == 'help'){  //помощь
					    $url1 = Url::to(['site/help']).'#SystemDesc24';
						return $this->redirect($url1);			//
						}
				elseif($btn_info[0] == 'mant'){  //синхронизация с mantis
					$LastEstimateSumm = 0;
					$error_code = 0;
					$error_str = '';
					$User = User::findOne(['id'=>Yii::$app->user->getId()]); 
					
					$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path_create']);   //путь к wsdl тянем из настроек
						if (!is_null($settings)) $url_mantis_cr = $settings->enm_str_value; //путь к мантиссе
						  else $url_mantis_cr = '';
					$settingsDefaultUser = vw_settings::findOne(['Prm_name'=>'Mantis_default_user']);   //пользователь mantis  по умолчанию
						if (!is_null($settingsDefaultUser)) $mntDefaultUser = $settingsDefaultUser->enm_str_value; //имя пользователя
						  else $mntDefaultUser = 'pmis';	  
						  
					if(empty($model->GetMantisNumber())){ //если номер не заполнен, то создаем новый инц в мантисе
						//Yii::$app->session->addFlash('success',"Номер инцидента не указан. Создаем инцидент в mantis");
						//ищем менеджера проекта по Br
						$BR = BusinessRequests::findOne(['idBR'=>$idBR]);
						$pm_login = $BR->get_pm_login();
						$handler = array('name'=>'pmis'); // испольнитель по умолчанию
						
						//ищем аналитика по задаче
						$analit_login = $model->get_analit_login();					
						////определяем тип результата
						$mantis_project = '';
						$wbs = Wbs::findOne(['id'=>$idWbs]); 
						$wbs_info = $wbs->getWbsInfo();
																
						if($wbs_info['idResultType'] == 1){  //тип результата - экспертиза
							$summary = 'BR-'.$wbs_info['BRNumber'].' '.$wbs_info['BRName'].' '.$model->WorkName; 
							$mantis_project = 'VTB24 Согласование экспертиз';
							$category = 'Оценка Экспертного заключения';
							$version = '';
							
							if(!empty($pm_login)){
								$handler = array('name'=>$pm_login);
							}else
								{
									Yii::$app->session->addFlash('success',"В команде нет менеджера проекта или  не указан его логин в mantis. Инцидент назначен на ".$mntDefaultUser);
									$handler = array('name'=>$mntDefaultUser);
									}
								
							//настраиваемые поля
							$custom_fields = array ('ExtRefPart' => array (
													'field' => array (
														'id' => 1 
																),
														'value' => 'BR' 
												              ),
												   'ExtRefNum' => array (
													'field' => array (
														'id' => 2 
																),
														'value' => $wbs_info['BRNumber']
												              ));	
							
						}
						 elseif($wbs_info['idResultType'] == 2 ){ //тип результата - БФТЗ
							 $summary = 'BR-'.$wbs_info['BRNumber'].' '.$wbs_info['BRName'].' '.$model->WorkName;
							 $mantis_project = 'VTB24 SpectrumFront';
							 $category ='Разработка';
							 $custom_fields = array ('ExtRefPart' => array (
																	'field' => array (
																		'id' => 1 
																				),
																		'value' => 'BR' 
																              ),
																   'ExtRefNum' => array (
																	'field' => array (
																		'id' => 2 
																				),
																		'value' => $wbs_info['BRNumber']
																              ),
																    'CR' => array (
																	'field' => array (
																		'id' => 7 
																				),
																		'value' => 'CR'
																              ));
								$version = $wbs_info['version_number_s'];
								//определяем сумму по последней оценке
								$LastEstimateSumm = $BR->getLastEstimateSumm();
								
								if(!empty($pm_login)){
								$handler = array('name'=>$pm_login);
							}else
								{
									Yii::$app->session->addFlash('success',"В команде нет менеджера проекта или  не указан его логин в mantis. Инцидент будет назначен на ".$mntDefaultUser);
									$handler = array('name'=>$mntDefaultUser);
									}
							 }
						 elseif($wbs_info['idResultType'] == 3 or $wbs_info['idResultType'] == 4){ //тип результата - ПО или прочее
							  $summary = $model->WorkName;
							  $mantis_project = 'VTB24 SpectrumFront';
							  $category ='Разработка';
							  $version = $wbs_info['version_number_s'];
							  if(!empty($analit_login)){
								$handler = array('name'=>$analit_login);
							  }else
								{
									//Yii::$app->session->addFlash('error',"В трудозаратах  по работе нет аналитика или  не указан его логин для mantis. Создание инцидента не возможно");
									//$handler = array('name'=>'pmis');
									$error_code = 3;
									$error_str = 'В трудозаратах  по работе нет аналитика или  не указан его логин для mantis. Создание инцидента не возможно';
									}
							  //настраиваемые поля
							  $custom_fields = array ();
							  //$custom_fields = array ('ExtRefPart' => array (
													//'field' => array (
														//'id' => 1 
																//),
														//'value' => 'BR' 
												              //),
												   //'ExtRefNum' => array (
													//'field' => array (
														//'id' => 2 
																//),
														//'value' => $wbs_info['BRNumber']
												              //));		
							  }
							  
	
					//поиск головного инцидента для привязки	
						$relationships ='';															              
						if(isset($a['mantis_link'])) {
							 if(empty($a['mantis_link'])){
								 $error_code = 1;
								 $error_str = 'По выбранной работе не указан инцидент mantis. Привязка не возможна';
			  
								 } else{
									$target_id = (int)$a['mantis_link'];
								 }	
							 	 
							} else{   //головной инцидент не выбран
								if($wbs_info['idResultType'] == 2 or $wbs_info['idResultType'] == 3 or $wbs_info['idResultType'] == 4){   //Для ПО и ТЗ и прочее
								   $error_code = 2;
								   $error_str = 'Не выбран головной инцидент для привязки. Привязка не возможна';
								}
							}	 
					//проверка проекта в мантис		
					if(empty($mantis_project)){
						$error_code = 3;
						$error_str = 'Для типа результата не определен проект mantis';
						}
							  //$username = 'pmis';
							  //$password = '141186ptv';
							  
							 // $username = 'perelygin';
							 // $password = 'gthtksuby';
							  $username = $User->getUserMantisName();
							  $password = $User->getMantisPwd();
							  
							  $client = new SoapClient($url_mantis_cr,    //'http://192.168.20.55/mantisbt-2.3.1/api/soap/mantisconnect.php?wsdl'
							  array('trace'=>1,'exceptions' => 0));
							  
							  
							  if($error_code == 0){
								   $issue = array(
										'project' => array( 'name' => $mantis_project ),
										'category' => $category,
										'severity' => array('id'=>10),//нововведение
										'reproducibility' => array('id'=>100),  //неприменимо
										'summary' =>  $summary, 
										'description' => $model->WorkDescription,
										'custom_fields' => $custom_fields,
										'target_version' => $version,
										'handler' =>$handler,
										'relationships'=>$relationships,
										'sponsorship_total'=>$LastEstimateSumm, 
									);
									 $result =  $client->mc_issue_add($username, $password, $issue);
									 if (is_soap_fault($result)){   //Ошибка
									    Yii::$app->session->addFlash('error',"Ошибка SOAP: (faultcode: ".$result->faultcode.
									    " faultstring: ".$result->faultstring);
									    //"detail".$result->detail);
									
								     }else{  //Сохраняем номер созданного инцидента
											$model->mantisNumber = (string)$result;
											$model->save();
											//делаем привязку к головному инц
											if($wbs_info['idResultType'] == 2 or $wbs_info['idResultType'] == 3 or $wbs_info['idResultType'] == 4){//Для ПО и ТЗ
												$issue_id = (int)$result; 
												$relationship = array (
													'type' => array (
																'id' => 1,
															  ),
													'target_id' => $target_id
												 );
											$relation =  $client->mc_issue_relationship_add($username, $password,$issue_id,$relationship);
											if (is_soap_fault($relation)){   //Ошибка привязки
												Yii::$app->session->addFlash('error',"Ошибка привязки инцидента. Ошибка SOAP: (faultcode: ".$relation->faultcode.
													" faultstring: ".$relation->faultstring);
											} else {Yii::$app->session->addFlash('success','Инциденты '.$issue_id.' и '.$target_id.' связаны');}
											}
											
										}
								} else{
									Yii::$app->session->addFlash('error',$error_str);
									}
							  
							   
					}else{ //Иначе - синхронизируем. т.е. читаем состояние инцидента в мантиссе и вносим измеения в pmis
						Yii::$app->session->addFlash('success',"Указан номер инцидента. Считываем его состояние из mantis ".$model->GetMantisNumber());
						}
					
				}
						
			}
    
        }
        
		$VwListOfWorkEffort = VwListOfWorkEffort::find()->where([
			'idEstimateWorkPackages'=>$idEstimateWorkPackages, 
			'idWbs'=>$idWbs,
			'idWorksOfEstimate'=>$idWorksOfEstimate])->orderBy('idLaborExpenditures')->all();
		
		$QueryLogDataProvider = Systemlog::find()->where(['IdTypeObject' => 4,'idObject' => $idWorksOfEstimate])->orderBy('DataChange'); //лог для работ
        $LogDataProvider = new ActiveDataProvider([
            'query' => $QueryLogDataProvider,
   
        ]);	
			
         return $this->render('update', [
            'model' => $model,
            'VwListOfWorkEffort'=>$VwListOfWorkEffort,
            'LogDataProvider'=>$LogDataProvider,
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
        $ewp = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEstimateWorkPackages]); 
		if($ewp->isFinished()){
			Yii::$app->session->addFlash('error',"Оценка трудозатрат закрыта(отправлена в банк). Для корректировки необходимо связаться с менеджером проекта");
				return $this->redirect(['index', 'id_node' => $idWbs ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);
			}
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
