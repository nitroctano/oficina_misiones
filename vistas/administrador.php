<?php

session_start();

if(isset($_SESSION["APLICATIVO_ENTERSY"])){
  if($_SESSION["APLICATIVO_ENTERSY"] != "CBBM"){
  	if($_SESSION["CBBM_TYPE_USER"] != 2){
  		header("Location: ../");
    	exit();
  	}
  }
}else{
  header("Location: ../");
  exit();
}

?>

<!DOCTYPE html>
<html>
	<head>
		 <title>Administrador</title>
    	 <meta charset="UTF-8">
    	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
    	 <script src="../lib/jquery-3.1.1.min.js"></script>
    	 <link rel="shortcut icon" href="../img/icono.png">
    	 <link href="../lib/bootstrap.min.css" type="text/css" rel="stylesheet">
    	 <link href="../css/estilos.css" rel="stylesheet" type="text/css">
    	 <script src="../js/administrador.js"></script>
	</head>
	<body onload="nobackbutton();">
		<div id="sombra" class="hide"></div>
		<div id="loading" class="hide">
			<div class="sombra"></div>
			<div id="cargando">Cargando...</div>
			<div id="loadingImg2"></div>
			<div id="loadingImg">
				<img src="../img/cont.png" id="loadingCont">
				<img src="../img/cont.png" id="loadingCont2">
			</div>
			<div id="loadingImg3"></div>
		</div>

		<div id="verde" class="alert alert-success mensaje hide"></div>
        <div id="rojo" class="alert alert-danger mensaje hide"></div>
		<div id="encabezado">
			<img src="../img/logo_ofimisA.png" id="logoOficina" class="hidden-xs hidden-sm">
			<img src="../img/logo_ofimisA.png" id="logoOficinaMovil" class="hidden-md hidden-lg">
			<div id="etiquetaAdmin">Administrador</div>
			<div id="iconoMenu" class="hidden-md hidden-lg">
				<div></div><div></div><div></div>
			</div>
			<div id="barraMenuMovil" class="hidden-lg hidden-md">

				<ul id="menuLat">
				<li onclick="pantallas('proyectosInd')"><img src="../img/proyectos.png" style="width: 35px;margin-right: 10px;">Proyectos</li>
				<li onclick="pantallas('Usuarios')"><img src="../img/usuarios.png" style="width: 35px;margin-right: 10px;">Usuarios</li>
				<li onclick="pantallas('ofrendasRecibidas')"><img src="../img/revision.png" style="width: 35px;margin-right: 10px;">Revisión de Ofrendas</li>
				<li onclick="pantallas('enviarOfrendas')"><img src="../img/enviar.png" style="width: 35px;margin-right: 10px;">Envío de ofrendas</li>
				<li onclick="pantallas('historialOfrendas')"><img src="../img/historial.png" style="width: 35px;margin-right: 10px;">Historial de ofrendas</li>
				<li onclick="pantallas('reportes')"><img src="../img/reportes.png" style="width: 35px;margin-right: 10px;">Reportes</li>
				<li onclick="pantallas('codigos')"><img src="../img/codigo.png" style="width: 35px;margin-right: 10px;">Códigos</li>
				<li onclick="window.location.href='../php/cerrarSesion.php'"><img src="../img/logout.png" style="width: 35px;margin-right: 10px;">Cerrar Sesión</li>
			</ul>
			</div>
		</div>
		<div id="barraMenu" class="hidden-xs hidden-sm">
			<div id="tituloMenu">Menú</div>
			<ul id="menuLat">
				<li onclick="pantallas('proyectosInd')"><img src="../img/proyectos.png" style="width: 35px;margin-right: 10px;">Proyectos</li>
				<li onclick="pantallas('Usuarios')"><img src="../img/usuarios.png" style="width: 35px;margin-right: 10px;">Usuarios</li>
				<li onclick="pantallas('ofrendasRecibidas')"><img src="../img/revision.png" style="width: 35px;margin-right: 10px;">Revisión de Ofrendas</li>
				<li onclick="pantallas('enviarOfrendas')"><img src="../img/enviar.png" style="width: 35px;margin-right: 10px;">Envío de ofrendas</li>
				<li onclick="pantallas('historialOfrendas')"><img src="../img/historial.png" style="width: 35px;margin-right: 10px;">Historial de ofrendas</li>
				<li onclick="pantallas('reportes')"><img src="../img/reportes.png" style="width: 35px;margin-right: 10px;">Reportes</li>
				<li onclick="pantallas('codigos')"><img src="../img/codigo.png" style="width: 35px;margin-right: 10px;">Códigos</li>
				<li onclick="window.location.href='../php/cerrarSesion.php'"><img src="../img/logout.png" style="width: 35px;margin-right: 10px;">Cerrar Sesión</li>
			</ul>
		</div>
		<div id="contenedor">
			<div id="proyectosInd" class="pantalla hide">
				<div id="formularioProyectos">
					<form onSubmit="return false" id="formProyectos">
						<div class="vertical-group" style="width: 300px; position: relative;">
							<input type="hidden" name="operacion" value="agregaProyecto">
							<label id="nombreProyecto" style="font-size: 18px;">Nombre del Proyecto</label>
							<input class="form-control" type="text" name="nomProyecto" id="nomProyecto" placeholder="Nombre" required autocomplete="off">
						</div>
						<div class="vertical-group" style="vertical-align: bottom;">
							<input class="btn btn-info" type="submit" value="Agregar">
						</div>
					</form>
				</div>
				<div id="tablaProyectos">
					<div class="tituloModulo">Proyectos independientes</div>
					<div class="tabla">
						<table class="tabla table table-hover" id="proyectosVistaAdmin">
							<thead>
								<th>Nombre del proyecto</th>
								<th>Activo</th>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>





			<div id="Usuarios" class="pantalla hide">
				<div class="buscador">
					<label for="busUsuario">Buscar</label>
					<input type="text" name="busUsuario" class="form-control" id="buscadorUsu">
				</div>
				<div class="tituloModulo">Usuarios</div>
				<div class="tabla" >
					<table class="tabla table table-hover" id="vistaUsuariosAdmin">
						<thead>
							<th>Iglesia</th>
							<th>Pastor</th>
							<th>Identificador</th>
							<th>Misionero</th>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>	
			</div>



			<div id="ofrendasRecibidas" class="pantalla">
				<div id="tablaOfrendasRecibidas" class="modulo">
					<div class="tituloModulo">Revisión de Ofrendas</div>
					<div class="tabla">
						<table class="tabla table table-hover">
							<thead>
								<th>Fecha</th>
								<th>Iglesia</th>
								<th>Pastor</th>
								<th>Monto</th>
							</thead>
							<tbody>
								
							</tbody>
						</table>
						<div id="sinOfrendas" class="hide">Sin Ofrendas</div>
					</div>
				</div>

				<div id="aprobacionOfrendas" class="modulo hide">
					<div class="btnAtras manita btn btn-success">Atras</div>
					<div class="tituloModulo">Detalle Ofrenda</div>
					<div id="infoDetalle">
						<div class="horizontal-group" style="float: left;">
							<!--<input type="hidden" id="ofrendaKey">-->
							<div id="detFecha">Fecha: <strong></strong></div>
							<div id="detTotal">Total: <strong></strong></div>
							<div id="detTipo">Tipo: <strong></strong></div>
						</div>
						<div class="horizontal-group">
							
							<!--<div id="detEstatus">Estatus: <strong></strong></div>
							<div id="detObs">Observaciones: <strong></strong></div>-->
						</div>
					</div>
					<table class="tabla table table-hover">
						<thead>
							<th>Misionero</th>
							<th>Monto</th>
						</thead>
						<tbody>
							
						</tbody>
					</table>
					<div id="operacionesOfrenda">
						<div id="rechazaOfrenda" class="btn btn-danger">No coincide</div>
						<div id="aceptaOfrenda" class="btn btn-success">Aceptar</div>
					</div>
				</div>
			</div>



			<div id="enviarOfrendas" class="pantalla hide">
				<div class="buscador">
					<label for="busOfrendas">Buscar</label>
					<input type="text" name="busOfrendas" class="form-control" id="buscadorOfr">
				</div>
				<div class="tituloModulo">Total ofrendas:</div>
				<form onSubmit="return false" id="formEnviarOfrendas">
					<div class="tabla">
						<table class="tabla table table-hover">
							<thead>
								<th>Todos<div class="fondoCheck"><div class="radioCheck"></div></div></th>
								<th style="vertical-align: middle;">Nombre</th>
								<th style="vertical-align: middle;">Disponible</th>
								<th style="vertical-align: middle;">Monto</th>
							</thead>
							<tbody>
									
							</tbody>
						</table>
					</div>
					<div class="btn btn-info btn-lg btn-block" id="btnOfrendas">Enviar ofrendas</div>
				</form>
			</div>


			<div id="codigos" class="pantalla hide">
	        	<div id="contCodigo">
	        		<span id="campo1">####</span>
	        		<span>-</span>
	        		<span id="campo2">####</span>
	        		<span>-</span>
	        		<span id="campo3">####</span>
	        	</div>
	        	<div id="btnGeneraCodigo" class="btn btn-block btn-info">Generar código</div>
	        </div>



	        <div id="historialOfrendas" class="pantalla hide">
	        	<div id="tablaOfrendasHistorial" class="modulo">
	        		<div class="buscador">
		        		<div class="option-buscador">
		        			<label for="texto">Buscar</label>
		        			<input type="text" name="texto" class="form-control">
		        		</div>
		        		<div class="option-buscador">
		        			<label for="mes">Mes</label>
		        			<select class="form-control" name="mes" id="mesHist">
		        				<option value="">Todo</option>
		        				<option value="01">Enero</option>
		        				<option value="02">Febrero</option>
		        				<option value="03">Marzo</option>
		        				<option value="04">Abril</option>
		        				<option value="05">Mayo</option>
		        				<option value="06">Junio</option>
		        				<option value="07">Julio</option>
		        				<option value="08">Agosto</option>
		        				<option value="09">Septiembre</option>
		        				<option value="10">Octubre</option>
		        				<option value="11">Noviembre</option>
		        				<option value="12">Diciembre</option>
		        			</select>
		        		</div>
		        		<div class="option-buscador">
		        			<label for="anio">Año</label>
		        			<select class="form-control" name="anio" id="anioHist">
		        				<option value="">Todo</option>
		        				<option value="2017">2017</option>
		        				<option value="2018">2018</option>
		        				<option value="2019">2019</option>
								<option value="2020">2020</option>
								<option value="2021">2021</option>
								<option value="2022">2022</option>
								<option value="2023">2023</option>
								<option value="2024">2024</option>
								<option value="2025">2025</option>
		        			</select>
		        		</div>
		        	</div>
					<div class="tituloModulo">Historial de Ofrendas</div>
					<div class="tabla">
						<table class="tabla table table-hover">
							<thead>
								<th>Fecha</th>
								<th>Iglesia</th>
								<th>Pastor</th>
								<th>Monto</th>
							</thead>
							<tbody>
								
							</tbody>
						</table>
						<div id="sinOfrendasHist" class="hide">Sin Ofrendas</div>
					</div>
				</div>

				<div id="detalleOfrendas" class="modulo hide">
					<div class="btnAtrasHist manita btn btn-success">Atras</div>
					<form action="../formatos/recibo.php" method="post" target="_blank">
						<input type="hidden" name="folio" id="folioRecibo">
						<input type="submit" class="btnRecibo manita btn btn-success" id="btnRecibo" value="Recibo">
					</form>
					<button class="btn btn-danger" id="btnRegOfrenda">Regresar ofrenda</button>
					<div class="tituloModulo">Detalle Ofrenda</div>
					<div id="infoDetalleHist">
						<div class="horizontal-group" style="float: left;">
							<div id="detFolioHist">Folio: <strong></strong></div>
							<div id="detFechaHist">Fecha: <strong></strong></div>
							<div id="detTotalHist">Total: <strong></strong></div>
						</div>
						<div class="horizontal-group">
							<div id="detTipoHist">Tipo: <strong></strong></div>
							<div id="detEstatusHist">Estatus: <strong></strong></div>
							<div id="detObsHist">Observaciones: <strong></strong></div>
						</div>
					</div>
					<table class="tabla table table-hover">
						<thead>
							<th>Misionero</th>
							<th>Monto</th>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
	        </div>

	        <div id="reportes" class="pantalla hide">
				<div class="tituloModulo">Reportes</div>
				<form id="formReporte" onsubmit="return validaAccion()" method="POST" target="_blank">
					<div class="form-group">
						<label for="tipoReporte">Tipo de reporte</label>
						<select name="tipoReporte" id="selectTipoReporte" class="form-control">
							<option value="1">Por misionero</option>
							<option value="2">Todos los Misioneros</option>
							<option value="3">Por Iglesia</option>
							<option value="4">Todas las iglesias</option>
							<option value="5">Salidas</option>
							<option value="6">Usuarios</option>
						</select>
					</div>
					<div class="form-group">
						<div id="grupoRango" class="hide filtrosReporte">
							<div class="vertical-group-md form-group">
								<label for="fechaIni">Fecha Inicial</label>
								<input type="date" name="fechaIni"  class="form-control">
							</div>
							<div class="vertical-group-md form-group">
								<label for="fechaFin">Fecha Final</label>
								<input type="date" name="fechaFin" class="form-control">
							</div>
						</div>
						<div id="grupoIglesias" class="hide filtrosReporte">
							<div class="vertical-group-md form-group">
								<label for="iglesia">Iglesia</label>
								<input type="hidden" name="idIglesia">
								<input type="text" id="inputIglesias" name="iglesia" class="form-control inputRepo" autocomplete="off">
								<table class="tablaRepo hide">
									<tbody></tbody>
								</table>
							</div>
							<div class="vertical-group-md form-group">
								<label for="fechaIglesia">Fecha</label>
								<input type="date" name="fechaIglesia" class="form-control">
							</div>
						</div>
						<div id="grupoMisioneros" class="filtrosReporte">
							<div class="vertical-group-md form-group">
								<label for="misioneros">Misioneros</label>
								<input type="hidden" name="idMisionero">
								<input type="text" id="inputMisioneros" name="misioneros" class="form-control inputRepo" autocomplete="off">
								<table class="tablaRepo hide">
									<tbody></tbody>
								</table>
							</div>
							<div class="vertical-group-md form-group">
								<label for="fechaMisionero">Fecha</label>
								<input type="date" name="fechaMisionero" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<input class="btn btn-block btn-info btn-lg" value="Generar Reporte" type="submit">
					</div>
				</form>
			</div>

		</div>



		<div id="prompRechazaOfrenda" class="hide">
	        <form id="confirmacionRechazo" onsubmit="return false">
	           	<input type="hidden" name="operacion" value="rechazaOfrenda">
	            <div class="cuadro">Confirmación de Rechazo<img id="cerrarConfirmacion" src="../img/cerrar.png" style="float: right; width: 25px; cursor: pointer"></div>
	            <div style="margin: 15px;">
	                <label>Comentarios: </label>
	                <textarea name="comentarios" style="width: 80%; margin: 0 10%; resize: none"></textarea>
	            </div>
	            <input id="btnEnvio" class="btn btn-success" style="margin: 15px;" type="submit">
	        </form>
        </div>

        <div id="prompAceptaOfrenda" class="hide">
	        <form id="confirmacionAceptacion" onsubmit="return false">
	           	<input type="hidden" name="operacion" value="aceptaOfrenda">
	            <div class="cuadro">Acepta ofrenda
	            	<img id="cerrarConfirmacionAcepta" src="../img/cerrar.png" style="float: right; width: 25px; cursor: pointer">
	            </div>
	            <div>
	            	<input type="hidden" name="ofrendaKey" id="ofrendaKey">
	            </div>
	            <div style="margin-top: 20px;">
	            	<label>Requiere folio contable </label>
	            	<input type="checkbox" name="chkContable" id="chkContable" style="margin: 0 15px; width: 15px; height: 15px;">
	            </div>
	            <div>
	            	<input type="text" name="folioContable" id="folioContable" style="margin: 0 15px; " disabled autocomplete="off">
	            </div>
	            <div style="margin-top: 20px;">
	            	<label>Requiere cambio de fecha </label>
	            	<input type="checkbox" name="chkFecha" id="chkFecha" style="margin: 0 15px; width: 15px; height: 15px;">
	            </div>
	            <div>
	            	<input type="date" name="cambioFecha" id="cambioFecha" style="margin: 0 15px; " disabled>
	            </div>
	            <div style="margin: 15px;">
	                <label>Comentarios: </label>
	                <textarea name="comentarios" id="comentariosAceptaOfr" style="width: 80%; margin: 0 10%; resize: none"></textarea>
	            </div>
	            <input id="btnEnvioo" class="btn btn-success" style="margin: 15px;" type="submit">
	        </form>
        </div>

	</body>