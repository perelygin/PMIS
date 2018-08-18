<?php

namespace app\models;

use Yii;

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
					 $person= array('idHuman' => $people['idHuman'],'Name'=>$people['Family'].' '.$people['Name'],'idRole'=>$people['idRole']);
					 
					 $person_of_role[] = $person;
				 }
				 //var_dump($person_of_role);
				 //die;
				 $prjLine = array('idRole' => $role['idRole'],'RoleName'=>$role['RoleName'],'Persons'=>$person_of_role);
				 $prjCommand[]=$prjLine;
				 
			 }
			
		 return $prjCommand;	
	 }
}
