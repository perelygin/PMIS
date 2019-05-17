<?php

namespace app\controllers;
use Yii;
use app\models\VwReport2;
use app\models\VwReport1Search;
use app\models\BusinessRequests;
use app\models\WbsSchedule;
use app\models\ResultEvents;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ReportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    //сводный отчет по результатам. Предусматривает фильтрацию  по номеру BR, текущему ответственному исполнителю и т.д.
    public function actionReport1()
    {
		
        $searchModel = new VwReport1Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index_report1', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //сводный сетевой график
    /*
     * отчет состоит из несколькьких частей и предназначен для отслеживания сроков достижения результатов
     * Часть 1. Перечень результатов,  срок достижения которых уже истек. как следствие -  требуется перепланирование
     * Часть 2. Перечень результатов,  срок достижения которых планируетя на предстоящей неделе. Для каждого результата,  по которому 
     * был перенос сроков, нужно выводить все даты.
     * 
     */ 
    
    public function actionReport2()
    {
		//получаем перечень результатов со статусом 2(в работе)
		$VwReport2 = VwReport2::find()->all();
		$idBR = -1;
		$LastEstimateId = -1;
		$result =  array();
		foreach($VwReport2 as $vr2){
			$res_str = array();
			if($idBR != $vr2['idBR']){  //id BR повторяются для каждого результата
				$idBR = $vr2['idBR'];
				$BR = BusinessRequests::findOne($idBR);
			    if(!is_null($BR)){   //  получаем id актуальной оценки для BR  покаждому результату.
					$LastEstimateId = $BR->getLastEstimateId();
				}				
			}
			if(!is_null($LastEstimateId)){
				$dend =  WbsSchedule::getWbsEndDate($LastEstimateId,$vr2['id']);
				$dend_list =  WbsSchedule::getWbsEndDatelist($LastEstimateId,$vr2['id']);  //список всех дат окончания(из-за переносов их может быть много)
				$dPlanEvent = ResultEvents::getLastResultEventPlanData($vr2['id']);//дату плановой реакции по последнему событию результата
				if($dPlanEvent){
					$dPlanEvent_txt = $dPlanEvent->format('Y-m-d');
					} else{
						$dPlanEvent_txt = '';
						}
				if($dend){  //есть дата окончания
					$curentDate = \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));//текущая дата
					if($dend<$curentDate){  //просроченные
						$result[] = ['BRNumber'=>$vr2['BRNumber'],
						              'BRName' => $vr2['BRName'],
						              'idBR' => $vr2['idBR'],
						              'name'=>$vr2['name'],
							          'id' => $vr2['id'],
						              'fio' => $vr2['fio'],
							          'CustomerName' => $vr2['CustomerName'],
							          'version_number' => $vr2['version_number'],
							          'DateEnd' => $dend->format('Y-m-d'),
							          'DateEndList' => $dend_list,
							          'dPlanEvent' => $dPlanEvent_txt,
							          'TypeRes' => '1'
							          ];	
						} else{
							$interval = $dend->diff($curentDate);
							if($interval->days > 7){  // плановая дата больше чем через 7 дней
								$result[] = ['BRNumber'=>$vr2['BRNumber'],
						              'BRName' => $vr2['BRName'],
						              'idBR' => $vr2['idBR'],
						              'name'=>$vr2['name'],
							          'id' => $vr2['id'],
						              'fio' => $vr2['fio'],
							          'CustomerName' => $vr2['CustomerName'],
							          'version_number' => $vr2['version_number'],
							          'DateEnd' => $dend->format('Y-m-d'),
							          'DateEndList' => $dend_list,
							          'dPlanEvent' => $dPlanEvent->format('Y-m-d'),
							          'TypeRes' => '2'
							          ];	
			 				   } else{
								   $result[] = ['BRNumber'=>$vr2['BRNumber'],
						              'BRName' => $vr2['BRName'],
						              'idBR' => $vr2['idBR'],
						              'name'=>$vr2['name'],
							          'id' => $vr2['id'],
						              'fio' => $vr2['fio'],
							          'CustomerName' => $vr2['CustomerName'],
							          'version_number' => $vr2['version_number'],
							          'DateEnd' => $dend->format('Y-m-d'),
							          'DateEndList' => $dend_list,
							          'dPlanEvent' => $dPlanEvent_txt,
							          'TypeRes' => '3'
							          ];	
								   }
						}
					} else{ //нет даты окончания
						$result[] = ['BRNumber'=>$vr2['BRNumber'],
					              'BRName' => $vr2['BRName'],
					              'idBR' => $vr2['idBR'],
					              'name'=>$vr2['name'],
						          'id' => $vr2['id'],
					              'fio' => $vr2['fio'],
						          'CustomerName' => $vr2['CustomerName'],
						          'version_number' => $vr2['version_number'],
						          'DateEnd' => false,
						          'DateEndList' => false,
						          'dPlanEvent' => $dPlanEvent_txt,
						          'TypeRes' => '0'
						          ];
						}
			}			
		 // $result[] =   $res_str; 
		}
	   
        return $this->render('index_report2', [
           'result'=>$result
        ]);
        
    }  
}
