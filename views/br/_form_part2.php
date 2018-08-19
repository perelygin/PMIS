 <?php 
	use app\models\Organization;
    use yii\helpers\ArrayHelper;
    use app\models\Projects;
    use yii\helpers\Html;
    use yii\bootstrap\Button;
    //use yii\bootstrap\Collapse;
    //use yii\helpers\Url
  ?>
  
  
 <table>
		
  <?php  
   $item = array();
   
	foreach ($prj_comm_model as $role) {
	       
			echo('<tr><td><a href="index.php?id=main"><img src="'.Yii::getAlias('@PIC_PATH').'/plus1.png" /></a> </td><td colspan="2"><b>'.$role['RoleName'].': </b></td></tr>');
		   

		$persons = $role['Persons'];
		//$personsNames = '';
		foreach($persons as $person){
			//$personsNames = $personsNames.' <li>'. $person['Name'].'</li>';
			 echo('<tr><td></td><td><a href="index.php?id=main"><img src="'.Yii::getAlias('@PIC_PATH').'/minus.png" /></a></td><td>'.$person['Name'].'</td></tr>');
			
		}
		//$item[] = array('label' => 'Роль: '.$role['RoleName'],'content' => $personsNames,'contentOptions' => ['class' => 'in'],'options' => []);	
		
	} 
	
 ?>
  
 </table>
 
 
 <?php	

//        $items=array('items'=>$item);
        
        //var_dump($items);die;
 //	echo Collapse::widget($items);
 	?>
 			
				
 
  
