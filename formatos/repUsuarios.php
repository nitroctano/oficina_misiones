<?php
header('Access-Control-Allow-Origin: *');
include("../php/conexion/conexion.php");
include("../php/conexion/ejecutar.php");

$usuarios;

$usuarios = consulta($con,'SELECT * FROM CBBM_USUARIOS');


require('../lib/fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',15);
$pdf->Cell(190,11,'Reporte de Usuarios',1,0,'C');
$pdf->SetFont('Arial','B',12);
$pdf->ln(20);

$altura = 30;
$left = 10;

if(isset($usuarios)){
	$cont = 0;
	foreach ($usuarios as $usuario => $value) {
		$cont ++;
		$pdf->SetX($left);
		$pdf->Cell(95,6,utf8_decode('Usuario: '.$value[2]),'LTR',0,'L');
		$pdf->ln(6);
		$pdf->SetX($left);
		$pdf->Cell(95,6,utf8_decode('Contraseña: '.$value[3]),'LR',0,'L');
		$pdf->ln(6);
		$pdf->SetX($left);
		$pdf->Cell(95,6,utf8_decode('Pastor: '.$value[5]),'LBR',0,'L');
		if($cont == 1){
			$left = 105;
			$pdf->ln(-12);
		}
		if($cont == 2){
			$left = 10;
			$altura = $altura + 18;
			$cont = 0;
			$pdf->ln(6);

			if($pdf->GetY() > 260){
				$pdf->ln(20);
			}
		}
	}
}


/*

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


*/

$pdf->Output();



?>