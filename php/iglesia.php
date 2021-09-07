<?php

include("conexion/conexion.php");
include("conexion/ejecutar.php"); 
$jsondata;

session_start();


$user = $_SESSION["CBBM_USER_KEY"];


switch($_POST["operacion"]){

	case 'cargaInicial':

		$jsondata[0] = consulta($con,'SELECT * FROM CBBM_USUARIOS WHERE ID_USUARIO = '.$user);

		$jsondata[1] = consulta($con,'SELECT * FROM VW_CBBM_PROYECTOS ORDER BY IDENT_ADMIN');

		$jsondata[2] = consulta($con,'SELECT * FROM CBBM_OFRENDAS_ENC WHERE ID_USUARIO = '.$user.' order by fecha desc');

		$jsondata[3] = consulta($con,'SELECT * FROM VW_CBBM_OFRENDAS_DET WHERE ID_USUARIO = '.$user);

		break;

	case 'agregaPlantilla':

		$proyecto = $_POST["keyMisionero"];

		$monto = $_POST["cantMisionero"];

		$tipo = $_POST["tipoProyecto"];

		$jsondata = ejecuta($con,'SELECT ADD_PLANTILLA('.$user.','.$proyecto.','.$monto.',"'.$tipo.'")');

		break;

	case 'cargaPlantilla':

		$jsondata = consulta($con,'SELECT * FROM VW_CBBM_PLANTILLA WHERE ID_USUARIO = '.$user);

		break;

	case 'enviaOfrenda':

		$tipoDeposito = $_POST["tipoDeposito"];

		$jsondata = ejecuta($con,'SELECT GENERA_OFRENDA('.$user.',"'.$tipoDeposito.'");');

		break;

	case 'editMonto':

		$key = $_POST["key"];

		$monto = $_POST["monto"];

		$jsondata = ejecuta($con,'SELECT EDIT_MONTO('.$key.','.$monto.');');

		break;

	case 'elimPlantilla':

		$key =$_POST["key"];

		$jsondata = ejecuta($con,'SELECT ELIM_PLANTILLA('.$key.');');

		break;

	case 'actualizaUsuario':

		$iglesia = $_POST["nomIglesia"];

		$pastor = $_POST["nomPastor"];

		$direccion = $_POST["direccion"];

		$telefono = $_POST["telefono"];

		$mail = $_POST["correoElect"];

		$usuario = $_POST["nomUsuario"];

		$pass = $_POST["contrasena"];

		$pais = $_POST["pais"];

		$estado = $_POST["estado"];

		$ciudad = $_POST["ciudad"];

		$jsondata = ejecuta($con,'SELECT ACTUALIZA_USUARIO("'.$iglesia.'","'.$usuario.'","'.$pass.'","'.$pastor.'","'.$direccion.'",'.$telefono.',"'.$mail.'","'.$pais.'","'.$estado.'","'.$ciudad.'");');

		break;

	case 'cargaAvance':

		$result = consulta($con,'SELECT TIPO FROM CBBM_USUARIOS WHERE ID_USUARIO = '.$user);

		if($result[0][0] == 1){

			$jsondata = 'N';

		}else{

			$jsondata = consulta($con,'SELECT SUM(MONTO) FROM CBBM_OFRENDAS_DET WHERE ID_MISIONERO = (SELECT ID_MISIONERO FROM CBBM_PROYECTOS WHERE ID_USUARIO = '.$user.')');

		}

		break;

}

echo json_encode($jsondata, JSON_FORCE_OBJECT);

?>