<?php
include("conexion/conexion.php");
include("conexion/ejecutar.php");
$jsondata;
$cont = 0;

switch($_POST["operacion"]){

	case 'nuevoRegistro':
		
		$iglesia = $_POST["iglesia"];
		$pastor = $_POST["pastor"];
		$pais = $_POST["pais"];
		$estado = $_POST["estado"];
		$ciudad = $_POST["ciudad"];
		$direccion = $_POST["direccion"];
		$telefono = $_POST["telefono"];
		$email = $_POST["mail"];
		$usuario = $_POST["usuario"];
		$pass = $_POST["pass"];
		$clave1 = $_POST["clave1"];
		$clave2 = $_POST["clave2"];
		$clave3 = $_POST["clave3"];

		$clave = $clave1 . $clave2 . $clave3;

		$respuesta = ejecuta('SELECT VALIDA_CLAVE("'.$clave.'")');

		if($respuesta[0] == 0){
			$jsondata[0] = "CI";
			break;
		}
		
		if(isset($_POST["extranjero"])){
			$extranjero = 'S';
		}else{
			$extranjero = 'N';
		}
		
		$jsondata = ejecuta('SELECT NUEVO_USUARIO("'.$iglesia.'","'.$usuario.'","'.$pass.'","'.$pastor.'","'.$direccion.'", "'.$telefono.'", "'.$email.'","'.$extranjero.'","'.$clave.'","'.$pais.'","'.$estado.'","'.$ciudad.'")');


		if($jsondata[0] == 1){

			require '../lib/PHPMailer/PHPMailerAutoload.php';
			$mail = new PHPMailer;
			$mail->setFrom('mvargasglzqro@gmail.com', 'Oficina de Misiones');
			$mail->addAddress($email, $usuario);
			$mail->Subject = utf8_decode('Bienvenido al sistema del CBBM');
			$mail->isHTML(true);
			//$mail->Body = 'su contraseña es '.$usuario[0][0];
			$mail->Body = utf8_decode('<body style="background-color: lightgray; font-family: sans-serif; height:100%; padding-top:1px">'.
								'<div style="width: 80%; margin: 10%; background-color: white; padding: 20px;">'.
									'<div style="width: 30%">'.
										'<img src="http://www.prod.sistema-ofac.org/img/logo_ofimisA.png" style="width: 100%">'.
									'</div>'.
									'<div style="font-size: 2em; text-align: center; font-weight: bold; color: #AFABAB">Bienvenido al <span style="color: #2F5597">Sistema de Registro de Ofrendas</span></div>'.
									'<div style="font-size: 1.8em; text-align: center; margin: 40px 0">'.
										'<div>Usuario: <span style="font-weight: bold; color: #2E75B6">'.$usuario.'</span></div>'.
										'<div>Contraseña: <span style="font-weight: bold; color: #2E75B6">'.$pass.'</span></div>'.
									'</div>'.
									'<div style="width: 90%; height: 15px; background-color: #AFABAB; margin-left: 5%"></div>'.
									'<div style="text-align: center; margin-top: 30px; font-size: 1.8em; ">'.
										'<a href="http://www.sistema-ofac.org" style="color: #2E75B6">www.sistema-ofac.org</a>	'.
									'</div>'.
								'</div>'.
							'</body>');
			$mail->send();
		}

		break;

}


echo json_encode($jsondata, JSON_FORCE_OBJECT);

?>