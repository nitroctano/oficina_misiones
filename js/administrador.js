var ruta = "../";
var glbUsuarios;
var glbOfrendasEnc;
var glbOfrendasDet;
var glbProyectos;
var glbOfrendasAct;
var glbOfrendasEncHist;
var glbOfrendasDetHist;

$(function(){

	$(document).on("click","#proyectosVistaAdmin .radioCheck",function(){
		var check = $(this).parent().prev();
		if(check.prop("checked") == true){
			check.prop("checked","");
		}else{
			check.prop("checked","checked");
		}

		cambiaEstatusProyecto(check);
	})

	$(document).on("click","#vistaUsuariosAdmin .radioCheck",function(){
		
		var check = $(this).parent().prev();
		if(check.prop("checked") == true){
			check.prop("checked","");
		}else{
			var ident = $(this).parent().parent().parent().children().eq(3).text();
			if(ident == "-" || ident == ""){
				muestraMensaje("rojo","Se debe asignar un identificador");
				return false;
			}
			check.prop("checked","checked");
		}
		cambiaEstatusUsuario(check);
	})


	$(document).on("click","#tablaOfrendasRecibidas tbody tr",function(){
		var idEnc = $(this).children().eq(0).text();
		var nomIglesia = $(this).children().eq(2).text();
		var misionero;
		var fila;

		$("#tablaOfrendasRecibidas").addClass("hide");
		$("#aprobacionOfrendas").removeClass("hide");
		$('#aprobacionOfrendas tbody').empty();

		for(index in glbOfrendasEnc){
			if(glbOfrendasEnc[index][0] == idEnc){

				$("#ofrendaKey").val(idEnc);
				$("#detFecha strong").text(glbOfrendasEnc[index][2]);
				$("#detTotal strong").text(formatoMoneda(glbOfrendasEnc[index][3]));
				$("#detTipo strong").text(glbOfrendasEnc[index][4]);
				break;
			}
		}

		for(index in glbOfrendasDet){
			if(glbOfrendasDet[index][1] == idEnc){

				if(glbOfrendasDet[index][5] == 'S'){
					for(usuario in glbUsuarios){
						if(glbOfrendasDet[index][2] == glbUsuarios[usuario][0]){
							misionero = glbUsuarios[usuario][3];
							break;
						}
					}
				}else{
					for(proyecto in glbProyectos){
						if(glbOfrendasDet[index][2] == glbProyectos[proyecto][0]){
							misionero = glbProyectos[proyecto][1];
							break;
						}
					}
				}

				fila = '<tr><td>'+misionero+'</td><td>'+glbOfrendasDet[index][3]+'</td></tr>';
				$(fila).appendTo($('#aprobacionOfrendas tbody'));
			}
		}
	})


	$(document).on("change",".checkUsuario",function(){
		var idUsuario = $(this).parent().parent().children().eq(0).text();
		var data = [{name:"operacion", value:"estatusUsuario"},
		{name:"idUsuario", value: idUsuario}]
		muestraLoading();
		$.ajax({
			method:"POST",
			url: ruta + "php/administrador.php",
			dataType: "json",
			data: data
		}).done(function(response){
			cargaInicial();

		}).fail(function(){

		}).always(function(){
			ocultaLoading();
		})

	})

	cargaInicial();
	$("#formProyectos").submit(function(){
		var data = $(this).serializeArray();
		muestraLoading();

		$.ajax({
			method: "POST",
			url: ruta + "php/administrador.php",
			dataType: "json",
			data: data
		}).done(function(response){
			if(response[0] == "0"){
				muestraMensaje("rojo","El nombre del proyecto ya existe");
			}else{
				cargaInicial();
				$("#nomProyecto").val("")
			}

		}).fail(function(){
			muestraMensaje("rojo","error de conexión");

		}).always(function(){
			ocultaLoading();
		})
	})



	$("#iconoMenu").click(function(){
		$(this).next().slideToggle();
	})

	$("#barraMenuMovil li").click(function(){
		$("#barraMenuMovil").slideToggle();
	})

	$(".btnAtras").click(function(){
		$("#tablaOfrendasRecibidas").removeClass("hide");
		$("#aprobacionOfrendas").addClass("hide");	

		$("#tablaOfrendasHistorial").removeClass("hide");
		$("#historialOfrendas").addClass("hide");	

	})

	$(".btnAtrasHist").click(function(){
		$("#tablaOfrendasHistorial").removeClass("hide");
		$("#detalleOfrendas").addClass("hide");
	})	

	$("#aceptaOfrenda").click(function(){
		$("#sombra").removeClass("hide");
		$("#prompAceptaOfrenda").removeClass("hide");
		$("#prompAceptaOfrenda #folioContable").val("");
		$("#prompAceptaOfrenda #cambioFecha").val("");
		$("#prompAceptaOfrenda #comentariosAceptaOfr").val("");
		$("#prompAceptaOfrenda #chkContable").prop("checked","");
		$("#prompAceptaOfrenda #folioContable").attr("disabled","disabled");
		$("#prompAceptaOfrenda #chkFecha").prop("checked","");
		$("#prompAceptaOfrenda #cambioFecha").attr("disabled","disabled");

	})

	$("#confirmacionAceptacion").submit(function(){

		if($("#prompAceptaOfrenda #chkContable").prop("checked") == true && $("#prompAceptaOfrenda #folioContable").val() == ""){
			$("#prompAceptaOfrenda #folioContable").focus();
			return false;
		}

		if($("#prompAceptaOfrenda #chkFecha").prop("checked") == true && $("#prompAceptaOfrenda #cambioFecha").val() == ""){
			$("#prompAceptaOfrenda #cambioFecha").focus();
			return false;
		}
		var ofrenda = $("#ofrendaKey").val();

		var data = $(this).serializeArray();
		muestraLoading();
		console.log(data);

		$.ajax({
			method: "POST",
			url: ruta + "php/administrador.php",
			dataType: "json",
			data: data
		}).done(function(response){
			cargaInicial();

		}).fail(function(response){
			console.log(response);
			muestraMensaje("rojo","Error de conexión");

		}).always(function(){
			$("#tablaOfrendasRecibidas").removeClass("hide");
			$("#aprobacionOfrendas").addClass("hide");
			$("#prompAceptaOfrenda").addClass("hide");
			ocultaLoading();
			$("#sombra").addClass("hide");
		});

	})


	$("#rechazaOfrenda").click(function(){
		$("#sombra").removeClass("hide");
		$("#prompRechazaOfrenda").removeClass("hide");
	})

	$(document).on("click",".editNomProyecto",function(){
		var nomProyecto = $(this).text();
		$(this).text("");
		
		$('<input type="text" class="editNomProyectoInput" value="'+nomProyecto+'">').appendTo($(this));
		$(this).removeClass('editNomProyecto');
		$(this).children().eq(0).focus();
	})


	$(document).on("focusout",".editNomProyectoInput",function(){
		muestraLoading();
		var key = $(this).parent().parent().children().eq(0).text();
		var nomProyecto = $(this).val();
		
		$(this).parent().addClass('editNomProyecto');
		$(this).parent().text(nomProyecto);
		$(this).remove();
		
		var data = [{name: 'operacion', value: 'editNomProyecto'},
					{name: 'key', value: key},
					{name: 'nomProyecto', value: nomProyecto}];
		$.ajax({
			method: "POST",
			url: ruta + "php/administrador.php",
			dataType: "json",
			data: data
		}).done(function(response){
		}).fail(function(response){
		}).always(function(){
			ocultaLoading();
		})
	
	});


	$(document).on("click","#vistaUsuariosAdmin .editIdent",function(){
		var ident = $(this).text();
		$(this).text("");
		
		$('<input type="text" class="editIdentInput" value="'+ident+'">').appendTo($(this));
		$(this).removeClass('editIdent');
		$(this).children().eq(0).focus();
		
	})
	
	$(document).on("focusout","#vistaUsuariosAdmin .editIdentInput",function(){
		muestraLoading();
		var key = $(this).parent().parent().children().eq(0).text();
		var ident = $(this).val();
		
		$(this).parent().addClass('editIdent');
		$(this).parent().text(ident);
		$(this).remove();
		
		var data = [{name: 'operacion', value: 'editIdent'},
					{name: 'key', value: key},
					{name: 'ident', value: ident}];
		$.ajax({
			method: "POST",
			url: ruta + "php/administrador.php",
			dataType: "json",
			data: data
		}).done(function(response){
			if(response[0] == 0){
				cargaUsuarios(glbUsuarios);
			}else{
				cargaInicial();
			}
		}).fail(function(response){
		}).always(function(){
			ocultaLoading();
		})
	
	});

	$("#confirmacionRechazo").submit(function(){
		muestraLoading();
		var data = $(this).serializeArray();
		var ofrenda = $("#ofrendaKey").val();

		data.push({name: "ofrenda", value: ofrenda});

		$.ajax({
			method: "POST",
			url: ruta + "php/administrador.php",
			dataType: "json",
			data: data
		}).done(function(response){
			cargaInicial();
			$("#prompRechazaOfrenda").addClass("hide");
			muestraMensaje("verde","Envío exitoso");
		}).fail(function(response){
			$("#prompRechazaOfrenda").addClass("hide");
			muestraMensaje("rojo","Envío fallido");
		}).always(function(){
			$("#tablaOfrendasRecibidas").removeClass("hide");
			$("#aprobacionOfrendas").addClass("hide");
			ocultaLoading();

		})
	})

	$("#enviarOfrendas thead .radioCheck").click(function(){
		$(this).toggleClass("radioCheckActivo");
		$(this).parent().toggleClass("fondoCheckActivo");

		if($(this).hasClass("radioCheckActivo")){
			$("#enviarOfrendas tbody .radioCheck").addClass("radioCheckActivo");
			$("#enviarOfrendas tbody .fondoCheck").addClass("fondoCheckActivo");
		}else{
			$("#enviarOfrendas tbody .radioCheck").removeClass("radioCheckActivo");
			$("#enviarOfrendas tbody .fondoCheck").removeClass("fondoCheckActivo");
		}

		sumaOfrendas();
	});

	$(document).on("click","#enviarOfrendas tbody .radioCheck",function(){
		$(this).toggleClass("radioCheckActivo");
		$(this).parent().toggleClass("fondoCheckActivo");

		if($(this).hasClass("radioCheckActivo") == false){
			$("#enviarOfrendas thead .radioCheck").removeClass("radioCheckActivo");
			$("#enviarOfrendas thead .fondoCheck").removeClass("fondoCheckActivo");
		}

		sumaOfrendas();
	});


	$(document).on("click","#enviarOfrendas .editMonto",function(){
		var monto = $(this).text();
		$(this).text("");
		
		$('<input style="max-width: 100px" type="number" class="editMontoInput" value="'+monto+'">').appendTo($(this));
		$(this).removeClass('editMonto');
		$(this).children().eq(0).focus();
		
	})
	
	$(document).on("focusout","#enviarOfrendas .editMontoInput",function(){

		var monto = $(this).val();
		var disponible = $(this).parent().parent().children().eq(4).text();
		if(parseFloat(monto) > parseFloat(disponible)){
			monto = disponible;
		}
		
		monto = parseFloat(monto).toFixed(2);

		$(this).parent().addClass('editMonto');
		$(this).parent().text(monto);
		$(this).remove();

		sumaOfrendas();
	
	});

	$("#btnOfrendas").click(function(){
		var tabla = $("#formEnviarOfrendas tbody");
		var largoTabla = $("#formEnviarOfrendas tbody tr").length;
		var data = [{name:"operacion", value:"salidaOfrenda"}];
		var cont;
		var reg;
		
		for(i=0;i<largoTabla;i++){
			if(tabla.children().eq(i).children().eq(2).children().eq(0).hasClass("fondoCheckActivo")){

				data.push({name:"reg"+i, value: tabla.children().eq(i).children().eq(0).text() +","+
									  	   tabla.children().eq(i).children().eq(1).text() +","+
									  	   tabla.children().eq(i).children().eq(5).text()});
				
			}
		}

		if(data.length == 1){
			muestraMensaje("rojo","No hay ofrendas seleccionadas");
			return false;
		}

		muestraLoading();
		$.ajax({
			method:"POST",
			url: ruta + "php/administrador.php",
			dataType: "json",
			data: data
		}).done(function(response){
			muestraMensaje("verde","Envío exitoso");
			cargaInicial();
		}).fail(function(response){
		}).always(function(){
			ocultaLoading();
		})
	})


	$("#buscadorUsu").keyup(function(){
		filtraCamposUsu();
	})

	$("#buscadorOfr").keyup(function(){
		filtraCamposOfr();
	})

	$("#btnGeneraCodigo").click(function(){
		var data = [{name: "operacion" , value: "generaCodigo"}];
		muestraLoading();
		$.ajax({
			method:"POST",
			url: ruta + "php/administrador.php",
			dataType: "json",
			data: data
		}).done(function(response){
			var codigo = response[0];
			var group = "";

			for(pos in codigo){
				group = group + codigo[pos];
				if(pos == 3){
					$("#campo1").text(group);
					group = "";
				}else if(pos == 7){
					$("#campo2").text(group);
					group = "";
				}else if(pos == 11){
					$("#campo3").text(group);
					group = "";
				}
			}

		}).fail(function(response){
			muestraMensaje("rojo","Error en la generación del código")
		}).always(function(){
			ocultaLoading();
		})
	})

	$("#cerrarConfirmacion").click(function(){
		$("#prompRechazaOfrenda").addClass("hide");
		$("#sombra").addClass("hide");
	})


	$("#cerrarConfirmacionAcepta").click(function(){
		$("#prompAceptaOfrenda").addClass("hide");
		$("#sombra").addClass("hide");
	})



	$(document).on("click","#tablaOfrendasHistorial tbody tr",function(){
		var idEnc = $(this).children().eq(0).text();
		var nomIglesia = $(this).children().eq(2).text();
		var misionero;
		var fila;

		$("#tablaOfrendasHistorial").addClass("hide");
		$("#detalleOfrendas").removeClass("hide");
		$('#detalleOfrendas tbody').empty();

		for(index in glbOfrendasEncHist){
			if(glbOfrendasEncHist[index][0] == idEnc){

				$("#folioRecibo").val(glbOfrendasEncHist[index][0]);

				if(glbOfrendasEncHist[index][7] != null){
					$("#detFolioHist strong").text(pad(glbOfrendasEncHist[index][7],5));
				}else{
					$("#detFolioHist strong").text('S/F');
				}

				if(glbOfrendasEncHist[index][5] == 'PE'){
					$("#detEstatusHist strong").text('Pendiente');
					$("#btnRecibo").hide();
				}else if(glbOfrendasEncHist[index][5] == 'CA'){
					$("#detEstatusHist strong").text('Rechazada');
					$("#btnRecibo").hide();
				}else{
					$("#detEstatusHist strong").text('Exitosa');
					$("#btnRecibo").show();
				}

				$("#detFechaHist strong").text(glbOfrendasEncHist[index][2]);
				$("#detTotalHist strong").text(formatoMoneda(glbOfrendasEncHist[index][3]));
				$("#detTipoHist strong").text(glbOfrendasEncHist[index][4]);
				$("#detObsHist strong").text(glbOfrendasEncHist[index][6]);
				break;
			}
		}

		for(index in glbOfrendasDetHist){
			if(glbOfrendasDetHist[index][1] == idEnc){

				if(glbOfrendasDetHist[index][5] == 'S'){
					for(usuario in glbUsuarios){
						if(glbOfrendasDetHist[index][2] == glbUsuarios[usuario][0]){
							misionero = glbUsuarios[usuario][3];
							break;
						}
					}
				}else{
					for(proyecto in glbProyectos){
						if(glbOfrendasDetHist[index][2] == glbProyectos[proyecto][0]){
							misionero = glbProyectos[proyecto][1];
							break;
						}
					}
				}

				fila = '<tr><td>'+misionero+'</td><td>'+glbOfrendasDetHist[index][3]+'</td></tr>';
				$(fila).appendTo($('#detalleOfrendas tbody'));
			}
		}
	});



	$("#tablaOfrendasHistorial .buscador input").keyup(function(){
		filtraCamposOfrHist();
	})

	$("#mesHist").change(function(){

		var fecha = $("#anioHist").val()+"-"+$("#mesHist").val()+"-";
		var data = [{name: "operacion" , value: "filtraFechaHist"},
					{name: "fecha", value: fecha}];

		muestraLoading();
		$.ajax({
			method:"POST",
			url: ruta + "php/administrador.php",
			dataType: "json",
			data: data
		}).done(function(response){
			cargaOfrendasHist(response);
		}).fail(function(response){
			muestraMensaje("rojo","Error al carga ofrendas")
		}).always(function(){
			ocultaLoading();
		});

	})

	$("#anioHist").change(function(){

		var fecha = $("#anioHist").val()+"-"+$("#mesHist").val();
		var data = [{name: "operacion" , value: "filtraFechaHist"},
					{name: "fecha", value: fecha}];

		muestraLoading();
		$.ajax({
			method:"POST",
			url: ruta + "php/administrador.php",
			dataType: "json",
			data: data
		}).done(function(response){
			cargaOfrendasHist(response);
		}).fail(function(response){
			muestraMensaje("rojo","Error al carga ofrendas")
		}).always(function(){
			ocultaLoading();
		});

	})

	$("#chkContable").change(function(){
		if( $(this).prop("checked")==true ){
			$("#folioContable").removeAttr("disabled");
		}else{
			$("#folioContable").attr("disabled",true);
		}


	})

	$("#chkFecha").change(function(){
		if( $(this).prop("checked")==true ){
			$("#cambioFecha").removeAttr("disabled");
		}else{
			$("#cambioFecha").attr("disabled",true);
		}


	})

	$("#selectTipoReporte").change(function(){
		$(".filtrosReporte").addClass("hide");

		switch ($(this).val()){
			case "1":
				$("#grupoMisioneros").removeClass("hide");
				break;
			case "2":
			case "4":
			case "5":
				$("#grupoRango").removeClass("hide");
				break;
			case "3":
				$("#grupoIglesias").removeClass("hide");
				break;
			case "6":
				$(".filtrosReporte").addClass("hide");
		}
	})

	$("#grupoMisioneros #inputMisioneros").click(function(){
		$(this).next().toggleClass("hide");
	})

	$("#grupoIglesias #inputIglesias").click(function(){
		$(this).next().toggleClass("hide");
	})

	$(document).on("click","#grupoMisioneros tbody tr",function(){
		$(this).parent().parent().prev().val($(this).children().eq(1).text());
		$(this).parent().parent().prev().prev().val($(this).children().eq(0).text());
	})

	$(document).on("click","#grupoIglesias tbody tr",function(){
		$(this).parent().parent().prev().val($(this).children().eq(1).text());
		$(this).parent().parent().prev().prev().val($(this).children().eq(0).text());
	})

	$("#inputMisioneros").keyup(function(){
		filtraMisioneros($(this).val());
	})

	$("#inputIglesias").keyup(function(){
		filtraIglesias($(this).val());
	})

	$(".inputRepo").blur(function(){
		if(!$(this).next().hasClass("hide")){
			setTimeout(function(){
				$(".tablaRepo").addClass("hide");	
			},200);
		}
	})

	$("#btnRegOfrenda").click(function(){
		var folio = $("#folioRecibo").val();
		var data = [{name: "operacion" , value: "regresaOfrenda"},
					{name: "folio", value: folio}];

		muestraLoading();
		$.ajax({
			method:"POST",
			url: ruta + "php/administrador.php",
			dataType: "json",
			data: data
		}).done(function(response){
			location.reload();
		}).fail(function(response){
			muestraMensaje("rojo","Error al carga ofrendas")
		}).always(function(){
			ocultaLoading();
		});
	})

})

