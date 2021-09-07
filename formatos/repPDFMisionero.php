<?php

include("../php/conexion/conexion.php");
include("../php/conexion/ejecutar.php");
require('../lib/fpdf/fpdf.php');


//$mes = '05';
$mes = substr($_POST['fecha'],5,2);
//$anio = '2017';
$anio = substr($_POST['fecha'],0,4);

session_start();
$usu = $_SESSION["CBBM_USER_KEY"];

$jsondata;


$jsondata[0] = consulta($con,"SELECT * FROM VW_CBBM_REPO_MASTER WHERE USU_REC = ".$usu." AND FECHA LIKE '%".$anio."-".$mes."%'");

if(!isset($jsondata[0])){
	header("Location: ../vistas/sinDatos.html");
	exit();
}
$jsondata[1] = consulta($con,'SELECT * FROM CBBM_USUARIOS WHERE ID_USUARIO = '.$usu);


$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image('../img/logo_ofimisA.jpg',10,10,70,30,'JPG');
$pdf->SetFont('Arial','B',13);
$pdf->SetXY(90,12);
$pdf->Cell(100,10,utf8_decode('COMPAÑERISMO BÍBLICO BAUTISTA DE'),0,1,'C');
$pdf->SetXY(90,18);
$pdf->Cell(100,10,utf8_decode('MÉXICO A.C.'),0,1,'C');
$pdf->SetFont('Arial','',9);
$pdf->SetXY(90,25);
$pdf->Cell(100,10,utf8_decode('Domicilio: Calle Hidalgo 217 Col. Centro, C.P. 62900 Jojutla,'),0,1,'C');
$pdf->SetXY(90,30);
$pdf->Cell(100,10,utf8_decode('Morelos.'),0,1,'C');
$pdf->SetXY(90,35);
$pdf->Cell(100,10,utf8_decode('Correo Electrónico: mvargasglz@yahoo.com.mx'),0,1,'C');
$pdf->ln(10);



$pdf->SetFont('Arial','',9);
$pdf->Cell(11,11,'Iglesia:',0,0,'C');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(12,11,utf8_decode($jsondata[1][0][1]),0,0,'L');
$pdf->ln(5);
$pdf->SetFont('Arial','',9);
$pdf->Cell(11,11,'Pastor:',0,0,'C');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(12,11,utf8_decode($jsondata[1][0][5]),0,0,'L');


$pdf->SetFillColor(168, 168, 168);
$pdf->SetXY(110,55);
$pdf->Cell(40,6,'Periodo',1,0,'L','true');
$pdf->SetXY(110,61);
$pdf->Cell(40,6,'Total',1,0,'L','true');
$pdf->SetXY(150,55);
$pdf->Cell(40,6,$mes."/".$anio,1,0,'R');
$pdf->SetXY(150,61);

$total = 0;
foreach ($jsondata[0] as &$value) {
	$total = $total + $value[7];
}
$pdf->Cell(40,6,"$".number_format($total, 2, '.', ','),1,0,'R');


$pdf->ln(20);

$pdf->Cell(30,6,'Fecha','BLT',0,'C','true');
$pdf->Cell(80,6,'Iglesia','BT',0,'C','true');
$pdf->Cell(50,6,'Pastor' ,'BT',0,'C','true');
$pdf->Cell(30,6,'Monto','BRT',0,'R','true');


foreach ($jsondata[0] as &$value) {
	$pdf->ln(6);
	$pdf->Cell(30,6,$value[0],'BLT',0,'L',false);
	$pdf->Cell(80,6,utf8_decode($value[2]),'BT',0,'L',false);
	$pdf->Cell(50,6,utf8_decode($value[3]),'BT',0,'L',false);
	$pdf->Cell(30,6,'$'.number_format($value[7], 2, '.', ',') ,'BRT',0,'R',false);
}


$image1 = "../img/Firma y Sello.png";
$pdf->Cell(140,8,$pdf->Image($image1, 65, $pdf->GetY()+15, 70));

$pdf->ln(60);
$pdf->setX(30);
$pdf->SetFont('Arial','I',9);
$pdf->multicell(140, 5, utf8_decode('Y Jesús se acercó y les habló diciendo: Toda potestad me es dada en el cielo y en la tierra. Por tanto, id, y haced discípulos a todas las naciones, bautizándolos en el nombre del Padre, y del Hijo, y del Espíritu Santo; enseñándoles que guarden todas las cosas que os he mandado; y he aquí yo estoy con vosotros todos los días, hasta el fin del mundo. Amén.'),0,'C');

$pdf->ln(10);
$pdf->setX(30);
$pdf->Cell(140,6,'Mateo 28:18-20',0,0,'C');




$pdf->Output();



?>