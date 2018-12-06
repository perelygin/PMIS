<?php
namespace app\components;
 
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
}