function validaAccion(){
	var tipoReporte = $("#selectTipoReporte").val();

	switch(tipoReporte){
		case "1": 
			$("#formReporte").attr("action","../formatos/repMisionero.php");

			return true;
			break;
		case "2":
			$("#formReporte").attr("action","../formatos/repMisioneros.php");
			return true;
			break;
		case "3":
			$("#formReporte").attr("action","../formatos/repIglesia.php");
			return true;
			break;
		case "4":
			$("#formReporte").attr("action","../formatos/repIglesias.php");
			return true;
			break;
		case "5":
			$("#formReporte").attr("action","../formatos/repSalidas.php");
			return true;
			break;
		case "6":
			$("#formReporte").attr("action","../formatos/repUsuarios.php");
			return true;
			break;
	}
}


function cargaInicial(){
	var data = [{name: "operacion", value: "cargaInicial"}];
	muestraLoading();
	$.ajax({
		method: "POST",
		url: ruta + "php/administrador.php",
		dataType: "json",
		data: data
	}).done(function(response){
		var proyectos = response[0];
		var usuarios = response[1];
		glbUsuarios = response[1];
		glbOfrendasEnc = response[2];
		glbOfrendasDet = response[3];
		glbProyectos = response[0];
		glbOfrendasAct = response[4];
		glbOfrendasEncHist = response[5];
		glbOfrendasDetHist = response[6];

		cargaOfrendas(response[2]);
		cargaUsuarios(usuarios);
		cargaProyectos(proyectos);
		cargaOfrendasAct();
		cargaOfrendasHist(response[5]);
		cargaReportes(response[7], response[8]);

	}).fail(function(){
		muestraMensaje("rojo","Error de conexion");
	}).always(function(){
		ocultaLoading();
	})
}


