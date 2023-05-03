<?php

function ejecuta($con, $query){
	$result = mysqli_query($con,$query);
	if(is_bool($result) == false)
	{
		$row = mysqli_fetch_array($result);
		return $row;
	}	
	return $result;	
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
