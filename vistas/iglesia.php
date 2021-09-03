<?php

session_start();

if(isset($_SESSION["APLICATIVO_ENTERSY"])){
  if($_SESSION["APLICATIVO_ENTERSY"] != "CBBM"){
    if($_SESSION["CBBM_TYPE_USER"] == 2){
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
		 <title>Iglesia</title>
    	 <meta charset="UTF-8">
    	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
    	 <script src="../lib/jquery-3.1.1.min.js"></script>
    	 <link rel="shortcut icon" href="../img/icono.png">
    	 <link href="../lib/bootstrap.min.css" type="text/css" rel="stylesheet">
    	 <link href="../css/estilos.css" rel="stylesheet" type="text/css">
    	 <script src="../js/iglesia.js"></script>
	</head>
	<body onload="nobackbutton();">
		<div id="sombra" class="hide"></div>
		<div id="loading" class="">
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

			<div id="infGral" class="hidden-xs hidden-sm">
				<div id="datos">
					 <h4><strong>Información General</strong></h4>
					 <div id="nombreIglesia"><strong>Nombre: </strong><span></span></div>
					 <div id="nombrePastor"><strong>Pastor: </strong><span></span></div>
				</div>
				<label class="avanceMisionero"></label>
			</div>

			<div id="iconoMenu" class="hidden-md hidden-lg">
				<div></div>
				<div></div>
				<div></div>
			</div>
			<div id="barraMenuMovil" class="hidden-lg hidden-md">

			<div id="infGralMovil">
					<div id="datos">
						 <h4><strong>Información General</strong></h4>
						 <div id="nombreIglesia"><strong>Nombre: </strong><span></span></div>
						 <div id="nombrePastor"><strong>Pastor: </strong><span></span></div>
					</div>
					<label class="avanceMisionero"></label>
				</div>

				<ul id="menuSup">
					<li onclick="pantallas('nuevaOfrenda')"><img src="../img/enviar.png" style="width: 35px;margin-right: 10px;">Nueva Ofrenda</li>
					<li onclick="pantallas('ofrendasEnviadas')"><img src="../img/historial.png" style="width: 30px;margin-right: 10px;">Ofrendas Registradas</li>
					<li class="itemMis" onclick="pantallas('reportes')"><img src="../img/reporte.png" style="width: 35px;margin-right: 10px;">Reportes</li>
					<li onclick="pantallas('informacionGral')"><img src="../img/informacion.png" style="width: 35px;margin-right: 10px;">Informacion General</li>
					<li onclick="window.location.href='../php/cerrarSesion.php'"><img src="../img/logout.png" style="width: 35px;margin-right: 10px;">Cerrar Sesion</li>
				</ul>
			</div>
			
		</div>
		<div id="barraMenu" class="hidden-xs hidden-sm">
			<div id="tituloMenu">Menú</div>
			<ul id="menuLat">
				<li onclick="pantallas('nuevaOfrenda')"><img src="../img/enviar.png" style="width: 35px;margin-right: 10px;">Nueva Ofrenda</li>
				<li onclick="pantallas('ofrendasEnviadas')"><img src="../img/historial.png" style="width: 30px;margin-right: 10px;">Ofrendas Registradas</li>
				<li class="itemMis" onclick="pantallas('reportes')"><img src="../img/reporte.png" style="width: 35px;margin-right: 10px;">Reportes</li>
				<li onclick="pantallas('informacionGral')"><img src="../img/informacion.png" style="width: 35px;margin-right: 10px;">Información General</li>
				<li onclick="window.location.href='../php/cerrarSesion.php'"><img src="../img/logout.png" style="width: 35px;margin-right: 10px;">Cerrar Sesión</li>

			</ul>
		</div>
		<div id="contenedor">
			<div id="ofrendasEnviadas" class="pantalla">
				<div id="histOfrendas" class="modulo">
					<div class="tituloModulo">Historial de Ofrendas</div>
					<div class="tabla">
						<table class="table table-hover">
							<thead>
								<th style="max-width: 100px">Estatus</th>
								<th>Fecha</th>
								<th>Folio</th>
								<th>Total</th>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>	
				</div>


				<div id="detHistOfrendas" class="modulo hide">
					<div class="btnAtras manita btn btn-success">Atras</div>
					<form action="../formatos/recibo.php" method="post" target="_blank">
						<input type="hidden" name="folio" id="folioRecibo">
						<input type="submit" class="btnRecibo manita btn btn-success" id="btnRecibo" value="Recibo">
					</form>
					<div class="tituloModulo">Detalle</div>
					<div id="infoDetalle">
						<div class="horizontal-group" style="float: left;">
							<div id="detFolio">Folio: <strong></strong></div>
							<div id="detFecha">Fecha: <strong></strong></div>
							<div id="detTotal">Total: <strong></strong></div>
						</div>
						<div class="horizontal-group">
							<div id="detTipo">Tipo: <strong></strong></div>
							<div id="detEstatus">Estatus: <strong></strong></div>
							<div id="detObs">Observaciones: <strong></strong></div>
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

			<div id="nuevaOfrenda" class="hide pantalla">
				<div id="agregarMisionero">
					<form id="formMisioneros" onsubmit="return false">
						<div class="vertical-group" style="width: 300px; position: relative;">
							<label>Misionero o proyecto</label>
							<input type="hidden" name="operacion" value="agregaPlantilla">
							<input type="hidden" name="keyMisionero" id="keyMisionero">
							<input type="hidden" name="tipoProyecto" id="tipoProyecto">
							<input type="text" name="nombreMisionero" id="nombreMisionero" class="form-control" autocomplete="off" required>

							<div id="misionesProyect" class="hide">
								<table class="tabla">
									<tbody>
									</tbody>
								</table>
							</div>

						</div>
						<div class="vertical-group">
							<label>Monto</label>
							<input type="number" name="cantMisionero" class="form-control" id="cantMisionero" required>
						</div>
						<div class="vertical-group" style="vertical-align: bottom;">
							<input type="submit" class="btn btn-info" value="Agregar">
						</div>
					</form>
				</div>
				<div id="distribucion">
					<div class="tituloModulo" id="totalDistribucion">Total distribución: <span></span></div>
					<div class="tabla">
						<table class="tabla table table-hover">
							<thead>
								<th>Proyecto</th>
								<th>Monto</th>
								<th></th>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
					<div class="btn btn-info btn-lg btn-block" id="btnOfrendas">Registrar ofrenda</div>
				</div>
			</div>



			<div id="informacionGral" class="hide pantalla">
				<div class="tituloModulo" id="encabezadoInfGral">Información General</div>
				<form id="camposRegistro" onSubmit="return false">
					<input type="hidden" name="operacion" value="actualizaUsuario">
					<div id="nomUsuario">
						<input type="hidden" name="nomUsuario">
					</div>
					<div id="nomIglesia">
						<label class="azul campo">Nombre de la Iglesia:</label>
						<input type="text" name="nomIglesia" class="form-control">
					</div>
					<div id="nomPastor">
						<label class="azul campo">Nombre del Pastor:</label>
						<input type="text" name="nomPastor" class="form-control">
					</div>
					<div id="pais">
						<label for="pais" class="azul campo">País:</label>
						<input type="text" name="pais" required class="form-control">
					</div>
					<div id="estado">
						<label for="estado" class="azul campo">Estado:</label>
						<input type="text" name="estado" class="form-control">
					</div>
					<div id="ciudad">
						<label for="ciudad" class="azul campo">Ciudad:</label>
						<input type="text" name="ciudad" required class="form-control">
					</div>
					<div id="direccion">
						<label class="azul campo">Dirección:</label>
						<input type="text" name="direccion" class="form-control">
					</div>
					<div id="telefono"> 
						<label class="azul campo">Teléfono:</label>
						<input type="number" name="telefono" class="form-control">
					</div>
					<div id="correoElect">
						<label class="azul campo">Correo electrónico:</label>
						<input type="text" name="correoElect" class="form-control">
					</div>
					<div id="contrasena">
						<label class="azul campo">Contraseña:</label>
						<input type="text" name="contrasena" class="form-control">
					</div>
					<input type="submit" id="btnGuardar" class="btn btn-info btn-lg btn-block" value="Guardar">
				</form>			
			</div>

			<div id="reportes" class="pantalla hide">
				<div class="tituloModulo">Reporte Mensual</div>
				<form id="formReporte" action="../formatos/repPDFMisionero.php" target="_blank" method="POST">
					<div>
						<div class="form-group">
							<label for="fecha">Fecha de reporte</label>
							<input type="date" name="fecha" class="form-control">
						</div>
						<input type="submit" class="btn btn-block btn-info" value="Generar Reporte">
					</div>

				</form>
			</div>


		</div>
		<div id="confirEnvio" class="hide">
	        <form id="confirmacionEnvio" onsubmit="return false">
	           	<input type="hidden" name="operacion" value="enviaOfrenda">
	            <div class="cuadro">Confirmación de Registro<img id="cerrarConfirmacion" src="../img/cerrar.png" style="float: right; width: 25px; cursor: pointer"></div>

	            <div style="margin: 15px;">
	                <label>Tipo de depósito:</label>
	                <select style="margin: 15px;" name="tipoDeposito">
	                  	<option>Efectivo</option>
	                   	<option>Transferencia</option>
	                   	<option>Depósito</option>
	                   	<option>Descuento automático</option>
	                </select>
	            </div>
	            <div style="margin: 15px;">
	                <label>Cantidad: </label>
	                <label id="confirMonto"></label>
	            </div>
	            <input id="btnEnvio" class="btn btn-success" style="margin: 15px;" type="submit" value="Registrar">
	        </form>
        </div>
		<div id="formularioLogin" class="hide">
			<div id="informacionGral">
				<div id="encabezadoInfGral">Información General</div>
				<form id="camposRegistro" onSubmit="return false">
					<input type="hidden" name="operacion" value="actualizaUsuario">
					<div id="nomUsuario">
						<input type="hidden" name="nomUsuario">
					</div>
					<div id="nomIglesia">
						<label class="azul campo">Nombre de la Iglesia:</label>
						<input type="text" name="nomIglesia" class="form-control">
					</div>
					<div id="nomPastor">
						<label class="azul campo">Nombre del Pastor:</label>
						<input type="text" name="nomPastor" class="form-control">
					</div>
					<div id="direccion">
						<label class="azul campo">Dirección:</label>
						<input type="text" name="direccion" class="form-control">
					</div>
					<div id="telefono"> 
						<label class="azul campo">Teléfono:</label>
						<input type="number" name="telefono" class="form-control">
					</div>
					<div id="correoElect">
						<label class="azul campo">Correo electrónico:</label>
						<input type="text" name="correoElect" class="form-control">
					</div>
					<div id="contrasena">
						<label class="azul campo">Contraseña:</label>
						<input type="text" name="contrasena" class="form-control">
					</div>
					<input type="submit" id="btnGuardar" class="btn btn-info btn-lg btn-block" value="Guardar">
					<input type="submit" id="btnRegresar" value="Regresar" onclick="window.location.href='iglesia.php'">
				</form>
			</div>			
		</div>
		<!--///////////////////////////////////////////////////////////////formularioConfimarciondeEnvío-->
		<div id="confirEnvio" class="hide">
            <form id="confirmacionEnvio" onsubmit="return false">
            	<input type="hidden" name="operacion" value="enviaOfrenda">
                <div class="cuadro">Confirmación de Envío</div>
                <div style="margin: 15px;">
                    <label>Tipo de depósito:</label>
                    <select style="margin: 15px;" name="tipoDeposito">
                    	<option>Efectivo</option>
                    	<option>Transferencia</option>
                    	<option>Depósito</option>
                    	<option>Descuento automático</option>
                    </select>
                </div>
                <div style="margin: 15px;">
                    <label>Cantidad: </label>
                    <label id="confirMonto"></label>
                </div>
                <input id="btnEnvio" class="btn btn-success" style="margin: 15px;" type="submit">
            </form>
        </div>
	</body>
</html>