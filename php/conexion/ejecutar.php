<?php

function ejecuta($con, $query){
	$result = mysqli_query($con,$query);
	
	//if($result->num_rows == 1){
	//	return $result;
	//}
	$row = mysqli_fetch_array($result);
	return $row;
}

function consulta($con, $query){
	$respuesta = null;
	$cont = 0;
	$result = mysqli_query($con, $query);
	
	if($result == ""){
		return null;
		
	}

    while($row = mysqli_fetch_array($result)):
    
        $respuesta[$cont] = $row;
        $cont ++;
    
    endwhile;

    return $respuesta;
}

?>