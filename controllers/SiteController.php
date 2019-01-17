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
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
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
		$model = new PersonalCabinetForm();
		
		if($model->load(\Yii::$app->request->post()) && $model->validate()){
			if (($modelUser = User::findOne(Yii::$app->user->id)) !== null) {
				$modelUser->username = $model->username;
				$modelUser->email = $model->email ;
				$modelUser->mantisname = $model->mantisname;
				
				if(!empty($model->password) or !empty($model->retypepassword)) 
				{
				   if ($model->password == $model->retypepassword){
						$modelUser->setPassword($model->password);	
						$modelUser->generateAuthKey();
					}else{
						//echo "=".$model->password."=".$model->retypepassword; die;
						//вот такая кривая валидация.  потому что compare в модели не работает.
						$model->addError('retypepassword', 'введенные значение не совпадают11');
						return $this->render('personalcabinet', ['model' => $model]);
					}
				} 
				if(!empty($model->mantispwd)){
					//шифруемся
					$secret_string = $model->username.$model->email;
					if(strlen($secret_string) < SODIUM_CRYPTO_SECRETBOX_KEYBYTES){
						$secret_key = str_pad($secret_string,SODIUM_CRYPTO_SECRETBOX_KEYBYTES,$model->mantisname);
						} else{
							$secret_key = substr($secret_string,0,SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
							}
					
					
					$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
					$modelUser->mantispwd  = base64_encode(sodium_crypto_secretbox($model->mantispwd, $nonce, $secret_key));
					$modelUser->mantisnonce = base64_encode($nonce);	
				}
					
				
				if($modelUser->save()){

					return $this->goHome();
				 } 
				 else{
					 if($modelUser->hasErrors()){
						$ErrorsArray = $modelUser->getErrors(); 	 
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
		} else{
			//Yii::$app->session->addFlash('error',"=".$model->password."=".$model->retypepassword."=");
		}
		if (($modelUser = User::findOne(Yii::$app->user->id)) !== null) {
				$model->username = $modelUser->username;
				$model->email = $modelUser->email ;
				$model->mantisname = $modelUser->mantisname;
				
				
				return $this->render('personalcabinet', ['model' => $model]);
        }  
		
		throw new NotFoundHttpException('The requested page does not exist.');
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
		 $user->mantisname = $model->mantisname;
		 $user->setPassword($model->password);
		 $user->generateAuthKey();
		 //шифруемся
			//$secret_key = sodium_crypto_secretbox_keygen();
			$secret_string = $model->username.$model->email;
			if(strlen($secret_string) < SODIUM_CRYPTO_SECRETBOX_KEYBYTES){
				$secret_key = str_pad($secret_string,SODIUM_CRYPTO_SECRETBOX_KEYBYTES,$model->mantisname);
				} else{
					$secret_key = substr($secret_string,0,SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
					}
			
			
			$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
			$user->mantispwd  = base64_encode(sodium_crypto_secretbox($model->mantispwd, $nonce, $secret_key));
			$user->mantisnonce = base64_encode($nonce);
			
			// echo base64_encode($encrypted_message).'<br>';
		    //$decrypted_message = sodium_crypto_secretbox_open($encrypted_message, $nonce, $secret_key);
		    //echo $decrypted_message.'<br>';
		    //echo $secret_key.'<br>';
			//die;
			
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
			//$auth = Yii::$app->authManager;
			//$auth->removeAll(); //На всякий случай удаляем старые данные из БД...
        
	        //// Создадим роли админа и прочих
	        //$admin = $auth->createRole('admin');
	        //$admin->description = 'Администратор';
	        //$pm = $auth->createRole('projectmanager');
	        //$pm->description = 'Менеджер проекта';
	        //$analyst = $auth->createRole('analyst');
	        //$analyst->description = 'Аналитик';
	        
	        //// запишем их в БД
	        //$auth->add($admin);
	        //$auth->add($pm);
	        //$auth->add($analyst);
	       
	        
	        //// Создаем разрешения. Например,управление ролями
			//$UserRoleManagementPage = $auth->createPermission('ManageUserRole');
			//$UserRoleManagementPage->description = 'Управление ролями';
			
			////Просмотр журнала BR
			//$BRJournalView = $auth->createPermission('BRJournalView');
			//$BRJournalView->description = 'Просмотр журнала BR';
			////Регистрация BR 
			//$BRCreate = $auth->createPermission('BRCreate');
			//$BRCreate->description = 'Регистрация BR';
			////Удаление BR 
			//$BRDelete = $auth->createPermission('BRDelete');
			//$BRDelete->description = 'Удаление BR';
			 
			 
			 
			//// Запишем эти разрешения в БД
			//$auth->add($UserRoleManagementPage);
			//$auth->add($BRJournalView); 
			//$auth->add($BRCreate);
			//$auth->add($BRDelete);
			 
			//// Теперь добавим наследования. Для роли analyst мы добавим разрешение BRJournalView,
			//// а для админа добавим наследование от роли analyst и еще добавим собственное разрешение 
        
        //// Роли «Аналитик» присваиваем разрешение «Просмотр журнала BR»
        //$auth->addChild($analyst,$BRJournalView);

        //// админ наследует роль analyst
        //$auth->addChild($admin, $analyst);
        
        //// Еще админ имеет собственное разрешение - «Просмотр админки»
        //$auth->addChild($admin, $UserRoleManagementPage);
        
        ////Менеджер наследует от аналитика 
         //$auth->addChild($pm, $analyst);
         //$auth->addChild($pm, $BRCreate);
         //$auth->addChild($pm, $BRDelete);
        
         //// Назначаем роль admin пользователю с ID 1
        //$auth->assign($pm, 2); 
        
        //// Назначаем роль editor пользователю с ID 2
        ////$auth->assign($editor, 2);
		
		///07/12/2018
			$auth = Yii::$app->authManager;	 
			//// Создаем разрешения. Например,управление ролями
			////Удаление узла wBS
			$WBSDeleteNode = $auth->createPermission('WBSDeleteNode');
			$WBSDeleteNode->description = 'Удаление узла wbs';
			 
			//// Запишем эти разрешения в БД
			$auth->add($WBSDeleteNode);
		    
		    $PMRole = $auth->getRole('projectmanager');
		    $auth->addChild($PMRole,$WBSDeleteNode);
		
		
		}
		return $this->render('RbacForm', compact('model'));
	
	}
	  /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
 
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }
 
        return $this->render('passwordResetRequestForm', [
            'model' => $model,
        ]);
    }
      /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
 
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }
 
        return $this->render('resetPasswordForm', [
            'model' => $model,
         ]);   
      }
 

}