function cargaProyectos(proyectos){
	var fila;
	var check; 
	var checked = ''; 
	var claseCheck = '';
	var claseFondo = '';

	$("#proyectosVistaAdmin tbody").empty();
	for(index in proyectos){
		check = proyectos[index][2];
		if(check == 'S'){
			claseCheck = 'radioCheckActivo';
			claseFondo = 'fondoCheckActivo';
			checked = 'checked="checked"';
		}else {
			checked = '';
			claseFondo = '';
			claseCheck = '';
		}
		fila = '<tr><td class="hide">'+proyectos[index][0]+'</td><td class="editNomProyecto manita">'+proyectos[index][1]+'</td><td><input class="checkProyectos hide" name="proyectAct" type="checkbox" '+checked+'><div class="fondoCheck '+claseFondo+'"><div class="radioCheck '+claseCheck+'"></div></div></td></tr>';
		$(fila).appendTo($("#proyectosVistaAdmin tbody"));
	}
}

function cargaUsuarios(usuarios){
	var fila;
	var check;
	var checked = ''; 

	$("#vistaUsuariosAdmin tbody").empty();
	for(index in usuarios){
		 check = usuarios[index][2];
		if(check == 'S'){
			claseCheck = 'radioCheckActivo';
			claseFondo= 'fondoCheckActivo'
			checked = 'checked="checked"';
		}else {
			checked = '';
			claseFondo = '';
			claseCheck = '';
		}
		fila = '<tr><td class="hide">'+usuarios[index][0]+'</td><td>'+usuarios[index][1]+'</td><td>'+usuarios[index][4]+'</td><td class="manita editIdent">'+usuarios[index][3]+'</td><td><input class="checkUsuario hide" name="extranjero" type="checkbox" '+checked+'><div class="fondoCheck '+claseFondo+'"><div class="radioCheck '+claseCheck+'"></div></div></td></tr>';
		$(fila).appendTo($("#vistaUsuariosAdmin tbody"));
	}
}


