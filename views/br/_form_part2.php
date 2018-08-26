 <?php 
	use app\models\Organization;
    use yii\helpers\ArrayHelper;
    use app\models\Projects;
    use yii\helpers\Html;
    use yii\bootstrap\Button;
    use yii\bootstrap\Modal;
    //use yii\helpers\Url
  ?>
  
  
 <table>
		
  <?php  
   $item = array();
   
	foreach ($prj_comm_model as $role) {
		echo('<tr><td>');
		    echo Html::a(Html::img(Yii::getAlias('@PIC_PATH').'/plus1.png', ['title' => 'Добавить члена команды в роль']),
					['br/add_user_to_role', 'idBr' => $model->idBR, 'idRole' => $role['idRole'],'ParentId'=>$role['parent_id']] );
	        echo('</td><td colspan="2"><b>'.$role['RoleName'].': </b></td></tr>'); 
		$persons = $role['Persons'];
		foreach($persons as $person){
			echo('<tr><td></td><td>');
			echo(Html::a(Html::img(Yii::getAlias('@PIC_PATH').'/minus.png', ['title' => 'Убрать члена команды из роли']),['br/delete_user_from_role', 'idPrjCom' => $person['idPrjCom'],'idBr'=>$model->idBR]));
			echo('</td><td>'.$person['Name'].'</td></tr>');
		}
	} 
	
 ?>
  
 </table>
 
 
 <?php	
		//Modal::begin([
		    //'header' => '<h2>Hello world</h2>',
		    //'toggleButton' => ['label' => 'click me'],
		    //'footer' => 'Низ окна',
		//]);
		 
		//echo 'Say hello...';
		 
		//Modal::end();
	
//        $items=array('items'=>$item);
        
        //var_dump($items);die;
 //	echo Collapse::widget($items);
 	?>
 			
				
 
  
