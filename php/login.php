<?php
header('Access-Control-Allow-Origin: *');
include("conexion/conexion.php");
include("conexion/ejecutar.php");
$jsondata;

$user = $_POST["user"];
$pass = $_POST["pass"];


$jsondata = ejecuta($con,'SELECT VALIDA_USUARIO("'.$user.'","'.$pass.'")');
session_start();
if($jsondata[0] == 1){
	$_SESSION["APLICATIVO_ENTERSY"] = "CBBM";
	$_SESSION["CBBM_TYPE_USER"] = $jsondata[0];
	$user = consulta($con,'SELECT ID_USUARIO FROM CBBM_USUARIOS WHERE USUARIO = "'.$user.'"');
	$_SESSION["CBBM_USER_KEY"] = $user[0][0];
	$jsondata[0] = "OK";
}elseif ($jsondata[0] == 2) {
	$_SESSION["APLICATIVO_ENTERSY"] = "CBBM";
	$_SESSION["CBBM_TYPE_USER"] = $jsondata[0];
	$jsondata[0] = "OK";
}else{
	session_destroy();
	//$jsondata[0] = "NULL";
}



echo json_encode($jsondata, JSON_FORCE_OBJECT);

?>