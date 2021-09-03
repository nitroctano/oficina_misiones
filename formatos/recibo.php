<?php
header('Access-Control-Allow-Origin: *');
include("../php/conexion/conexion.php");
include("../php/conexion/ejecutar.php");

$fecha_actual= date("Y/m/d");
$folio = $_POST["folio"];

$jsondata;

$jsondata[0] = consulta('SELECT * FROM VW_CBBM_OFRENDAS_DET WHERE ID_OFRENDA_ENC = '.$folio);
$jsondata[1] = consulta('SELECT * FROM CBBM_USUARIOS WHERE ID_USUARIO = '.$jsondata[0][0][2]);
$jsondata[2] = consulta('SELECT * FROM CBBM_OFRENDAS_ENC WHERE ID_OFRENDA_ENC = '.$folio);
//$jsondata[3] = consulta('SELECT USU.NOMBRE, DET.MONTO FROM CBBM_USUARIOS USU WHERE USU.ID_USUARIO IN (SELECT ID_MISIONERO FROM CBBM_OFRENDAS_DET DET WHERE DET.ID_OFRENDA_ENC = '.$folio.' AND DET.MISIONERO = "S") AND USU.EXTRANJERO = "S"');


$jsondata[3] = consulta('SELECT USU.IDENT_ADMIN, DET.MONTO FROM CBBM_OFRENDAS_DET DET LEFT JOIN CBBM_USUARIOS USU ON USU.ID_USUARIO = DET.ID_MISIONERO WHERE USU.EXTRANJERO = "S" AND DET.MISIONERO = "S" AND DET.ID_OFRENDA_ENC = '.$folio);

$jsondata[4] = consulta('SELECT USU.IDENT_ADMIN, DET.MONTO FROM CBBM_OFRENDAS_DET DET LEFT JOIN CBBM_USUARIOS USU ON USU.ID_USUARIO = DET.ID_MISIONERO WHERE USU.EXTRANJERO = "N" AND DET.MISIONERO = "S" AND DET.ID_OFRENDA_ENC = '.$folio);

$jsondata[5] = consulta('SELECT PRO.NOMBRE, DET.MONTO FROM CBBM_OFRENDAS_DET DET LEFT JOIN CBBM_PROYECTOS PRO ON PRO.ID_PROYECTO = DET.ID_MISIONERO WHERE DET.MISIONERO = "N" AND DET.ID_OFRENDA_ENC = '.$folio);

//$empleado = $_GET["e"];

require('../lib/fpdf/fpdf.php');

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
$pdf->Cell(100,10,utf8_decode('Domicilio: Calle Villas Jojutla El Doral Condominio D, casa 38,'),0,1,'C');
$pdf->SetXY(90,30);
$pdf->Cell(100,10,utf8_decode('C.P.62900 Colonia Cuauhtémoc, Jojutla Morelos.'),0,1,'C');
$pdf->SetXY(90,35);
$pdf->Cell(100,10,utf8_decode('Correo Electrónico: mvargasglz@yahoo.com.mx'),0,1,'C');
$pdf->ln(10);


$pdf->Cell(13,11,'No.Folio:',0,0,'C');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(12,11,str_pad($jsondata[2][0][7],5,"0",STR_PAD_LEFT),0,0,'L');
$pdf->ln(5);
$pdf->SetFont('Arial','',9);
$pdf->Cell(21,11,'Folio Contable:',0,0,'C');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(12,11,$jsondata[2][0][8],0,0,'L');
$pdf->ln(5);
$pdf->SetFont('Arial','',9);
$pdf->Cell(20,11,'Recibimos de:',0,0,'C');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(12,11,utf8_decode($jsondata[1][0][1]),0,0,'L');
$pdf->ln(5);
/*$pdf->SetFont('Arial','',9);
$pdf->Cell(15,11,'Concepto:',0,0,'C');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(12,11,'Ofrenda del mes',0,0,'L');
$pdf->ln(5);*/
$pdf->SetFont('Arial','',9);
$pdf->Cell(10,11,'Pastor:',0,0,'C');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(12,11,utf8_decode($jsondata[1][0][5]),0,0,'L');
$pdf->ln(5);
$pdf->SetFont('Arial','',9);
$pdf->Cell(14,11,'Direccion:',0,0,'C');
$pdf->SetFont('Arial','B',9);
$pdf->ln(3);
$pdf->SetX(25);
//$pdf->Cell(12,11,utf8_decode($jsondata[1][0][13]." ".$jsondata[1][0][14]." ".$jsondata[1][0][15]." ".$jsondata[1][0][6]),0,0,'L');
//$pdf->Cell(12,11,utf8_decode($jsondata[1][0][6]." ".$jsondata[1][0][15]." ".$jsondata[1][0][14]." ".$jsondata[1][0][13]),0,0,'L');
$pdf->multicell(160, 5, utf8_decode($jsondata[1][0][6]." ".$jsondata[1][0][15]." ".$jsondata[1][0][14]." ".$jsondata[1][0][13]),0,'L');

