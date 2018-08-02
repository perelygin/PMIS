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
use app\models\PersonalCabinetForm;


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
	
	
    
}
