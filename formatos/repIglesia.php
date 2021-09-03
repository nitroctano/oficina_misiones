<?php

include("../php/conexion/conexion.php");
include("../php/conexion/ejecutar.php");
require_once'../lib/PHPExcel.php';


//$mes = '05';
$mes = substr($_POST['fechaIglesia'],5,2);
//$anio = '2017';
$anio = substr($_POST['fechaIglesia'],0,4);
$usu = $_POST['idIglesia'];

$jsondata;
$cell = 6;
$mesLetra;

switch($mes){
	case '01':
		$mesLetra = 'Enero';
		break;
	case '02':
		$mesLetra = 'Febrero';
		break;
	case '03':
		$mesLetra = 'Marzo';
		break;
	case '04':
		$mesLetra = 'Abril';
		break;
	case '05':
		$mesLetra = 'Mayo';
		break;
	case '06':
		$mesLetra = 'Junio';
		break;
	case '07':
		$mesLetra = 'Julio';
		break;
	case '08':
		$mesLetra = 'Agosto';
		break;
	case '09':
		$mesLetra = 'Septiembre';
		break;
	case '10':
		$mesLetra = 'Octubre';
		break;
	case '11':
		$mesLetra = 'Noviembre';
		break;
	case '12':
		$mesLetra = 'Diciembre';
		break;
}



$jsondata[0] = consulta("SELECT * FROM VW_CBBM_REPO_MASTER WHERE USU_ENVIA = ".$usu." AND FECHA LIKE '%".$anio."-".$mes."%' ORDER BY EST_REC, CIU_REC");

if(!isset($jsondata[0])){
	header("Location: ../vistas/sinDatos.html");
	exit();
}
$jsondata[1] = consulta('SELECT * FROM CBBM_USUARIOS WHERE ID_USUARIO = '.$usu);


$total = 0;
foreach ($jsondata[0] as &$value) {
	$total = $total + $value[7];
}


$objPHPExcel = new PHPExcel();

// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
$objPHPExcel->setActiveSheetIndex(0)
	->mergeCells('A1:F1')
	->mergeCells('A2:F2')
	->mergeCells('A3:F3');
 
// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A1', 'Informe de '.$mesLetra.' '.$anio) // Titulo del reporte
	->setCellValue('A2', 'Pastor: '.$jsondata[1][0][5])
	->setCellValue('A3', 'Iglesia: '.$jsondata[1][0][1])
	->setCellValue('A5','Fecha')
	->setCellValue('B5','Iglesia')
	->setCellValue('C5','Ciudad')
	->setCellValue('D5','Estado')
	->setCellValue('E5','Pastor')
	->setCellValue('F5','Monto');



	
foreach ($jsondata[0] as &$value) {
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$cell ,$value[0])
		->setCellValue('B'.$cell ,$value[10])
		->setCellValue('C'.$cell ,$value[13])
		->setCellValue('D'.$cell ,$value[14])
		->setCellValue('E'.$cell ,$value[11])
		->setCellValue('F'.$cell ,$value[7]);

	$cell ++;
}

$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('E'.$cell, "Total: ")
	->setCellValue('F'.$cell, $total);




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
       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
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

$objPHPExcel->getActiveSheet()->getStyle('A2:A3')->applyFromArray($font11);

$objPHPExcel->getActiveSheet()->getStyle('A5:F5')->applyFromArray($font10);

$objPHPExcel->getActiveSheet()->getStyle('E'.$cell.':F'.$cell)->applyFromArray($fontTotal);

$objPHPExcel->getActiveSheet()->getStyle('F5:F'.$cell)->getNumberFormat()->setFormatCode("#,##0.00");

for($i = 'A'; $i <= 'E'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
}

$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight(25);


/////////////////////////////////////////////////////////////////////////



header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte.xlsx"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

?>