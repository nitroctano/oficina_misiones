<?php
session_start();
if(isset($_SESSION["APLICATIVO_ENTERSY"])){
  if($_SESSION["APLICATIVO_ENTERSY"] == "CBBM"){
  	if($_SESSION["CBBM_TYPE_USER"] == 2){
  		header("Location: ../vistas/administrador.php");
    	exit();
  	}else if($_SESSION["CBBM_TYPE_USER"] == 1){
  		header("Location: ../vistas/iglesia.php");
    	exit();
  	}else{
  		header("Location: ../");
 		exit();		
  	}
  }
}else{
  header("Location: ../");
  exit();
}

?>