var infoGral;
var proyectos;
var ofrendas;
var ofrendasDet;

var ruta = "../";

$(function(){
	cargaInicial();
	cargaPlantilla();
	cargaAvance();

	$(document).on("click", "#misionesProyect tbody tr", function(){

		var key = $(this).children().eq(0).text();
		var proyect = $(this).children().eq(1).text();
		var tipo =$(this).children().eq(2).text();

		$("#nombreMisionero").val(proyect);
		$("#keyMisionero").val(key);
		$("#tipoProyecto").val(tipo);
		$(this).parent().parent().parent().addClass("hide");
	})

	$("#formMisioneros").submit(function(){
		muestraLoading();
		var data = $(this).serializeArray();
		$.ajax({
			method: "POST",
			url: ruta + "php/iglesia.php",
			dataType: "json",
			data: data
		}).done(function(response){
			console.log(response);
			if (response[0] == 0){
				muestraMensaje("rojo","El misionero seleccionado ya tiene asignada una ofrenda");
			}
			cargaPlantilla();
			$("#nombreMisionero").val("");
			$("#keyMisionero").val("");
			$("#cantMisionero").val("");

		}).fail(function(response){
		}).always(function(){
			ocultaLoading();
		})
	})
	
	$("#btnOfrendas").click(function(){
		if($("#distribucion tbody tr").length == 0){
			muestraMensaje("rojo","Seleccionar al menos un misionero o proyecto");
		}else{
			$("#sombra").removeClass("hide");
			$("#confirEnvio").removeClass("hide");
			$("#confirMonto").text($("#totalDistribucion span").text());
		}
		
	});

	$("#confirmacionEnvio").submit(function(){
		muestraLoading();
		var data = $(this).serializeArray();
		$.ajax({
			method: "POST",
			url: ruta + "php/iglesia.php",
			dataType: "json",
			data: data
		}).done(function(response){
			cargaInicial();
			$("#confirEnvio").addClass("hide");
			muestraMensaje("verde","Envío exitoso");
		}).fail(function(response){
			$("#confirEnvio").addClass("hide");
			muestraMensaje("rojo","Envío fallido");
		}).always(function(){
			ocultaLoading();
		})
	});
	
	$(document).on("click","#histOfrendas tbody tr",function(){

		$("#histOfrendas").toggleClass("hide");
		$("#detHistOfrendas").toggleClass("hide");
		$("#btnRecibo").show();

		var key = $(this).children().eq(0).text();
		
		for(enc in ofrendas){
			if(ofrendas[enc][0] == key){
				$("#folioRecibo").val(ofrendas[enc][0]);
				if(ofrendas[enc][7] != null){
					$("#detFolio strong").text(pad(ofrendas[enc][7],5));
				}else{
					$("#detFolio strong").text('S/F');
				}
				$("#detFecha strong").text(ofrendas[enc][2]);
				$("#detTotal strong").text(formatoMoneda(ofrendas[enc][3]));
				$("#detTipo strong").text(ofrendas[enc][4]);
				$("#detObs strong").text(ofrendas[enc][6]);

				if(ofrendas[enc][5] == 'PE'){
					$("#detEstatus strong").text('Pendiente');
					$("#btnRecibo").hide();
				}else if(ofrendas[enc][5] == 'CA'){
					$("#detEstatus strong").text('Rechazada');
					$("#btnRecibo").hide();
				}else{
					$("#detEstatus strong").text('Exitosa');
				}
				
				$("#detHistOfrendas tbody").empty();
				for(det in ofrendasDet){
					if(ofrendasDet[det][1] == key){

						$('<tr><td>'+ofrendasDet[det][3]+'</td><td>'+ofrendasDet[det][4]+'</td></tr>').appendTo($("#detHistOfrendas tbody"));
					}
				}
				break;
				
			}
		}
	});
	
	$(document).on("click","#distribucion .editMonto",function(){
		var monto = $(this).text();
		$(this).text("");
		
		$('<input type="number" class="editMontoInput" value="'+monto+'">').appendTo($(this));
		$(this).removeClass('editMonto');
		$(this).children().eq(0).focus();
		
	})
	
	$(document).on("focusout","#distribucion .editMontoInput",function(){
		muestraLoading();
		var key = $(this).parent().parent().children().eq(0).text();
		var monto = $(this).val();
		
		$(this).parent().addClass('editMonto');
		$(this).parent().text(monto);
		$(this).remove();
		
		var data = [{name: 'operacion', value: 'editMonto'},
					{name: 'key', value: key},
					{name: 'monto', value: monto}];
		$.ajax({
			method: "POST",
			url: ruta + "php/iglesia.php",
			dataType: "json",
			data: data
		}).done(function(response){
		}).fail(function(response){
		}).always(function(){
			ocultaLoading();
			cargaPlantilla();
		})
	
	});
	
	$(document).on("click","#distribucion .elimPlantilla",function(){
		muestraLoading();
		var key = $(this).parent().children().eq(0).text();
		
		var data = [{name: 'operacion', value: 'elimPlantilla'},
					{name: 'key', value: key}];
		$.ajax({
			method: "POST",
			url: ruta + "php/iglesia.php",
			dataType: "json",
			data: data
		}).done(function(response){
			cargaPlantilla();
		}).fail(function(response){
		}).always(function(){
			ocultaLoading();
		})
	})
	
	$("#btnOpciones").click(function(){
		$(this).children().eq(0).toggleClass('hide');
	})
	
	$("#muestraPantInfGral").click(function(){
		$("#formularioLogin").removeClass('hide');
		$("#principal").addClass('hide');
		setInfoGral();
	});
	
	$("#camposRegistro").submit(function(){
		muestraLoading();
		var data = $(this).serializeArray();
		
		$.ajax({
			method: "POST",
			url: ruta + "php/iglesia.php",
			dataType: "json",
			data: data
		}).done(function(response){
			cargaInicial();
			muestraMensaje("verde","La información ha sido guardada correctamente");
			setInfoGral();
		}).fail(function(response){
			muestraMensaje("rojo","Error de conexión");
		}).always(function(){
			ocultaLoading();
		})
	})

	$(".btnAtras").click(function(){
		$("#histOfrendas").toggleClass("hide");
		$("#detHistOfrendas").toggleClass("hide");
	})

	$("#nombreMisionero").click(function(){
		$(this).next().toggleClass("hide");
	})

	$("#nombreMisionero").blur(function(){
		if(!$(this).next().hasClass("hide")){
			setTimeout(function(){
				$("#misionesProyect").addClass("hide");	
			},200);
			
		}
	})

	$("#nombreMisionero").keyup(function(){
		filtraCampos();
	})

	$("#iconoMenu").click(function(){
		$(this).next().slideToggle();
		$("#sombra").toggleClass("hide");
	})

	$("#barraMenuMovil li").click(function(){
		$("#barraMenuMovil").slideToggle();
		$("#sombra").toggleClass("hide");
	})

	$("#cerrarConfirmacion").click(function(){
		$(this).parent().parent().parent().addClass("hide");
		$("#sombra").addClass("hide");
	})

})

