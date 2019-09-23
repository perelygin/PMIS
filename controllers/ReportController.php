<?php

namespace app\controllers;
use Yii;
use app\models\VwReport2;
use app\models\VwReport1Search;
use app\models\BusinessRequests;
use app\models\WbsSchedule;
use app\models\ResultEvents;
use app\models\EstimateWorkPackages;
use app\models\ProjectCommand;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

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
		$a = Yii::$app->request->get();
		if(isset($a['btn'])) {   // анализируем нажатые кнопки
		   if($a['btn'] == 'excel'){ //dыгрузка в ексель
				$Alfabet = 'ABCDEFGHIJKLMNOPQR';
				$CurrentDate = \DateTime::createFromFormat('Y-m-d',  date("Y-m-d"));	 //текущая дата
				
				$ex_row = 2;
				$spreadsheet = new Spreadsheet();
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->getColumnDimension('A')->setWidth(70);  
				$sheet->getColumnDimension('B')->setWidth(20);  
				$sheet->getColumnDimension('C')->setWidth(12);  
				$sheet->getColumnDimension('D')->setWidth(20);  
				$sheet->getColumnDimension('E')->setWidth(12);  
				$sheet->getColumnDimension('F')->setWidth(15);  
				$sheet->getColumnDimension('G')->setWidth(12);  
				$sheet->getColumnDimension('H')->setWidth(15);  
				$sheet->getColumnDimension('I')->setWidth(12);  
				$sheet->getColumnDimension('J')->setWidth(30);  
				$sheet->getColumnDimension('K')->setWidth(20);  
				$sheet->getColumnDimension('L')->setWidth(20);  
				
				$sheet->setCellValue('A'.$ex_row, 'Сводный отчет по результатам по состоянию на '.$CurrentDate->format('d-m-Y'));
				$ex_row = $ex_row+1;
				
				$sheet->getStyle('A'.$ex_row.':L'.$ex_row)->getFont()->setBold(true);
				$sheet->getStyle('A'.$ex_row.':L'.$ex_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A'.$ex_row.':L'.$ex_row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
				$sheet->getStyle('A'.$ex_row.':L'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('A'.$ex_row.':L'.$ex_row)->setRowHeight(-1);
				
				$sheet->setCellValue('A'.$ex_row, 'BR');
				$sheet->setCellValue('B'.$ex_row, 'Результат проекта');
				$sheet->setCellValue('C'.$ex_row, 'Статус результата');
				$sheet->setCellValue('D'.$ex_row, 'Ответственный');
				$sheet->setCellValue('E'.$ex_row, 'План. верс.');
				$sheet->setCellValue('F'.$ex_row, 'Организация отв.');
				$sheet->setCellValue('G'.$ex_row, 'Плановая дата реакции');
				$sheet->setCellValue('H'.$ex_row, 'Приоритет');
				$sheet->setCellValue('I'.$ex_row, 'Число дней без движения (-2 от даты последнего события) ');
				$sheet->setCellValue('J'.$ex_row, 'Последнее событие');
				$sheet->setCellValue('K'.$ex_row, 'Аналитик');
				$sheet->setCellValue('L'.$ex_row, 'Технолог');
				$ex_row = $ex_row+1;
				foreach($dataProvider as $dp_str){
					$reprd_str =""; //строка для даты по событию
					$reprd = \DateTime::createFromFormat('Y-m-d', $dp_str->ResultEventsPlannedResponseDate);		 // дата окончания сохраняемой работы
					$analit_fio = ProjectCommand::getFIOsByRole($dp_str->idBr,3);//фио аналитика
					$tehno_fio = ProjectCommand::getFIOsByRole($dp_str->idBr,2);//фио технолога
					
					if($reprd){
						$reprd_str = $reprd->format('d-m-Y');
						if($reprd<$CurrentDate){  //проверка  на просроченую реакцию
						  $diff1 = $CurrentDate->diff($reprd);
						  $sheet->setCellValue('I'.$ex_row, $diff1->days);
						} 
					}
					
					$idEstPckg = BusinessRequests::findOne(['idBR'=>$dp_str->idBr])->getLastEstimateId(); 
					  if(!is_null($idEstPckg)){  //если есть пакет оценок по BR
						  $a = EstimateWorkPackages::findOne($idEstPckg);
						  if(is_null($a)) {echo($idEstPckg." ".$dp_str->idBr);  die;}
						  $WorksList = $a->getWorksList($dp_str->id);
						  
						  if(count($WorksList)>0){
							$str_mantis = "";
							foreach($WorksList as $wl){
								if(!empty($wl['mantisNumber'])){
									$str_mantis = $wl['mantisNumber'].','.$str_mantis;
								}
						    }
						  }
			 		  } 
					
					
					$sheet->setCellValue('A'.$ex_row, $str_mantis.' BR'.$dp_str->BRNumber.' '.$dp_str->BRName);
					$sheet->setCellValue('B'.$ex_row, $dp_str->name);
					$sheet->setCellValue('C'.$ex_row, $dp_str->ResultStatusName);
					$sheet->setCellValue('D'.$ex_row, $dp_str->fio);
					$sheet->setCellValue('E'.$ex_row, $dp_str->version_number);
					$sheet->setCellValue('F'.$ex_row, $dp_str->CustomerName);
					$sheet->setCellValue('G'.$ex_row, $reprd_str);
					$sheet->setCellValue('H'.$ex_row, $dp_str->ResultPriorityOrder);
					$sheet->setCellValue('J'.$ex_row, $dp_str->ResultEventsName);
					$sheet->setCellValue('K'.$ex_row, $analit_fio);
					$sheet->setCellValue('L'.$ex_row, $tehno_fio);
					
					
					$ex_row = $ex_row+1;
				}
					  
				$sheet->getStyle('A3:A'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('A3:A'.$ex_row)->setRowHeight(-1);
				$sheet->getStyle('B3:B'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('B3:B'.$ex_row)->setRowHeight(-1);
				$sheet->getStyle('C3:C'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('C3:C'.$ex_row)->setRowHeight(-1);
				$sheet->getStyle('D3:D'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('D3:D'.$ex_row)->setRowHeight(-1);
				$sheet->getStyle('E3:E'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('E3:E'.$ex_row)->setRowHeight(-1);
				$sheet->getStyle('F3:F'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('F3:F'.$ex_row)->setRowHeight(-1);
				$sheet->getStyle('G3:G'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('G3:G'.$ex_row)->setRowHeight(-1);
				$sheet->getStyle('H3:H'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('H3:H'.$ex_row)->setRowHeight(-1);
				$sheet->getStyle('I3:I'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('I3:I'.$ex_row)->setRowHeight(-1);
				$sheet->getStyle('J3:J'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('J3:J'.$ex_row)->setRowHeight(-1);
				$sheet->getStyle('K3:K'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('K3:K'.$ex_row)->setRowHeight(-1);
				$sheet->getStyle('L3:L'.$ex_row)->getAlignment()->setWrapText(true);
				$sheet->getRowDimension('L3:L'.$ex_row)->setRowHeight(-1);
				//ставим границы
				$styleThinBlackBorderOutline = [
				    'borders' => [
					    'allBorders' => [
		                    'borderStyle' => Border::BORDER_THIN,
		                ],
				    ],
				];
				$sheet->getStyle('A3:'.'L'.$ex_row)->applyFromArray($styleThinBlackBorderOutline);
				
				$writer = new Xlsx($spreadsheet);
				$file_name = 'report1_'.$CurrentDate->format('d-m-Y').'.xlsx';
				$writer->save($file_name);
	            Yii::$app->response->sendFile('/var/www/html/pmis_app/web/'.$file_name)->send();  
	            return $this->render('index_report1', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		   }
		}

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
   ///*
    //* сводный сетевой график вариант2
    //* перед запуском отчета делается пересчет графика по всем BR, у которых есть хотя бы один результат в работе
    //* для каждого результата в работе выводится информация о плановой дате получения результата 
    //* и о плановой дате реакции по  событию результата
    //*/  
   //public function actionReport3()
   //{
		////получаем перечень результатов со статусом 2(в работе)
		//$VwReport2 = VwReport2::find()->all();
		//$idBR = -1;
		//$LastEstimateId = -1;
		//$result =  array();
		//foreach($VwReport2 as $vr2){
			//$res_str = array();
			//if($idBR != $vr2['idBR']){  //id BR повторяются для каждого результата
				//$idBR = $vr2['idBR'];
				//$BR = BusinessRequests::findOne($idBR);
			    //if(!is_null($BR)){   //  получаем id актуальной оценки для BR  покаждому результату.
					//$LastEstimateId = $BR->getLastEstimateId();
				//}				
			//}
			//if(!is_null($LastEstimateId)){
				
			//}
			
		//}	
   //}	
}
