<?php

include("../php/conexion/conexion.php");
include("../php/conexion/ejecutar.php");
require_once'../lib/PHPExcel.php';


//$mesIni = '05';
//$anioIni = '2017';
//$mesFin = '05';
//$anioFin = '2017';

$mesIni = substr($_POST['fechaIni'],5,2);
$anioIni = substr($_POST['fechaIni'],0,4);
$mesFin = substr($_POST['fechaFin'],5,2);
$anioFin = substr($_POST['fechaFin'],0,4);

//$usu = 43,47;
$jsondata;
$cell = 6;
$mesLetraIni = mesLetra($mesIni);
$mesLetraFin = mesLetra($mesFin);


$jsondata[0] = consulta($con,"SELECT DISTINCT USU_REC FROM VW_CBBM_REPO_MASTER WHERE DATE_FORMAT(FECHA, '%Y/%m') BETWEEN '".$anioIni."/".$mesIni."' AND '".$anioFin."/".$mesFin."' ORDER BY EST_REC, CIU_REC");



if(!isset($jsondata[0])){
	header("Location: ../vistas/sinDatos.html");
	exit();
}

$objPHPExcel = new PHPExcel();

// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
$objPHPExcel->setActiveSheetIndex(0)
	->mergeCells('A1:F1')
	->mergeCells('A2:F2')
	->mergeCells('A3:F3');
 
// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A1', 'Informe de Misioneros') // Titulo del reporte
	->setCellValue('A2', $mesLetraIni .' '.$anioIni.'  -  '.$mesLetraFin.' '.$anioFin)
	->setCellValue('A5','Iglesia')
	->setCellValue('B5','Pastor')
	->setCellValue('C5','Ciudad')
	->setCellValue('D5','Estado');

$fecIni = $anioIni."/".$mesIni;
$fecFin = $anioFin."/".$mesFin;
$fecFin = sigMes($fecFin);
$col = 'E';
$fila = 6;

foreach ($jsondata[0] as $value){

	$mis = consulta('SELECT NOM_REC,PAS_REC,CIU_REC,EST_REC FROM VW_CBBM_REPO_MASTER WHERE USU_REC = '.$value[0]);

		
	if(gettype($mis) == "array"){

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila,$mis[0][0])
			->setCellValue('B'.$fila,$mis[0][1])
			->setCellValue('C'.$fila,$mis[0][2])
			->setCellValue('D'.$fila,$mis[0][3]);

	}
				
	$fila ++;
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('D'.$fila,'Total: ');
		
}


while ($fecIni != $fecFin) {
	$fila = 6;
	$totalCol=0;
	
	$objPHPExcel->setActiveSheetIndex(0)
		//->setCellValue($col.'5',$fecIni);	
		->setCellValue($col.'5', mesLetra(substr($fecIni,5,2)));	

	foreach ($jsondata[0] as $value){

		$mis = consulta($con,'SELECT SUM(MONTO) FROM VW_CBBM_REPO_MASTER WHERE USU_REC = '.$value[0].' AND DATE_FORMAT(FECHA, "%Y/%m") LIKE "%'.$fecIni.'%" GROUP BY USU_REC');

		
		if(gettype($mis) == "array"){

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($col.$fila,$mis[0][0]);
				$totalCol = $totalCol + $mis[0][0];

		}else{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($col.$fila,0);			
		}
				
		$fila ++;

		
	}

	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($col.$fila,$totalCol);

	$fecIni = sigMes($fecIni);
	$col ++;

}

$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.'5','Total');

for($contFila = 6; $contFila < $fila; $contFila ++){
	$totalRow = 0;

	for($i = 'E'; $i < $col; $i++){
	    $totalRow = $totalRow + $objPHPExcel->setActiveSheetIndex(0)->getCell($i.$contFila)->getValue();
	}

	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$contFila,$totalRow);
}

function imprime($misionero){
	print_r($misionero);
}



//////////////////////////////////////////////////////////// ESTILOS


$font16 = array(
   'font' => array(
       'size' =>16,
       'bold'  => true,
   ),
   'alignment' => array(
       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
       'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
       'rotation' => 0,
       'wrap' => TRUE
   )
);

$font11 = array(
   'font' => array(
       'size' =>11,
       'bold'  => true,
   ),
   'alignment' => array(
       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
       'rotation' => 0,
       'wrap' => TRUE
   )
);


$font10 = array(
   'font' => array(
       'size' =>11,
       'bold'  => true,
   ),
   'alignment' => array(
       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
       'rotation' => 0,
       'wrap' => TRUE
   ),
   'borders' => array(
		'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM 
        ),
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM 
        ),
        'right' => array(
        	'style' => PHPExcel_Style_Border::BORDER_MEDIUM
        ),
        'left' => array(
        	'style' => PHPExcel_Style_Border::BORDER_MEDIUM
        )

	)
);

$fontTotal = array(
   'font' => array(
       'size' =>11,
       'bold'  => true,
   ),
   'alignment' => array(
       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
       'rotation' => 0,
       'wrap' => TRUE
   )
);



$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($font16);

$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($font11);

$objPHPExcel->getActiveSheet()->getStyle('A5:'.$col.'5')->applyFromArray($font10);

$objPHPExcel->getActiveSheet()->getStyle('D'.$fila.':'.$col.$fila)->applyFromArray($fontTotal);

$objPHPExcel->getActiveSheet()->getStyle($col.'6'.':'.$col.$fila)->applyFromArray($fontTotal);

$objPHPExcel->getActiveSheet()->getStyle('D6:'.$col.$fila)->getNumberFormat()->setFormatCode("#,##0.00");

for($i = 'A'; $i <= 'D'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
}

$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight(25);


/////////////////////////////////////////////////////////////////////////



header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte.xlsx"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');




function mesLetra($mes){
	switch($mes){
		case '01':
			return 'Enero';
			break;
		case '02':
			return 'Febrero';
			break;
		case '03':
			return 'Marzo';
			break;
		case '04':
			return 'Abril';
			break;
		case '05':
			return 'Mayo';
			break;
		case '06':
			return 'Junio';
			break;
		case '07':
			return 'Julio';
			break;
		case '08':
			return 'Agosto';
			break;
		case '09':
			return 'Septiembre';
			break;
		case '10':
			return 'Octubre';
			break;
		case '11':
			return 'Noviembre';
			break;
		case '12':
			return 'Diciembre';
			break;
	}
}


function sigMes($fecha){
	$mes = substr($fecha,5,2);
	$anio = substr($fecha,0,4);

	if($mes == '12'){
		$mes = '01';
		$anio = $anio + 1;
	}else{
		$mes = $mes + 1;
		$mes = str_pad($mes,2,"0",STR_PAD_LEFT);
	}

	$nuevaFecha = $anio."/".$mes;

	return $nuevaFecha;
}

?>