function cargaInicial(){
	muestraLoading();
	var data = [{name: "operacion", value: "cargaInicial"}];
	$.ajax({
		method: "POST",
		url: ruta + "php/iglesia.php",
		dataType: "json",
		data: data
	}).done(function(response){

		infoGral = response[0][0];
		proyectos = response[1];
		ofrendas = response[2];
		ofrendasDet = response[3];


		barraInfo();
		proyectosyMisioneros();
		historialOfrendas();
		setInfoGral();

	}).fail(function(response){
	}).always(function(){
		ocultaLoading();
	})
}

function barraInfo(){
	$("#nombreIglesia span").text(infoGral[1]);
	$("#nombrePastor span").text(infoGral[5]);

}


function proyectosyMisioneros(){
	$("#misionesProyect tbody").empty();
	for(proyecto in proyectos){
		$("<tr style='cursor: pointer;	'><td class='hide keyProyecto'>"+proyectos[proyecto][0]+"</td><td>"+proyectos[proyecto][1]+"</td><td class='hide'>"+proyectos[proyecto][2]+"</td></tr>").appendTo($("#misionesProyect tbody"))
	} 

}

function cargaPlantilla(){
	muestraLoading();
	var data = [{name: "operacion", value: "cargaPlantilla"}];
	$.ajax({
		method: "POST",
		url: ruta + "php/iglesia.php",
		dataType: "json",
		data: data
	}).done(function(response){

		var plantilla = response;
		var total = 0;

		$("#distribucion tbody").empty();

		for(registro in plantilla){
			$('<tr><td class="hide">'+plantilla[registro][0]+'</td><td>'+plantilla[registro][1]+'</td><td class="editMonto" style="cursor: pointer">'+plantilla[registro][2]+'</td><td class="elimPlantilla" style="color: red; font-size: 18px; cursor: pointer">X</td></tr>').appendTo($("#distribucion tbody"));
			total = total + parseInt(plantilla[registro][2]);
		}

		$("#totalDistribucion span").text('$'+formatoMoneda(total));

	}).fail(function(response){
	}).always(function(){
		ocultaLoading();
	})

}