$pdf->SetFillColor(168, 168, 168);
$pdf->SetXY(110,60);
$pdf->Cell(40,6,'Fecha movimiento',1,0,'L','true');
$pdf->SetXY(110,66);
$pdf->Cell(40,6,'Tipo',1,0,'L','true');
$pdf->SetXY(110,72);
$pdf->Cell(40,6,'Total',1,0,'L','true');
$pdf->SetXY(150,60);
$pdf->Cell(40,6,$jsondata[2][0][2],1,0,'R');
$pdf->SetXY(150,66);
$pdf->Cell(40,6,utf8_decode($jsondata[2][0][4]),1,0,'R');
$pdf->SetXY(150,72);
$pdf->Cell(40,6,'$'.number_format($jsondata[2][0][3], 2, '.', ',')	,1,0,'R');

///////////////////////////////////////// CAMPOS EXTRANJEROS

if(isset($jsondata[3])){

	$total = 0;
	foreach($jsondata[3] as &$valor){
		$total = $total + $valor[1];
	}

	$pdf->ln(20);
	$pdf->Cell(150,6,'Campos al extranjero','BLT',0,'L','true');
	$pdf->Cell(30,6,'$'.number_format($total, 2, '.', ',') ,'BRT',0,'R','true');

	foreach($jsondata[3] as &$valor){
		$pdf->ln(6);
		$pdf->Cell(150,6,utf8_decode($valor[0]),'B',0,'L');
		$pdf->Cell(30,6,'$'.$valor[1],'B',0,'R');
	}
}


////////////////////////////////////CAMPOS NACIONALES
if(isset($jsondata[4])){

	$total = 0;
	foreach($jsondata[4] as &$valor){
		$total = $total + $valor[1];
	}

	$pdf->ln(20);
	$pdf->Cell(150,6,'Campos nacionales','BLT',0,'L','true');
	$pdf->Cell(30,6,'$'.number_format($total, 2, '.', ','),'BRT',0,'R','true');

	foreach($jsondata[4] as &$valor){
		$pdf->ln(6);
		$pdf->Cell(150,6,utf8_decode($valor[0]),'B',0,'L');
		$pdf->Cell(30,6,$valor[1],'B',0,'R');
	}
}


/////////////////////////////////////////// CAMPOS PROYECTOS
if(isset($jsondata[5])){

	$total = 0;
	foreach($jsondata[5] as &$valor){
		$total = $total + $valor[1];
	}

	$pdf->ln(20);
	$pdf->Cell(150,6,'Otros Proyectos','BLT',0,'L','true');
	$pdf->Cell(30,6,'$'.number_format($total, 2, '.', ','),'BRT',0,'R','true');
	foreach($jsondata[5] as &$valor){
		$pdf->ln(6);
		$pdf->Cell(150,6,utf8_decode($valor[0]),'B',0,'L');
		$pdf->Cell(30,6,'$'.$valor[1],'B',0,'R');
	}

}

////////////////////////////////////////////// COMENTARIOS

$pdf->ln(12);
$pdf->SetFont('Arial','',9);
$pdf->Cell(23,5,'Observaciones:',0,0,'C');
$pdf->SetFont('Arial','B',9);
$pdf->MultiCell(155,5,utf8_decode($jsondata[2][0][6]),0,'L');


$image1 = "../img/Firma y Sello.png";
$pdf->Cell(140,8,$pdf->Image($image1, 65, $pdf->GetY()+15, 70));
//$pdf->Image('../img/Firma y Sello.png',65,250,70,30,'PNG');

$pdf->ln(60);
$pdf->setX(30);
$pdf->SetFont('Arial','I',9);
$pdf->multicell(140, 5, utf8_decode('Y Jesús se acercó y les habló diciendo: Toda potestad me es dada en el cielo y en la tierra. Por tanto, id, y haced discípulos a todas las naciones, bautizándolos en el nombre del Padre, y del Hijo, y del Espíritu Santo; enseñándoles que guarden todas las cosas que os he mandado; y he aquí yo estoy con vosotros todos los días, hasta el fin del mundo. Amén.'),0,'C');

$pdf->ln(10);
$pdf->setX(30);
$pdf->Cell(140,6,'Mateo 28:18-20',0,0,'C');




$pdf->Output();



?>