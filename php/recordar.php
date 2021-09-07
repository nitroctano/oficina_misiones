<?php

include("conexion/conexion.php");
include("conexion/ejecutar.php");
$jsondata;

$user = $_POST["usuarioRec"];
$email = $_POST["correoRec"];

$jsondata = ejecuta($con,'SELECT VALIDA_RECORD("'.$user.'","'.$email.'")');

if($jsondata[0] != '0'){

	$usuario = consulta($con,'SELECT PASS FROM CBBM_USUARIOS WHERE USUARIO = "'.$user.'"');

	require '../lib/PHPMailer/PHPMailerAutoload.php';
	$mail = new PHPMailer;
	$mail->setFrom('mvargasglzqro@gmail.com', 'Oficina de Misiones');
	$mail->addAddress($email, $user);
	$mail->Subject = utf8_decode('Recuperacion de contraseña');
	$mail->isHTML(true);
	//$mail->Body = 'su contraseña es '.$usuario[0][0];
	$mail->Body = utf8_decode('<head><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>'.
					'<body style="background-color: lightgray; font-family: sans-serif; height:100%; padding-top:1px">'.
						'<div style="width: 80%; margin: 10%; background-color: white; padding: 20px;">'.
							'<div style="width: 30%">'.
								'<img src="http://www.prod.sistema-ofac.org/img/logo_ofimisA.png" style="width: 100%">'.
							'</div>'.
							'<div style="font-size: 2em; text-align: center; font-weight: bold; color: #2F5597">Su usuario y contraseña del sistema son:</div>'.
							'<div style="font-size: 1.8em; text-align: center; margin: 40px 0">'.
								'<div>Usuario: <span style="font-weight: bold; color: #2E75B6">'.$user.'</span></div>'.
								'<div>Contraseña: <span style="font-weight: bold; color: #2E75B6">'.$usuario[0][0].'</span></div>'.
							'</div>'.
							'<div style="width: 90%; height: 15px; background-color: #AFABAB; margin-left: 5%"></div>'.
							'<div style="text-align: center; margin-top: 30px; font-size: 1.8em; ">'.
								'<a href="http://www.sistema-ofac.org" style="color: #2E75B6">www.sistema-ofac.org</a>	'.
							'</div>'.
						'</div>'.
					'</body>');
	$mail->send();

}

echo json_encode($jsondata, JSON_FORCE_OBJECT);

?>