function cambiaEstatusProyecto(check){
	var idProyecto = check.parent().parent().children().eq(0).text();
	var data = [{name:"operacion", value:"estatusProyecto"},
	{name:"idProyecto", value: idProyecto}]
	muestraLoading();
	$.ajax({
		method:"POST",
		url: ruta + "php/administrador.php",
		dataType: "json",
		data: data
	}).done(function(response){
		cargaInicial();
	}).fail(function(){

	}).always(function(){
		ocultaLoading();
	})
}




function cambiaEstatusUsuario(check){
	var idUsuario = check.parent().parent().children().eq(0).text();
	var data = [{name:"operacion", value:"estatusUsuario"},
	{name:"idUsuario", value: idUsuario}]
	muestraLoading();

	$.ajax({
		method:"POST",
		url: ruta + "php/administrador.php",
		dataType: "json",
		data: data
	}).done(function(response){
		cargaInicial();
	}).fail(function(){

	}).always(function(){
		ocultaLoading();
	})
}


function cargaOfrendas(ofrendas){
	var fila;
	$("#sinOfrendas").addClass("hide");
	$("#tablaOfrendasRecibidas tbody").empty();
	if(ofrendas == null){
		//$('<div style="font-size: 40px; text-align:center; width: 100%; font-weight: bold; color: #A6A6A6; margin-top: 50px">Sin Ofrendas</div>').appendTo($("#tablaOfrendasRecibidas"));
		$("#sinOfrendas").removeClass("hide");
	}else{
		for(index in ofrendas){
			for(id in glbUsuarios){
				if(ofrendas[index][1] == glbUsuarios[id][0]){
					fila = '<tr class="manita"><td class="hide">'+ofrendas[index][0]+'</td><td>'+ofrendas[index][2]+'</td><td>'+glbUsuarios[id][1]+'</td><td>'+glbUsuarios[id][4]+'</td><td>'+formatoMoneda(ofrendas[index][3])+'</td></tr>';
					$(fila).appendTo($("#tablaOfrendasRecibidas tbody"));
					break;
				}
			}
		}
	}
	

}


