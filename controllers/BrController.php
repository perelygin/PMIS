<?php

namespace app\controllers;

use Yii;
use app\models\VwListOfBR;
use app\models\VwListOfBRSearch;
use app\models\BusinessRequests;  
use app\models\RoleModel;
use app\models\ProjectCommand;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
				$prjComm->idHuman = null;
				$prjComm->save();
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
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $prjComm = new ProjectCommand();
		$prj_comm_model = $prjComm->get_RoleModel($id); //массив с описанием комманды BR

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idBR]);
        }

        return $this->render('update', [
            'model' => $model,
            'prj_comm_model'=>$prj_comm_model
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
}