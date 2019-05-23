<?php
namespace app\components;
 
use app\models\Wbs;
use app\models\vw_settings; 
use app\models\User;
use SoapClient;
 
class myHelper{
	public static function Round_05($a){
		/*
		 * Функция  округляет с точностью до  0.5 
		 * 3.1 = 3
		 * 3.5 = 3.5
		 * 3.8 = 4
		 */
		$whole = intval($a);  //целая часть
		$decimal = $a - $whole;  //дробная часть
		if($decimal < 0.5){
			$decimal = 0;
			}elseif($decimal > 0.5){
				$decimal = 0;
				$whole = $whole +1; 
				} elseif ($decimal == 0.5){
					$decimal = 0.5;
					}
		
		return $whole + $decimal;
		
		//$price = 1234.44; $whole = intval($price); 
		// 1234 $decimal1 = $price - $whole; 
		// 0.44000000000005 uh oh! that's why it needs... (see next line) $decimal2 = round($decimal1, 2); 
		// 0.44 this will round off the excess numbers $decimal = substr($decimal2, 2); 
		// 44 this removed the first 2 characters if ($decimal == 1) { $decimal = 10; } 
		// Michel's warning is correct... if ($decimal == 2) { $decimal = 20; } 
		// if the price is 1234.10... the decimal will be 1... if ($decimal == 3) { $decimal = 30; } 
		// so make sure to add these rules too if ($decimal == 4) { $decimal = 40; } 
		//if ($decimal == 5) { $decimal = 50; } if ($decimal == 6) { $decimal = 60; } 
		//if ($decimal == 7) { $decimal = 70; } if ($decimal == 8) { $decimal = 80; } 
		//if ($decimal == 9) { $decimal = 90; } echo 'The dollar amount is ' . $whole . ' and the decimal amount is ' . $decimal; 
		
	}
	/*
     *список проектов мантис,  который нам нужен только для результов  типа "ПО" и если не заполнен номер инцидента, в противном случа - нефиг дергать сервис
     * 
     */ 
	public static function getMantisprojects($idWbs){
		//Считывание настроек
		$settings = vw_settings::findOne(['Prm_name'=>'Mantis_path_create']);   //путь к wsdl тянем из настроек
						if (!is_null($settings)) $url_mantis_cr = $settings->enm_str_value; //путь к мантиссе
						  else $url_mantis_cr = '';
		//$settingsDefaultUser = vw_settings::findOne(['Prm_name'=>'Mantis_default_user']);   //пользователь mantis  по умолчанию
						//if (!is_null($settingsDefaultUser)) $mntDefaultUser = $settingsDefaultUser->enm_str_value; //имя пользователя
						  //else $mntDefaultUser = 'pmis';
		//wsdl клиент
			$User = User::findOne(['id'=>\Yii::$app->user->getId()]); 
			$username = $User->getUserMantisName();
			$password = $User->getMantisPwd();
							  
		$MntPrjLstArray =  array();
		$wbs = Wbs::findOne(['id'=>$idWbs]); 
		$wbs_info = $wbs->getWbsInfo();  				  
				
		if($wbs_info['idResultType'] == 2 or $wbs_info['idResultType'] == 3 or $wbs_info['idResultType'] == 4){
			
			$client = new SoapClient($url_mantis_cr,array('trace'=>1,'exceptions' => 0)); 	
			$result_1 =  $client->mc_projects_get_user_accessible($username, $password);
				 if (is_soap_fault($result_1)){   //Ошибка
				    Yii::$app->session->addFlash('error',"Ошибка связи с mantis SOAP: (faultcode: ".$result_1->faultcode.
				    " faultstring: ".$result_1->faultstring);
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
		return $MntPrjLstArray;
	}	
}