function pantallas(pantalla){
	$(".pantalla").addClass("hide");
	$("#"+pantalla).removeClass("hide");

	if(pantalla == 'ofrendasRecibidas'){
		$("#tablaOfrendasRecibidas").removeClass("hide");
		$("#aprobacionOfrendas").addClass("hide");
	}
}

function formatoMoneda(monto){
	var numero = monto.toString()
	var nuevo = "";
	var cont = 0;
	for(i=(numero.length - 1);i>=0;i--){
		if(cont % 3 == 0 && cont != 0){
			nuevo = "," + nuevo;
	    }
		nuevo = numero[i] + nuevo;
		cont = cont + 1;
		if(numero[i] == "."){
			cont = 0;
		}
	}

	return nuevo;
}

function muestraLoading(){
	$("#loading").removeClass("hide");
}

function ocultaLoading(){
	$("#loading").addClass("hide");
}

function muestraMensaje(tipo, mensaje){
	$("#sombra").removeClass("hide");
	$("#"+tipo).removeClass("hide");
	$("#"+tipo).text(mensaje);
	setTimeout(function(){
		$("#sombra").addClass("hide");
		$("#"+tipo).addClass("hide");
	}, 1000)
}

function cargaOfrendasAct(){
	var fila;

	$("#enviarOfrendas tbody").empty();
	$("#enviarOfrendas thead .fondoCheck").removeClass("fondoCheckActivo");
	$("#enviarOfrendas thead .radioCheck").removeClass("radioCheckActivo");

	for(index in glbOfrendasAct){
		fila = '<tr><td class="hide">'+glbOfrendasAct[index][0]+'</td><td class="hide">'+glbOfrendasAct[index][3]+'</td><td><div class="fondoCheck"><div class="radioCheck"></div></div></td><td>'+glbOfrendasAct[index][1]+'</td><td>'+glbOfrendasAct[index][2]+'</td><td class="editMonto">'+glbOfrendasAct[index][2]+'</td></tr>';

		$(fila).appendTo($("#enviarOfrendas tbody"));

	}

	sumaOfrendas();
}

