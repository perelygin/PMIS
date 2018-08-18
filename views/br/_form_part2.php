 <?php 
	use app\models\Organization;
    use yii\helpers\ArrayHelper;
    use app\models\Projects;
    use yii\helpers\Html;
    use yii\bootstrap\Button;
    //use yii\helpers\Url
  ?>
  
  
 <table>
		
  <?php  
	foreach ($prj_comm_model as $role) {
		
			echo('<tr><td><b>'.$role['RoleName'].': </b></td><td></td></tr>');
			
		
		$persons = $role['Persons'];
		foreach($persons as $person){
			
			 echo('<tr><td></td><td>'.$person['Name'].'</td><td></td></tr>');
			
		}
		
	} 
	
 ?>
  
 </table>
 
 	
 	
 	
 			
				
 
  
