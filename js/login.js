 ruta = "";

$(function(){
	$("#formLogin").submit(function(){
		muestraLoading();
		var data = $("#formLogin").serializeArray();
		$.ajax({
			method: 'POST',
			url: ruta + 'php/login.php',
			dataType: 'json',
			data: data
		}).done(function(response){
			if(response[0] == "OK"){
				muestraMensaje("verde","Ingreso Exitoso");
				setTimeout(function(){
					window.location.href="php/enrutador.php"
				}, 1000);
			}else{
				muestraMensaje("rojo","Usuario inválido")
			}
		}).fail(function(response){
			muestraMensaje("rojo","Error de conexión");
		}).always(function(){
			ocultaLoading();
		})
		
		
	})

	$("#recPass").click(function(){
		$("#sombra").removeClass("hide");
		$("#recContraseña").removeClass("hide");
	})

	$("#cerrarRecPass").click(function(){
		$("#sombra").addClass("hide");
		$("#recContraseña").addClass("hide");
	})

	$("#contenidoFormulario").submit(function(){
		var data = $(this).serializeArray();
		muestraLoading();
		$.ajax({
			method: 'POST',
			url: ruta + 'php/recordar.php',
			dataType: 'json',
			data: data
		}).done(function(response){
			if(response[0] == 0){
				muestraMensaje("rojo","El correo electronico o usuario incorrectos");
			}else{
				muestraMensaje("verde","Su contraseña ha sido enviada a su correo electrónico");
				$("#recContraseña").addClass("hide");
				$("#sombra").addClass("hide");
			}
		}).fail(function(response){
			muestraMensaje("rojo","Error de conexión");
		}).always(function(){
			ocultaLoading();
		})
	})
})

function muestraMensaje(tipo, mensaje){
	$("#sombra").removeClass("hide");
	$("#"+tipo).removeClass("hide");
	$("#"+tipo).text(mensaje);
	setTimeout(function(){
		$("#sombra").addClass("hide");
		$("#"+tipo).addClass("hide");
	}, 1000)
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