function sumaOfrendas(){
	var largoTabla = $("#enviarOfrendas tbody tr").length;
	var tabla = $("#enviarOfrendas tbody");
	var fondo;
	var total = 0;
	for(i=0; i<largoTabla; i++){
		fondo = tabla.children().eq(i).children().eq(2).children().eq(0);

		if(fondo.hasClass("fondoCheckActivo")){
			total = total + parseFloat(tabla.children().eq(i).children().eq(5).text());
		}
	}

	$("#enviarOfrendas .tituloModulo").text("Total ofrendas: $"+ formatoMoneda(total.toFixed(2)));
}

function filtraCamposUsu(){
	if($("#buscadorUsu").val() != ""){
		$("#vistaUsuariosAdmin tbody>tr").hide();
		$("#vistaUsuariosAdmin tbody td:contains-ci("+$("#buscadorUsu").val()+")").parent("tr").show();
	}else{
		$("#vistaUsuariosAdmin tbody>tr").show();
	}
}

function filtraCamposOfr(){
	if($("#buscadorOfr").val() != ""){
		$("#formEnviarOfrendas tbody>tr").hide();
		$("#formEnviarOfrendas tbody td:contains-ci("+$("#buscadorOfr").val()+")").parent("tr").show();
	}else{
		$("#formEnviarOfrendas tbody>tr").show();
	}
}

