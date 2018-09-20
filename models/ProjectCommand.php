<?php

namespace app\models;

use Yii;
use app\models\WorkEffort;

/**
 * This is the model class for table "ProjectCommand".
 *
 * @property int $id
 * @property int $parent_id
 * @property int $idBR
 * @property int $idRole
 * @property int $idHuman
 */
class ProjectCommand extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ProjectCommand';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'idBR', 'idRole', 'idHuman'], 'integer'],
            [['parent_id', 'idBR', 'idRole', 'idHuman'], 'required'],
            [['idHuman'], 'unique', 'targetAttribute' => ['parent_id', 'idBR', 'idRole', 'idHuman'],'message' =>'Этот человек с такой ролью уже есть в команде'],
          
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'idBR' => 'Id Br',
            'idRole' => 'Id Role',
            'idHuman' => 'Id Human',
        ];
    }
    /*
     * возвращает массив ролей с привязкой конкретных сотрудников по заданной BR
     */ 
    public function get_RoleModel($idBR)
     {
		 
		 //$Roles = $this::find()->asArray()->join('LEFT JOIN', 'RoleModel', 'ProjectCommand.idRole = RoleModel.idRole')->where(['parent_id' => 0, 'idBR'=>$idBR])->all(); 
		 //print_r($Roles);
		 //die;
		 
		//выбрали все роли по BR(заголовки)
		 $sql = 'SELECT * FROM ProjectCommand prj_com
					LEFT OUTER JOIN  RoleModel rm ON rm.idRole = prj_com.idRole
					WHERE idBR =:idBr AND parent_id=0';
		 $roles = $this::findBySql($sql, [':idBr' => $idBR])->asArray()->all();
		 
		 $prjCommand = array();
		 foreach ($roles as $role) {
			 //для каждой роли собирем информацию по людям с это ролью на BR
			   //echo($role['idRole']);
			   //echo($role['RoleName']);
			   $sql1 = 'SELECT * FROM ProjectCommand prj_com
					LEFT OUTER JOIN  People p ON prj_com.idHuman = p.idHuman
					WHERE idBR =:idBr AND parent_id=:parent_id';
				 $peoples = $this::findBySql($sql1, [':idBr' => $idBR,':parent_id'=>$role['id']])->asArray()->all();
				 $person_of_role = array();
				 foreach ($peoples as $people) {
					 $person= array('idHuman' => $people['idHuman'],'Name'=>$people['Family'].' '.$people['Name'],'idRole'=>$people['idRole'],'idPrjCom'=>$people['id']);
					 
					 $person_of_role[] = $person;
				 }
				 //var_dump($person_of_role);
				 //die;
				 $prjLine = array('parent_id'=>$role['id'],'idRole' => $role['idRole'],'RoleName'=>$role['RoleName'],'Persons'=>$person_of_role);
				 $prjCommand[]=$prjLine;
				 
			 }
			
		 return $prjCommand;	
	 }
	 
	 public function getAnyTeamMember($idBR){
		 $sql = 'SELECT * FROM ProjectCommand WHERE idBR=:idBR and parent_id != 0';
		 $pc = ProjectCommand::findBySql($sql, [
		':idBR' =>$idBR])->one();
		 
		 if(isset($pc)){
			 return $pc->id;
			 } else{
				 return -1;
				 }
	}
	 public function beforeDelete(){
        if (parent::beforeDelete()){
			//смотрим наличие  записей в подчиненной таблице с трудозатратами	
            $WorkEffort = WorkEffort::find()->where(['idTeamMember' => $this->id])->all();
            if(count($WorkEffort)>0){
				Yii::$app->session->addFlash('error','ошибка удаления,  есть подчиненные записи в оценке трудозатрат' );
				return false;
			}
            return true;
        }
        return false;
    }
}
