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
use app\models\MoveWorksToAnotherResultForm;
use app\models\People;
use app\models\AddWorkEffortForm;
use app\models\select_Work_search;
use app\models\Links;
use app\models\Schedule;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
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
       	$related_issue = array(); //массив для связанных инцидентов
       	//Считывание настроек
		$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path_create']);   //путь к wsdl тянем из настроек
						if (!is_null($settings)) $url_mantis_cr = $settings->enm_str_value; //путь к мантиссе
						  else $url_mantis_cr = '';
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
		
	    if(isset($a['btn'])) {   // анализируем нажатые кнопки
			
			$btn_info = explode("_", $a['btn']);
			if($btn_info[0] == 'mnt1') { //получить список связанных инцидентов
				
				//получаем номер выбранного инцидента
			    $relationships ='';	
			    $error_code = 99;	
			    $issue_id = 0;													  
				if(isset($a['mantis_link'])) {
					 if(empty($a['mantis_link'])){
						 $error_code = 1;
						 $error_str = 'По выбранной работе не указан инцидент mantis. Привязка невозможна';
	  
						 } else{
							$issue_id = (int)$a['mantis_link'];
							
						 }	
					 	 
					} else{   //головной инцидент не выбран
						   $error_code = 2;
						   $error_str = 'Не выбран головной инцидент для привязки. Привязка невозможна';
					}	
				//пытаемся получить информацию по инциденту. В том числе и связанные
				if(!empty($issue_id)){
				    //wsdl клиент
					$User = User::findOne(['id'=>Yii::$app->user->getId()]); 
					$username = $User->getUserMantisName();
					$password = $User->getMantisPwd();
					$client = new SoapClient($url_mantis_cr,array('trace'=>1,'exceptions' => 0));
					$result =  $client->mc_issue_get($username, $password, $issue_id);
					if (is_soap_fault($result)){   //Ошибка
									    Yii::$app->session->addFlash('error',"Ошибка получения информации из mantis SOAP: (faultcode: ".$result->faultcode.
									    " faultstring: ".$result->faultstring);
									    //"detail".$result->detail);
									
					}else{
						//делаем массив с перечнем привязанных инцидентов
						$related_issue=array();
						foreach ($result->relationships as $rel){
							//echo ('<br>'. $rel->target_id);
							$result_issue = $client->mc_issue_get($username, $password, $rel->target_id); 	
							if(is_soap_fault($result_issue)){   //Ошибка
							    Yii::$app->session->addFlash('error',"Ошибка получения информации из mantis SOAP: (faultcode: ".$result->faultcode.
							    " faultstring: ".$result_issue->faultstring);
							}else{
								
								$related_issue[$rel->target_id] = array('mantisNumber'=>$rel->target_id,'name'=>$result_issue->summary,'handler'=>$result_issue->handler->name);
							}
						}
						//var_dump($related_issue);die;
					}
					
				}	
			
				//Yii::$app->session->addFlash('error',"ИИИха ".$issue_id." ".$error_code );
			}
			if($btn_info[0] == 'mnt2') {   //создаем работы по выбранным инцдентам
					if(isset($a['relatedissue'])) {
						foreach($a['relatedissue'] as $r){
							echo('<Br>'.$r);
							}
						 die;
						}
				//Yii::$app->session->addFlash('error',"ИИИха ");
			}
		}	 
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
				'related_issue'	=> $related_issue
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
       $result = $modelWOS->addWork($idEstimateWorkPackages,$idWbs,'Название работы','Описание работы');
       if($result<0){
				Yii::$app->session->addFlash('error',"Ошибка сохранения работ ");
				return $this->redirect(['index','id_node'=>$idWbs,'idBR' => $idBR]); 
	   }else{
				return $this->redirect(['update', 'idWorksOfEstimate'=>$modelWOS->idWorksOfEstimate,'idWbs' => $idWbs ,'idBR' => $idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages]);
	   }
	   
	   //$modelWOS->idEstimateWorkPackages = $idEstimateWorkPackages;
	   //$modelWOS->idWbs = $idWbs;
	   //$modelWOS->WorkName = 'Название работы';
	   //$modelWOS->WorkDescription = 'Описание работы';
	   //$modelWOS->save();
	   //if($modelWOS->hasErrors()){
				//Yii::$app->session->addFlash('error',"Ошибка сохранения работ ");
				//return $this->redirect(['index','id_node'=>$idWbs,'idBR' => $idBR]); 
	   //}else{
				////return $this->redirect(['index', 'id_node' => $idWbs ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);
				//return $this->redirect(['update', 'idWorksOfEstimate'=>$modelWOS->idWorksOfEstimate,'idWbs' => $idWbs ,'idBR' => $idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages]);
	   //}
	   
    }
    
    //public function actionCreate_workeffort($idWorksOfEstimate,$idBR,$idWbs,$idEstimateWorkPackages)
    //{
       ////$modelWE = new WorkEffort();
       ////$modelWE->idWorksOfEstimate = $idWorksOfEstimate;
       ////$modelWE->workEffort = 0;
       ////$idAnyTeamMember = ProjectCommand::getAnyTeamMember($idBR);
       ////if($idAnyTeamMember != -1){
		   ////$modelWE->idTeamMember = $idAnyTeamMember;
	   ////} else {
		   ////Yii::$app->session->addFlash('error',"для данной BR нет ни одного члена команды. Регистрация трудозатрат невозможна ");
		   ////return $this->redirect(['index','id_node'=>$idWbs,'idBR' => $idBR]);
	   ////}
       
	   ////$modelWE->save();
	   ////if($modelWE->hasErrors()){
			////Yii::$app->session->addFlash('error',"Ошибка регистрации трудозатрат ");
	   ////}
	   ////return $this->redirect(['update','idWorksOfEstimate'=>$idWorksOfEstimate, 'idWbs' => $idWbs ,'idBR' => $idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages]);
    //}    
    
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
     
     /*
      * 
      *Добавляем трудозатраты по работе
      * 
      */
    public function actionEdit_work_effort($idBR,$idWbs,$idWorksOfEstimate,$idEstimateWorkPackages,$idLaborExpenditures,$idTeamMember=0){
		
		$modelWE = WorkEffort::findOne($idLaborExpenditures);
		if ($idTeamMember == 0){ 
			$mark = 0;
		} else {
			$mark = 1;
			$modelWE->idTeamMember = $idTeamMember;
		}
		// $model = new AddWorkEffortForm();
		$a = Yii::$app->request->post();
		if ($modelWE->load(Yii::$app->request->post())){
			//echo $modelWE->idTeamMember; die;
			 if(isset($a['btn'])) {   // анализируем нажатые кнопки
				$btn_info = explode("_", $a['btn']);
				if($btn_info[0] == 'add1') {  //кнопка Далее
					$this->redirect(['edit_work_effort','idBR'=>$idBR,
														'idWbs'=>$idWbs,
														'idWorksOfEstimate'=>$idWorksOfEstimate,
														'idEstimateWorkPackages'=>$idEstimateWorkPackages,
														'idLaborExpenditures'=>$idLaborExpenditures,
														'idTeamMember'=>$modelWE->idTeamMember]);
				}
				if($btn_info[0] == 'cancel') {  //кнопка отмена
					$this->redirect(['update', 'idWorksOfEstimate'=>$idWorksOfEstimate,'idWbs' => $idWbs ,'idBR' => $idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages]);
				}
				if($btn_info[0] == 'add2') {  //кнопка Сохранить
					if($modelWE->save()){
						   $this->redirect(['update', 'idWorksOfEstimate'=>$idWorksOfEstimate,'idWbs' => $idWbs ,'idBR' => $idBR, 'idEstimateWorkPackages'=>$idEstimateWorkPackages]);
						}
		   
							Yii::$app->session->addFlash('error',"Ошибка регистрации трудозатрат ");
				}
			 }	
		}
		
	    
	    return $this->render('editWorkEffort', [
				 'model' =>$modelWE,
				 'idBR'=>$idBR,
                 'idWbs'=>$idWbs,
                 'idWorksOfEstimate'=>$idWorksOfEstimate, 
                 'idEstimateWorkPackages'=>$idEstimateWorkPackages,
                 'mark'=>$mark
        ]);
		
	}
	
	/*
	 * редактирование информации о связи между работами
	 * тип связи, задержка
	 * 
	 */ 
	public function actionEdit_link($idWorksOfEstimate,$idBR,$idWbs,$idEstimateWorkPackages,$idLink){
		$model = Links::findOne(['idLink'=>$idLink]); 
		$a = Yii::$app->request->post();   
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if(isset($a['btn'])) {   // анализируем нажатые кнопки
				$btn_info = explode("_", $a['btn']);
				if($btn_info[0] == 'cancl') {   // отмена
					return $this->redirect(['update','idWorksOfEstimate'=>$idWorksOfEstimate,'idBR'=>$idBR,'idWbs'=>$idWbs,'idEstimateWorkPackages'=>$idEstimateWorkPackages,'page_number'=>3]);		
				}		
			}

			return $this->redirect(['update','idWorksOfEstimate'=>$idWorksOfEstimate,'idBR'=>$idBR,'idWbs'=>$idWbs,'idEstimateWorkPackages'=>$idEstimateWorkPackages,'page_number'=>3]);
		}
			
		 return $this->render('edit_link', [
			'model' => $model,
            'idWbs'=>$idWbs,
            'idEstimateWorkPackages'=>$idEstimateWorkPackages
         ]);
			
		}
		 
		 
    public function actionUpdate($idWorksOfEstimate,$idBR,$idWbs,$idEstimateWorkPackages,$page_number=1)
    {
        //проверка авторизации
        if (Yii::$app->user->isGuest){
			Yii::$app->session->addFlash('error',"Необходимо авторизоваться в системе");
			 return $this->goHome();
		}
        //проверка на то, что оценка трудозатрат не закрыта
        $ewp = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEstimateWorkPackages]); 
		if($ewp->isFinished()){
			Yii::$app->session->addFlash('error',"Оценка трудозатрат закрыта(отправлена в банк). Для корректировки необходимо связаться с менеджером проекта");
				return $this->redirect(['index', 'id_node' => $idWbs ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);
			}
		//Считывание настроек
		$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path_create']);   //путь к wsdl тянем из настроек
						if (!is_null($settings)) $url_mantis_cr = $settings->enm_str_value; //путь к мантиссе
						  else $url_mantis_cr = '';
		$settingsDefaultUser = vw_settings::findOne(['Prm_name'=>'Mantis_default_user']);   //пользователь mantis  по умолчанию
						if (!is_null($settingsDefaultUser)) $mntDefaultUser = $settingsDefaultUser->enm_str_value; //имя пользователя
						  else $mntDefaultUser = 'pmis';
		//$settingsMntPrjLst = vw_settings::findOne(['Prm_name'=>'Mantis_projects_list']);   //Перечень проектов мантис
						//if (!is_null($settingsMntPrjLst)){
							 //$MntPrjLstArray = explode(';',$settingsMntPrjLst->enm_str_value); 
						//}	 
						  //else $MntPrjLstArray = array();
		
		//
		$wbs = Wbs::findOne(['id'=>$idWbs]); 
		$wbs_info = $wbs->getWbsInfo();  				  
		$model = $this->findModel($idWorksOfEstimate);
		//wsdl клиент
			$User = User::findOne(['id'=>Yii::$app->user->getId()]); 
			$username = $User->getUserMantisName();
			$password = $User->getMantisPwd();
			
		//список проектов мантис,  который нам нужен только для результов  типа "ПО" и если не заполнен номер инцидента, в противном случа - нефиг дергать сервис
		$MntPrjLstArray =  array();
		if(empty($model->mantisNumber) and ($wbs_info['idResultType'] == 2 or $wbs_info['idResultType'] == 3 or $wbs_info['idResultType'] == 4)){
			$client = new SoapClient($url_mantis_cr,array('trace'=>1,'exceptions' => 0)); 	
			$result_1 =  $client->mc_projects_get_user_accessible($username, $password);
				 if (is_soap_fault($result_1)){   //Ошибка
				    Yii::$app->session->addFlash('error',"Ошибка связи с mantis SOAP: (faultcode: ".$result_1->faultcode.
				    " faultstring: ".$result_1->faultstring);
				  // и вываливаемся
				  return $this->redirect(['index', 'id_node' => $idWbs ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);			
				
			     }else{
					 foreach($result_1 as $rs){
						  if($rs->id == 12 or $rs->id == 22 or $rs->id == 17 or $rs->id == 13 or $rs->id == 26 or $rs->id == 21){
							  $mntprjArr = array('name' =>$rs->name,'Checked' =>' ');
							  $MntPrjLstArray[$rs->id] = $mntprjArr;
						  }
						 } 
						 // проставляем признак выбранности
						   if($wbs_info['idResultType'] == 3 or $wbs_info['idResultType'] == 4 or $wbs_info['idResultType'] == 2){
							   //SpectrumFront
							    $name = $MntPrjLstArray['12']['name'];
								$MntPrjLstArray['12']  = array('name'=>$name,'Checked' =>'checked');
							}
							if($wbs_info['idResultType'] == 1){
							   //VTB24 Согласование экспертиз
							    $name = $MntPrjLstArray['17']['name'];
								$MntPrjLstArray['17']  = array('name'=>$name,'Checked' =>'checked');
							}
					   //print_r($MntPrjLstArray);
					   //die;
					 }
			//[13]  VTB24 SpectrumAdmin 
			//[12]  VTB24 SpectrumFront 
			//[22]  VTB24 SpectrumTrs24 
			//[17]  VTB24 Согласование экспертиз 
			//[26]  VTB тестирование
			//[21]  VTB24 Согласование методик тестирования
		}
		
		
       
		$a = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//////
			//  сохраняем трудозатраты в базе 
	        if(isset($a['workEffort']) or isset($a['team_member']) or isset($a['workEffortHour'])){  
				foreach($a['workEffort'] as $key => $value){
					$WorkEffort = WorkEffort::findOne($key);
					$WorkEffort->workEffort = $value;
					if(!$WorkEffort ->save()){
						 if($WorkEffort->hasErrors()){
							$ErrorsArray = $WorkEffort->getErrors(); 	 
							foreach ($ErrorsArray as $key => $value1){
								foreach($value1 as $value2){
										Yii::$app->session->addFlash('error',"Ошибка сохранения трудозатарт WorkEffort. Реквизит ".$key." ".$value2);
								}
							}	
						}	
					}	 
				}
				foreach($a['workEffortHour'] as $key => $value){
					$WorkEffort = WorkEffort::findOne($key);
					$WorkEffort->workEffortHour = $value;
					if(!$WorkEffort ->save()) Yii::$app->session->addFlash('error','ошибка сохраненния трудозатарт workEffortHour' );
				}
				//foreach($a['team_member'] as $key => $value){
					//$WorkEffort = WorkEffort::findOne($key);
					//$WorkEffort->idTeamMember = $value;
					//if(!$WorkEffort ->save()) Yii::$app->session->addFlash('error','ошибка сохраненния членов команды WorkEffort' );
				//}
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
				       $modelWE->workEffortHour = 0;
				       $idAnyTeamMember = ProjectCommand::getAnyTeamMember($idBR);
				       if(!is_null($idAnyTeamMember)){
						   $modelWE->idTeamMember = $idAnyTeamMember;  //любой член команды
						   //любой тип услуги для роли этого человека
						   $idServTeamMember = ProjectCommand::getAnyServTeamMember($idAnyTeamMember);
						   if(!is_null($idServTeamMember)){
							   $modelWE->idServiceType = $idServTeamMember;
							   }else{
								    Yii::$app->session->addFlash('error',"для выбранного члена команды нет ни одной услуги. Регистрация трудозатрат невозможна ");
									return $this->redirect(['index','id_node'=>$idWbs,'idBR' => $idBR]);
								   }
					   } else {
						   Yii::$app->session->addFlash('error',"для данной BR нет ни одного члена команды. Регистрация трудозатрат невозможна ");
						   return $this->redirect(['index','id_node'=>$idWbs,'idBR' => $idBR]);
					   }
					   $modelWE->save();
					   
					   if($modelWE->hasErrors()){
							Yii::$app->session->addFlash('error',"Ошибка регистрации трудозатрат ");
					   } else{
						    $page_number = 1;
							$idLaborExpenditures  = $modelWE->idLaborExpenditures; 
							$this->redirect(['edit_work_effort','idBR'=>$idBR,'idWbs'=>$idWbs,'idWorksOfEstimate'=>$idWorksOfEstimate,'idEstimateWorkPackages'=>$idEstimateWorkPackages,'idLaborExpenditures'=>$idLaborExpenditures]);	
						}
				} 
				elseif($btn_info[0] == 'del'){ //удаление трудозатрат из работы
					   $modelWE = new WorkEffort();
				       $modelWE->findOne($btn_info[1])->delete();    //$idLaborExpenditures  =$btn_info[1] 
				       if($modelWE->hasErrors()){
								Yii::$app->session->addFlash('error',"Ошибка удаления трудозатрат по работе " );
					   }
					   $page_number = 1;
				  }
				elseif($btn_info[0] == 'edit'){ //Изменение трудозатрат из работы
					   $idLaborExpenditures  =$btn_info[1]; 
					   $this->redirect(['edit_work_effort','idBR'=>$idBR,'idWbs'=>$idWbs,'idWorksOfEstimate'=>$idWorksOfEstimate,'idEstimateWorkPackages'=>$idEstimateWorkPackages,'idLaborExpenditures'=>$idLaborExpenditures]);
				       $page_number = 1;
				  }  
				elseif($btn_info[0] == 'save'){  //сохранение формы
					   
						return $this->redirect(['index', 'id_node' => $idWbs ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);			
						}
				elseif($btn_info[0] == 'help'){  //помощь
					    $url1 = Url::to(['site/help']).'#SystemDesc24';
						return $this->redirect($url1);			//
						}
				elseif($btn_info[0] == 'addpw'){  //добавление работы-предшественика
						return $this->redirect(['add_work_prev','idWbs'=>$idWbs, 'idBR' => $idBR, 'idWOS'=>$idWorksOfEstimate, 'idEWP'=>$idEstimateWorkPackages]);	
							
						}	
				elseif($btn_info[0] == 'dellnk'){  //удаление связи с работы-предшественика
					   $lnk = new Links();
				       $lnk->findOne($btn_info[1])->delete();    //$idLaborExpenditures  =$btn_info[1] 
				       if($lnk->hasErrors()){
								Yii::$app->session->addFlash('error',"Ошибка удаления связи " );
					   }
					   $page_number = 3;
						//return $this->redirect(['add_work_prev','idWbs'=>$idWbs, 'idBR' => $idBR, 'idWOS'=>$idWorksOfEstimate, 'idEWP'=>$idEstimateWorkPackages]);	
						}	
				elseif($btn_info[0] == 'editlnk'){  //Изменнеи связи с работы-предшественика
			   
						return $this->redirect(['edit_link','idWbs'=>$idWbs, 'idBR' => $idBR, 'idWorksOfEstimate'=>$idWorksOfEstimate, 'idEstimateWorkPackages'=>$idEstimateWorkPackages,'idLink'=>$btn_info[1]]);	
						}			
				elseif($btn_info[0] == 'mant'){  //синхронизация с mantis
					$LastEstimateSumm = 0;
					$error_code = 0;
					$error_str = '';
					
						  
					if(empty($model->GetMantisNumber())){ //если номер не заполнен, то создаем новый инц в мантисе
						//Yii::$app->session->addFlash('success',"Номер инцидента не указан. Создаем инцидент в mantis");
						//ищем менеджера проекта по Br
						$BR = BusinessRequests::findOne(['idBR'=>$idBR]);
						$pm_login = $BR->get_pm_login();
						$handler = array('name'=>'pmis'); // испольнитель по умолчанию
						
						//ищем аналитика по задаче
						//$analit_login = $model->get_analit_login();					
						////определяем тип результата
						$mantis_project = '';
																
						if($wbs_info['idResultType'] == 1){  //тип результата - экспертиза
							$view_state = array('name'=>'public');  //видимость
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
							 $view_state = array('name'=>'public');
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
							  $view_state = array('name'=>'private');
							  $summary = $model->WorkName;
							  $mantis_project = 'VTB24 SpectrumFront';
							  $category ='Разработка';
							  $version = $wbs_info['version_number_s'];
							  $handler = array('name'=>$username);
							  //if(!empty($analit_login)){
								//$handler = array('name'=>$analit_login);
							  //}else
								//{
									////Yii::$app->session->addFlash('error',"В трудозаратах  по работе нет аналитика или  не указан его логин для mantis. Создание инцидента невозможно");
									////$handler = array('name'=>'pmis');
									//$error_code = 3;
									//$error_str = 'В трудозаратах  по работе нет аналитика или  не указан его логин для mantis. Создание инцидента невозможно';
									//}
							  //настраиваемые поля
							  
							  $custom_fields = array ('CodevTT_Type' => array (
													'field' => array (
														'id' => 23 
																),
														'value' => 'Bug' 
												              ),
												   'CodevTT_Manager EffortEstim' => array (
													'field' => array (
														'id' => 24 
																),
														'value' => 0
												              ),
												    'CodevTT_EffortEstim' => array (
													'field' => array (
														'id' => 22 
																),
														'value' => 1
												              )
												              );		
							  }
						elseif($wbs_info['idResultType'] == 5 ){ //тип результата - абонемент 
							 $view_state = array('name'=>'public');
							 $summary = 'BR-'.$wbs_info['BRNumber'].' Абонемент '.$wbs_info['BRName'].' '.$model->WorkName;
							 $mantis_project = 'VTB24 SpectrumFront';
							 $category ='Разработка:Абонемент';
							 $version = $wbs_info['version_number_s'];
							 $handler = array('name'=>$username);
							 $custom_fields = array ('CodevTT_Type' => array (
													'field' => array (
														'id' => 23 
																),
														'value' => 'Bug' 
												              ),
												   'CodevTT_Manager EffortEstim' => array (
													'field' => array (
														'id' => 24 
																),
														'value' => 0
												              ),
												    'CodevTT_EffortEstim' => array (
													'field' => array (
														'id' => 22 
																),
														'value' => 1
												              )
												              );
						}		  
					  elseif($wbs_info['idResultType'] == 6 ){ //тип результата - внутрений тест
							  $view_state = array('name'=>'private');
							  $summary ='BR-'.$wbs_info['BRNumber'].' Тестирование в составе версии. '. $model->WorkName;
							  $mantis_project = 'VTB тестирование';
							  $category ='Разработка';
							  $version = $wbs_info['version_number_s'];
							  $handler = array('name'=>$username);
							  $custom_fields = array();
							  }
					elseif($wbs_info['idResultType'] == 7 ){ //тип результата - МТ банка
							  $view_state = array('name'=>'private');
							  $summary = 'BR-'.$wbs_info['BRNumber'].' Согласование МТ банка. '.$model->WorkName;
							  $mantis_project = 'VTB24 Согласование методик тестирования';
							  $category ='Разработка';
							  $version = $wbs_info['version_number_s'];
							  $handler = array('name'=>$username);
							  $custom_fields = array();
							  }		  
					//поиск головного инцидента для привязки	
						$relationships ='';															              
						if(isset($a['mantis_link'])) {
							//Yii::$app->session->addFlash('error',"Онок ".$a['mantis_link']);
							 if(empty($a['mantis_link'])){
								 $error_code = 1;
								 $error_str = 'По выбранной работе не указан инцидент mantis. Привязка невозможна';
			  
								 } else{
									$target_id = (int)$a['mantis_link'];
								 }	
							 	 
							} else{   //головной инцидент не выбран
								if($wbs_info['idResultType'] == 2 or $wbs_info['idResultType'] == 3 or $wbs_info['idResultType'] == 4 or $wbs_info['idResultType'] == 5){   //Для ПО и ТЗ и прочее
								   $error_code = 2;
								   $error_str = 'Не выбран головной инцидент для привязки. Привязка невозможна';
								}
							}	
					//выбор проекта мантис
						if(isset($a['mantis_prj'])) {
							$mantis_project = $MntPrjLstArray[$a['mantis_prj']]['name'];
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
										'view_state' => $view_state  
									);
									 $client = new SoapClient($url_mantis_cr,array('trace'=>1,'exceptions' => 0)); 
									 $result =  $client->mc_issue_add($username, $password, $issue);
									 if (is_soap_fault($result)){   //Ошибка
									    Yii::$app->session->addFlash('error',"Ошибка SOAP: (faultcode: ".$result->faultcode.
									    " faultstring: ".$result->faultstring);
									    //"detail".$result->detail);
									
								     }else{  //Сохраняем номер созданного инцидента
											$model->mantisNumber = (string)$result;
											$model->save();
											//делаем привязку к головному инц
											if($wbs_info['idResultType'] == 2 or $wbs_info['idResultType'] == 3 
																			  or $wbs_info['idResultType'] == 4 
																			  or $wbs_info['idResultType'] == 5
																			  or $wbs_info['idResultType'] == 6
																			  or $wbs_info['idResultType'] == 7){//Для ПО и ТЗ и абонемента
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
					$page_number = 2;
					
				}
						
			}
    
        }
        
		$VwListOfWorkEffort = VwListOfWorkEffort::find()->where([
			'idEstimateWorkPackages'=>$idEstimateWorkPackages, 
			'idWbs'=>$idWbs,
			'idWorksOfEstimate'=>$idWorksOfEstimate])->orderBy('idLaborExpenditures')->all();
		
		$ListPrevWorks = Links::getPrevWorks($idWorksOfEstimate);
		$Workdates = Schedule::getWorkdates($idWorksOfEstimate);
		
		$QueryLogDataProvider = Systemlog::find()->where(['IdTypeObject' => 4,'idObject' => $idWorksOfEstimate])->orderBy('DataChange'); //лог для работ
        $LogDataProvider = new ActiveDataProvider([
            'query' => $QueryLogDataProvider,
   
        ]);	
			
         return $this->render('update', [
			'page_number' =>$page_number,
            'model' => $model,
            'VwListOfWorkEffort'=>$VwListOfWorkEffort,
            'LogDataProvider'=>$LogDataProvider,
            'idBR'=>$idBR,
            'MantisPrjLstArray' =>$MntPrjLstArray,
            'ListPrevWorks'=>$ListPrevWorks,
            'Workdates' =>$Workdates
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
 * 
 * Cоздать работы на основе инцидентов mantis
 */
    public function actionTake_works_from_mantis($idEstimateWorkPackages,$idWbs,$idBR)
    {
       $related_issue = array(); //массив для связанных инцидентов
       $missingMembers=array();  //перечень логинов,  которые не принадлежат членам команды;
       $BR = BusinessRequests::findOne($idBR);
      
       
       //Считывание настроек
		$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path_create']);   //путь к wsdl тянем из настроек
						if (!is_null($settings)) $url_mantis_cr = $settings->enm_str_value; //путь к мантиссе
						  else $url_mantis_cr = '';
	 //wsdl клиент
					$User = User::findOne(['id'=>Yii::$app->user->getId()]); 
					$username = $User->getUserMantisName();
					$password = $User->getMantisPwd();
					$client = new SoapClient($url_mantis_cr,array('trace'=>1,'exceptions' => 0));					  
       $a = Yii::$app->request->post();
 		if (!empty($a)) {
			if(isset($a['btn'])) {   // анализируем нажатые кнопки
				$btn_info = explode("_", $a['btn']);
				if($btn_info[0] == 'mnt1') { //получить список связанных инцидентов
									//получаем номер выбранного инцидента
			    $relationships ='';	
			    $error_code = 99;	
			    $issue_id = 0;													  
				if(isset($a['mantis_link'])) {
					 if(empty($a['mantis_link'])){
						 $error_code = 1;
						 $error_str = 'не указан инцидент mantis для получения связанных инцидентов.';
	  
						 } else{
							$issue_id = (int)$a['mantis_link'];
							
						 }	
					 	 
					} else{   //головной инцидент не выбран
						   $error_code = 2;
						   $error_str = 'Не выбран головной инцидент для получения связанных инцидентов.';
					}	
				//пытаемся получить информацию по инциденту. В том числе и связанные
				if(!empty($issue_id)){
				   	$result =  $client->mc_issue_get($username, $password, $issue_id);
					if (is_soap_fault($result)){   //Ошибка
									    Yii::$app->session->addFlash('error',"Ошибка получения информации из mantis SOAP: (faultcode: ".$result->faultcode.
									    " faultstring: ".$result->faultstring);
									    //"detail".$result->detail);
									
					}else{
						//делаем массив с перечнем привязанных инцидентов
						$related_issue=array();
						$missingMembers=array();
						$CommandMembersLogins = ArrayHelper::getColumn($BR->getCMembersLogins(), 'mantis_login');//массив логинов членов команды
						//print_r($ids); die;
						$WBS = Wbs::findOne($idWbs);
						foreach ($result->relationships as $rel){
							
							$result_issue = $client->mc_issue_get($username, $password, $rel->target_id); 	
							if(is_soap_fault($result_issue)){   //Ошибка
							    Yii::$app->session->addFlash('error',"Ошибка получения информации из mantis SOAP: (faultcode: ".$result->faultcode.
							    " faultstring: ".$result_issue->faultstring);
							}else{
								// проверить,  что по данному результату еще нет работ с таким номером инцидента мантис
							  if(!$WBS->isWbsHasMantisNumber($idEstimateWorkPackages,$rel->target_id)){
								if($result_issue->project->id != 17){//связанный инцидент не принадлежить проекту 17(согласование экспертиз)
									$related_issue[$rel->target_id] = array('mantisNumber'=>$rel->target_id,
																		'name'=>$result_issue->summary,
																		'handler'=>$result_issue->handler->name,
																		'project' =>$result_issue->project->name,
																		);
									//формируем перечень логинов,  которые не принадлежат членам команды									
									if(!ArrayHelper::isIn($result_issue->handler->name, $CommandMembersLogins) // логина нет в  массиве с членами комманды
										and !ArrayHelper::isIn($result_issue->handler->name, ArrayHelper::getColumn($missingMembers,'handler'))){ // логина еще нет в массиве с логинами,  которых нет в команде проекта
										    $man = People::find()->where(['mantis_login'=>$result_issue->handler->name])->one();	
										    if(!is_null($man)){
												$fio = $man->getFIO();
												$id_ppl = $man->getid();
												} else {
													$fio = 'В PMIS нет такого человека';
													$id_ppl = -1;
													}
											$missingMembers[] = array('handler'=> $result_issue->handler->name,
																	  'fio'=>$fio,
																	  'id'=>$id_ppl,
																	  );
										}
									}
								}	
								
							}
						}
						//var_dump($missingMembers);die;
					}
					
				  }	
				
				}
				if($btn_info[0] == 'add') { //добавление сотрудника в команду
					$idPeople = $btn_info[1];
					$SelectedRole = $a['idRole'][$idPeople]; 
					$Role_info = explode("_", $SelectedRole);
					$prjComm = new ProjectCommand();
					$prjComm->parent_id = $Role_info[1];
					$prjComm->idBR = $BR->getBrId();
					$prjComm->idRole = $Role_info[0];
					$prjComm->idHuman = $idPeople;
					 if(!$prjComm->save()){
						 Yii::$app->session->addFlash('error',"Ошибка добавление сотрудника в команду" );
					 } 
					
					
				}
				if($btn_info[0] == 'mnt2') {   //создаем работы по выбранным инцдентам
					 
  					 if(isset($a['relatedissue'])) {
						foreach($a['relatedissue'] as $r){
							$result_issue = $client->mc_issue_get($username, $password, $r); 	
							if(is_soap_fault($result_issue)){   //Ошибка
							    Yii::$app->session->addFlash('error',"Ошибка получения информации из mantis SOAP: (faultcode: ".$result->faultcode.
							    " faultstring: ".$result_issue->faultstring);
							}else{
								
								$modelWOS = new WorksOfEstimate();
								$result = $modelWOS->addWork($idEstimateWorkPackages,$idWbs,$result_issue->summary,$result_issue->description,$r);
									if($result<0){
										Yii::$app->session->addFlash('error',"Ошибка создания работы на основе инцидента ".$r);
									}else{
										Yii::$app->session->addFlash('success',"Создана работа на основе инцидента ".$r);
										//теперь привязываем исполнителя  если он есть в команде
										 $idTeamMember = $BR->getIdTeamMemberBylogin($result_issue->handler->name);
										   if($idTeamMember > 0 ){
											$modelWE = new WorkEffort();
									        $modelWE->idWorksOfEstimate = $modelWOS->idWorksOfEstimate;
									        $modelWE->workEffort = 0;
									        $modelWE->idTeamMember = $idTeamMember;
									        $modelWE->idServiceType = -1;
									        if(!$modelWE->save()){
											//Yii::$app->session->addFlash('error',"Ошибка добавления трудозатрат в работу" );
												$ErrorsArray = $modelWE->getErrors(); 	 
													foreach ($ErrorsArray as $key => $value1){
														foreach($value1 as $value2){
																Yii::$app->session->addFlash('error',"Ошибка сохранения. Реквизит ".$key." ".$value2);
														}
													}
										    }   
										   }
										    
										}
									
								}
							}
						
				}
				//die;
				//Yii::$app->session->addFlash('error',"ИИИха ");
			}
			}
		}   
	   
       
        
	  $VwListOfWorkEffort = VwListOfWorkEffort::find()->where(['idEstimateWorkPackages'=>$idEstimateWorkPackages, 'idWbs'=>$idWbs])->all();	
	  return $this->render('TakeWorksFromMantis', [
			'idBR'=>$idBR,
            'id_node'=>$idWbs,
            'VwListOfWorkEffort' => $VwListOfWorkEffort,
            'idEstimateWorkPackages' => $idEstimateWorkPackages,
            'mantis_links' => $BR->getMantisNumbers(2),
            'related_issue'=>$related_issue,
            'missingMembers'=>$missingMembers
        ]);	
    }
    
 /* 
 * Перемещение работ в другой результат
 */
    public function actionMove_works_to_another_result($idEstimateWorkPackages,$idWbs,$idBR)
    {
	   $model = new MoveWorksToAnotherResultForm();
       $a = Yii::$app->request->post();
       if ($model->load(Yii::$app->request->post())) {
		if (!empty($a)) {
			if(isset($a['selectedWorks'])) {
				//переносим работы
				foreach($a['selectedWorks'] as $r){
					Yii::$app->db->createCommand()->update('WorksOfEstimate'
					, ['idWbs' => $model->NewResult	,'idEstimateWorkPackages'=>$idEstimateWorkPackages], 'idWorksOfEstimate = '.$r)->execute();
					}
				$this->redirect(['index', 'id_node' => $model->NewResult ,'idBR' => $idBR, 'idEWP'=>$idEstimateWorkPackages]);	
				} else {
					Yii::$app->session->addFlash('error','Не выбраны работы для переноса' );
					}
		}   
	   }
       
        
	  $VwListOfWorkEffort = VwListOfWorkEffort::find()->where(['idEstimateWorkPackages'=>$idEstimateWorkPackages, 'idWbs'=>$idWbs])->all();	
	  return $this->render('MoveWorksToAnotherResult', [
			'model'=> $model,
			'idBR'=>$idBR,
            'id_node'=>$idWbs,
            'VwListOfWorkEffort' => $VwListOfWorkEffort,
            'idEstimateWorkPackages' => $idEstimateWorkPackages
        ]);	
    }
  /*
   * Добавление задачи предшественницы
   * 
   */   
   public function actionAdd_work_prev($idWbs,$idBR,$idEWP,$idWOS,$idPrevWrk=0)   
    {
	$a = Yii::$app->request->post();   
    $searchModel = new select_Work_search();	
    //$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$idEWP,$idBR,$idWOS);	
    $dataProvider = $searchModel->search($a,$idEWP,$idBR,$idWOS);	
        
    if(isset($a['btn'])) {   // анализируем нажатые кнопки
		//Yii::$app->session->addFlash('error',"a[btn] ".$a['btn']);				
		$btn_info = explode("_", $a['btn']);
		if($btn_info[0] == 'cancl') {   // отмена
			return $this->redirect(['update','idWorksOfEstimate'=>$idWOS,'idBR'=>$idBR,'idWbs'=>$idWbs,'idEstimateWorkPackages'=>$idEWP,'page_number'=>3]);		
	    }		
    }	
    
	if($idPrevWrk==0){
		return $this->render('select_work_prev', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'idBR'=> $idBR,
            'idEWP'=>$idEWP,
            'idWOS' => $idWOS,
            'idWbs'=>$idWbs
        ]);
	}else{
		$lnk = new Links();
		$lnk->idFirstWork = $idPrevWrk;
		$lnk->idSecondWork = $idWOS;
		$lnk->idLinkType = 1;
		$lnk->idEstimateWorkPackages = $idEWP;
		if($lnk->save()){
		//сохраняем и редактируем связь
			return $this->redirect(['edit_link','idWorksOfEstimate'=>$idWOS,'idBR'=>$idBR,'idWbs'=>$idWbs,'idEstimateWorkPackages'=>$idEWP,'idLink'=>$lnk->idLink]);
			
			
		 } else{
			 if($lnk->hasErrors()){
				$ErrorsArray = $lnk->getErrors(); 	 
				foreach ($ErrorsArray as $key => $value1){
					foreach($value1 as $value2){
							Yii::$app->session->addFlash('error',"Ошибка сохранения. Реквизит ".$key." ".$value2);
					}
				}	
				//// если не удалось сохранить  продолжаем выбирать
				return $this->render('select_work_prev', [
		            'searchModel' => $searchModel,
		            'dataProvider' => $dataProvider,
		            'idBR'=> $idBR,
		            'idEWP'=>$idEWP,
		            'idWOS' => $idWOS,
		            'idWbs'=>$idWbs
		        ]);
			 }
		}	 
  	 }
    }
    /*
     * Удаляет все связ по работе и
     * Выстраивает работы по достижению результата последовательно в диаграмме Ганта
     * 
     */ 
	public function actionLine_up_works($idEstimateWorkPackages,$idWbs,$idBR){
		if (!Yii::$app->user->can('LineUpWorks')) {   // проверка права на создание 
		   Yii::$app->session->addFlash('error',"Нет прав на выполнение операции");
		   return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
	     }
		//удаляем все связи по результату в оценке
		Yii::$app->db->createCommand('Delete from Links 
			where idFirstWork in (select idWorksOfEstimate from  WorksOfEstimate where idEstimateWorkPackages = '.$idEstimateWorkPackages.' and idWbs = '.$idWbs.') 
			or idSecondWork in (select idWorksOfEstimate from  WorksOfEstimate where idEstimateWorkPackages ='.$idEstimateWorkPackages.' and idWbs = '.$idWbs.')')
			->execute();
		//
		$EWP = EstimateWorkPackages::findOne(['idEstimateWorkPackages'=>$idEstimateWorkPackages]); 
		$workList = $EWP->getWorksList($idWbs);
		if($workList){
			$first_work_id = $workList[0]['idWorksOfEstimate'];
			foreach($workList as $wl){
				if($wl['idWorksOfEstimate']!=$first_work_id){
					//создаем связь
					$lnk = new Links();
					$lnk->idFirstWork = $first_work_id;
					$lnk->idSecondWork = $wl['idWorksOfEstimate'];
					$lnk->idLinkType = 1;
					$lnk->idEstimateWorkPackages = $idEstimateWorkPackages;
					if($lnk->save()){
						$first_work_id = $wl['idWorksOfEstimate'];
					 } else{
						 if($lnk->hasErrors()){
							$ErrorsArray = $lnk->getErrors(); 	 
							foreach ($ErrorsArray as $key => $value1){
								foreach($value1 as $value2){
								   Yii::$app->session->addFlash('error',"Ошибка сохранения. Реквизит ".$key." ".$value2);
								}
							}	
						 }
					}	
				}
			}
		  }
		  Yii::$app->session->addFlash('success',"связи установлены");
		  return $this->redirect(['works_of_estimate/index','idBR'=>$idBR,'id_node'=>$idWbs,'idEWP'=>$idEstimateWorkPackages]);
		 
		  	
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