function filtraCamposOfrHist(){
	if($("#tablaOfrendasHistorial .buscador input").val() != ""){
		$("#tablaOfrendasHistorial tbody>tr").hide();
		$("#tablaOfrendasHistorial tbody td:contains-ci("+$("#tablaOfrendasHistorial .buscador input").val()+")").parent("tr").show();
	}else{
		$("#tablaOfrendasHistorial tbody>tr").show();
	}
}

function filtraMisioneros(valor){
	if(valor != ""){
		$("#grupoMisioneros tbody>tr").hide();
		$("#grupoMisioneros tbody td:contains-ci("+$("#grupoMisioneros #inputMisioneros").val()+")").parent("tr").show();
	}else{
		$("#grupoMisioneros tbody>tr").show();
	}
}

function filtraIglesias(valor){
	if(valor != ""){
		$("#grupoIglesias tbody>tr").hide();
		$("#grupoIglesias tbody td:contains-ci("+$("#grupoIglesias #inputIglesias").val()+")").parent("tr").show();
	}else{
		$("#grupoIglesias tbody>tr").show();
	}
}

$.extend($.expr[":"],{
	"contains-ci": function(elem,i,match,array){
		return(elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
})

function nobackbutton(){
   window.location.hash="no-back-button";
   window.location.hash="Again-No-back-button" //chrome
   window.onhashchange=function(){window.location.hash="no-back-button";}	
}

function cargaOfrendasHist(ofrendas){
	var fila;
	$("#sinOfrendasHist").addClass("hide");
	$("#tablaOfrendasHistorial tbody").empty();
	if(ofrendas == null){
		//$('<div style="font-size: 40px; text-align:center; width: 100%; font-weight: bold; color: #A6A6A6; margin-top: 50px">Sin Ofrendas</div>').appendTo($("#tablaOfrendasRecibidas"));
		$("#sinOfrendasHist").removeClass("hide");
	}else{
		for(index in ofrendas){
			for(id in glbUsuarios){
				if(ofrendas[index][1] == glbUsuarios[id][0]){
					fila = '<tr class="manita"><td class="hide">'+ofrendas[index][0]+'</td><td>'+ofrendas[index][2]+'</td><td>'+glbUsuarios[id][1]+'</td><td>'+glbUsuarios[id][4]+'</td><td>'+formatoMoneda(ofrendas[index][3])+'</td></tr>';
					$(fila).appendTo($("#tablaOfrendasHistorial tbody"));
					break;
				}
			}
		}
	}
	

}

function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}

function cargaReportes(misioneros, iglesias){
	for(index in misioneros){
		fila = '<tr><td class="hide">'+misioneros[index][0]+'</td><td>'+misioneros[index][1]+'</td></tr>';
		$(fila).appendTo($("#grupoMisioneros tbody"));
	}

	for(index in iglesias){
		fila = '<tr><td class="hide">'+iglesias[index][0]+'</td><td>'+iglesias[index][1]+" - "+iglesias[index][2]+'</td></tr>';
		$(fila).appendTo($("#grupoIglesias tbody"));
	}
}