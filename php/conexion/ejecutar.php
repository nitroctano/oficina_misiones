<?php

function ejecuta($query){
	$result = mysql_query($query);
	if($result == 1){
		return $result;
	}
	$row = mysql_fetch_array($result);
	return $row;
}

function consulta($query){
	$respuesta = null;
	$cont = 0;
	$result = mysql_query($query);
	
	if($result == ""){
		return null;
		
	}

    while($row = mysql_fetch_array($result)):
    
        $respuesta[$cont] = $row;
        $cont ++;
    
    endwhile;

    return $respuesta;
}

?>