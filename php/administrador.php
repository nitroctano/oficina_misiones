<?php

include("conexion/conexion.php");

include("conexion/ejecutar.php");

$jsondata;




switch($_POST["operacion"]){

	case 'cargaInicial':

		$jsondata[0] = consulta('SELECT ID_PROYECTO, NOMBRE, TIPO FROM CBBM_PROYECTOS ORDER BY NOMBRE');
        
		$jsondata[1] = consulta('SELECT ID_USUARIO, NOMBRE, MISIONERO,IDENT_ADMIN, PASTOR FROM CBBM_USUARIOS WHERE TIPO <> 2 ORDER BY PASTOR');

		$jsondata[2] = consulta('SELECT * FROM CBBM_OFRENDAS_ENC WHERE ESTATUS = "PE"');

		$jsondata[3] = consulta('SELECT * FROM CBBM_OFRENDAS_DET WHERE ID_OFRENDA_ENC IN (SELECT ID_OFRENDA_ENC FROM CBBM_OFRENDAS_ENC WHERE ESTATUS = "PE")');

        $jsondata[4] = consulta('SELECT * FROM VW_CBBM_OFRENDAS_ACTUAL ORDER BY IDENT_ADMIN');

        $jsondata[5] = consulta('SELECT * FROM CBBM_OFRENDAS_ENC WHERE ESTATUS != "PE" ORDER BY FECHA DESC');

        $jsondata[6] = consulta('SELECT * FROM CBBM_OFRENDAS_DET WHERE ID_OFRENDA_ENC IN (SELECT ID_OFRENDA_ENC FROM CBBM_OFRENDAS_ENC WHERE ESTATUS != "PE")');
        $jsondata[7] = consulta('SELECT DISTINCT USU_REC, NOM_REC FROM VW_CBBM_REPO_MASTER ORDER BY NOM_REC');

        $jsondata[8] = consulta('SELECT DISTINCT USU_ENVIA, NOM_ENVIA, PAS_ENVIA FROM VW_CBBM_REPO_MASTER ORDER BY NOM_ENVIA');

		break;
		
	case 'agregaProyecto':
		
		$proyecto = $_POST['nomProyecto'];
		
		$jsondata = ejecuta('SELECT NUEVO_PROYECTO("'.$proyecto.'")');
	
		break;

    case 'estatusProyecto':
        
		$proyecto = $_POST['idProyecto'];

		$jsondata = ejecuta('SELECT CAMBIA_ESTATUS_PROYECTO('.$proyecto.')');

    	break;
    case 'estatusUsuario':
    
    	$idUsuario = $_POST['idUsuario'];
    	
    	$jsondata = ejecuta('SELECT CAMBIA_ESTATUS_USUARIO('.$idUsuario.')');
    	
    
    	break;
    case 'aceptaOfrenda':

    	$ofrenda = $_POST['ofrendaKey'];
        $comentarios = $_POST["comentarios"];

        if(isset($_POST["chkContable"])){
            $folio = $_POST["folioContable"];
        }else{
            $folio = "";
        }

        if(isset($_POST["chkFecha"])){
            $fecha = $_POST["cambioFecha"];
            $fecha = ejecuta('UPDATE CBBM_OFRENDAS_ENC SET FECHA = "'.$fecha.'" WHERE ID_OFRENDA_ENC = '.$ofrenda);
        }

    	$jsondata = ejecuta('SELECT ACEPTA_OFRENDA('.$ofrenda.', "'.$folio.'", "'.$comentarios.'")');

    	break;
    	
    case 'editNomProyecto':
    
    	$key = $_POST["key"];
    	
    	$nomProyecto = $_POST["nomProyecto"];
    	
    	$jsondata = ejecuta('SELECT EDIT_NOM_PROYECTO('.$key.',"'.$nomProyecto.'");');
    	
    	
    	
    	break;

    case 'editIdent':
        
        $key = $_POST["key"];
    	
    	$ident= $_POST["ident"];
    	
    	$jsondata = ejecuta('SELECT EDIT_IDENT('.$key.',"'.$ident.'");');
        

        break;

    case 'rechazaOfrenda':

        $ofrenda = $_POST['ofrenda'];

        $comentarios = $_POST['comentarios'];

        $jsondata = ejecuta('SELECT RECHAZA_OFRENDA('.$ofrenda.',"'.$comentarios.'")');

        break;

    case 'salidaOfrenda':

        $count = count($_POST);
        $total = 0;
        $key;
        $tipo;
        $monto;
        $enc;
        $respuesta;

        for($i=0;$i<($count-1);$i++){
            $reg = $_POST["reg".$i];

            list($key[$i], $tipo[$i], $monto[$i]) = split(',',$reg);
            $total = $total + $monto[$i];

        }

        $jsondata[0] = ejecuta('SELECT SALIDA_ENC('.$total.')');

        $count = count($key);

        for($co=0; $co<($count); $co++){

            $jsondata[1] = 'SELECT SALIDA_DET('.$jsondata[0][0].','.$key[$co].',"'.$tipo[$co].'",'.$monto[$co].')';
            
            $respuesta = ejecuta('SELECT SALIDA_DET('.$jsondata[0][0].','.$key[$co].',"'.$tipo[$co].'",'.$monto[$co].')');

        }
        

        break;
    case 'generaCodigo':

        $jsondata = ejecuta('SELECT GENERA_CLAVE()');

        break;
    case 'filtraFechaHist':

        $fecha = $_POST["fecha"];

        $jsondata = consulta('SELECT * FROM CBBM_OFRENDAS_ENC WHERE ESTATUS != "PE" AND FECHA like "%'.$fecha.'%"');

        break;

    case 'regresaOfrenda':
    $folio = $_POST['folio'];
        $jsondata = ejecuta('SELECT REGRESA_OFRENDA('.$folio.')');
        break;
    
}



echo json_encode($jsondata, JSON_FORCE_OBJECT);



?>