function historialOfrendas(){
	$("#histOfrendas tbody").empty();
	var estatus;
	var folio;
	for(ofrenda in ofrendas){
		switch(ofrendas[ofrenda][5]){
			case 'PE':
				estatus = 'amarillo';
				folio = 'S/F'
				break;
			case 'OK':
				estatus = 'verde';
				folio = pad(ofrendas[ofrenda][7],5);
				break;
			case 'CA':
				estatus = 'rojo';
				folio = 'S/F';
				break;

		}
		$("<tr style='cursor: pointer; font-weight: bold'><td class='hide keyOfrenda'>"+ofrendas[ofrenda][0]+"</td><td><div class='estatus "+estatus+"'></div></td><td>"+ofrendas[ofrenda][2]+"</td><td>"+folio+"</td><td>"+formatoMoneda(ofrendas[ofrenda][3])+"</td></tr>").appendTo($("#histOfrendas tbody"))
	} 
}


function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}

function setInfoGral(){
	$("#nomIglesia input").val(infoGral[1]);
	$("#nomPastor input").val(infoGral[5]);
	$("#direccion input").val(infoGral[6]);
	$("#telefono input").val(infoGral[7]);
	$("#correoElect input").val(infoGral[8]);
	$("#nomUsuario input").val(infoGral[2]);
	$("#contrasena input").val(infoGral[3]);
	$("#pais input").val(infoGral[13]);
	$("#estado input").val(infoGral[14]);
	$("#ciudad input").val(infoGral[15]);

	if(infoGral[9] == 'S'){
		$(".avanceMisionero").removeClass("hide");
		$(".avanceMisionero").text("Avance misionero: $"+formatoMoneda(infoGral[11]));
	}else{
		$("#reportes").remove();
		$(".avanceMisionero").remove();
		$(".itemMis").remove();
	}
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

function cargaAvance(){
	muestraLoading();
	var data = [{name: "operacion", value: "cargaAvance"}];
	$.ajax({
		method: "POST",
		url: ruta + "php/iglesia.php",
		dataType: "json",
		data: data
	}).done(function(response){
		if(response[0][0] != 'N'){
			if(response[0][0] != null){
				$(".avanceMisionero").text('Ofrenda actual: $'+response[0][0]);
			}else{
				$(".avanceMisionero").text('Ofrenda actual: $0.00');
			}
			
		}

	}).fail(function(response){
	}).always(function(){
		ocultaLoading();
	})
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

function filtraCampos(){
	if($("#nombreMisionero").val() != ""){
		$("#misionesProyect tbody>tr").hide();
		$("#misionesProyect tbody td:contains-ci("+$("#nombreMisionero").val()+")").parent("tr").show();
	}else{
		$("#misionesProyect tbody>tr").show();
	}
}

$.extend($.expr[":"],{
	"contains-ci": function(elem,i,match,array){
		return(elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
})

function pantallas(pantalla){
	$(".pantalla").addClass("hide");
	$("#"+pantalla).removeClass("hide");

	if(pantalla == 'ofrendasEnviadas'){
		$("#histOfrendas").removeClass("hide");
		$("#detHistOfrendas").addClass("hide");
	}
}

function muestraLoading(){
	$("#loading").removeClass("hide");
}

function ocultaLoading(){
	$("#loading").addClass("hide");
}

function nobackbutton(){
   window.location.hash="no-back-button";
   window.location.hash="Again-No-back-button" //chrome
   window.onhashchange=function(){window.location.hash="no-back-button";}	
}
