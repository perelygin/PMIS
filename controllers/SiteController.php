<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use app\models\User;
use app\models\RbacForm;
use app\models\PersonalCabinetForm;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

   public function actionTestsoup()
    {
		//phpinfo(); die;
		//var_dump(extension_loaded('zip')); die;
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Hello World !');
		$writer = new Xlsx($spreadsheet);
		$writer->save('hwd1.xlsx');
			
        return Yii::$app->response->sendFile('/var/www/html/pmis_app/web/hwd1.xlsx')->send();
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    /**
     * Displays help page.
     *
     * @return string
     */
    public function actionHelp($anchor = '')
    {
        return $this->render('help');
    }
    //личный кабинет
	public function actionPersonalcabinet(){
		//$model = new PersonalCabinetForm();
		return $this->render('personalcabinet');
	}
	
 //регистрация пользователя
    public function actionSignup(){
	 if (!Yii::$app->user->isGuest) {
		return $this->goHome();
	 }
	 $model = new SignupForm();
	 if($model->load(\Yii::$app->request->post()) && $model->validate()){
		 $user = new User();
		 $user->username = $model->username;
		 $user->email = $model->email;
		 $user->setPassword($model->password);
		 $user->generateAuthKey();
		 
		 
		 if($user->save()){
					
			return $this->goHome();
		 } 
		 else{
			 if($user->hasErrors()){
				$ErrorsArray = $user->getErrors(); 	 
				foreach ($ErrorsArray as $key => $value1){
					foreach($value1 as $value2){
						if($key == 'username'){
							$model->addError($key, $value2);
						} else {
							Yii::$app->session->addFlash('error',"Ошибка сохранения. Реквизит ".$key." ".$value2);
						}
					}
				}	
				//echo '<pre>'; print_r($ErrorsArray); die;
			 }
			 
			 
			 
		 }
	  }
	 return $this->render('signup', compact('model'));
	}
	
	
   public function actionRbac(){
	  //if (!Yii::$app->user->can('ManageUserRole')){
		  //Yii::$app->session->addFlash('error',"У вас нет прав на использование этого функционала");
			//return $this->goHome();
		  //}
		$model = new RbacForm();
		if($model->load(\Yii::$app->request->post()) && $model->validate()){
			$auth = Yii::$app->authManager;
			$auth->removeAll(); //На всякий случай удаляем старые данные из БД...
        
	        // Создадим роли админа и прочих
	        $admin = $auth->createRole('admin');
	        $admin->description = 'Администратор';
	        $pm = $auth->createRole('projectmanager');
	        $pm->description = 'Менеджер проекта';
	        $analyst = $auth->createRole('analyst');
	        $analyst->description = 'Аналитик';
	        
	        // запишем их в БД
	        $auth->add($admin);
	        $auth->add($pm);
	        $auth->add($analyst);
	       
	        
	        // Создаем разрешения. Например,управление ролями
			$UserRoleManagementPage = $auth->createPermission('ManageUserRole');
			$UserRoleManagementPage->description = 'Управление ролями';
			
			//Просмотр журнала BR
			$BRJournalView = $auth->createPermission('BRJournalView');
			$BRJournalView->description = 'Просмотр журнала BR';
			//Регистрация BR 
			$BRCreate = $auth->createPermission('BRCreate');
			$BRCreate->description = 'Регистрация BR';
			//Удаление BR 
			$BRDelete = $auth->createPermission('BRDelete');
			$BRDelete->description = 'Удаление BR';
			 
			 
			 
			// Запишем эти разрешения в БД
			$auth->add($UserRoleManagementPage);
			$auth->add($BRJournalView); 
			$auth->add($BRCreate);
			$auth->add($BRDelete);
			 
			// Теперь добавим наследования. Для роли analyst мы добавим разрешение BRJournalView,
			// а для админа добавим наследование от роли analyst и еще добавим собственное разрешение 
        
        // Роли «Аналитик» присваиваем разрешение «Просмотр журнала BR»
        $auth->addChild($analyst,$BRJournalView);

        // админ наследует роль analyst
        $auth->addChild($admin, $analyst);
        
        // Еще админ имеет собственное разрешение - «Просмотр админки»
        $auth->addChild($admin, $UserRoleManagementPage);
        
        //Менеджер наследует от аналитика 
         $auth->addChild($pm, $analyst);
         $auth->addChild($pm, $BRCreate);
         $auth->addChild($pm, $BRDelete);
        
         // Назначаем роль admin пользователю с ID 1
        $auth->assign($pm, 2); 
        
        // Назначаем роль editor пользователю с ID 2
        //$auth->assign($editor, 2);
			 
		}
		return $this->render('RbacForm', compact('model'));
	
	}
    
}
