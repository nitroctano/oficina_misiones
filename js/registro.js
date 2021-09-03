$(function(){
	$("#formRegistro").submit(function(){
		var data = $(this).serializeArray();
		$.ajax({
			method: 'POST',
			url: '../php/registro.php',
			dataType: 'json',
			data: data
		}).done(function(response){
			if(response[0] == 0){
				muestraMensaje("rojo","El usuario ya está en uso, prueba con otro");
			}else if(response[0] == 'CI'){
				muestraMensaje("rojo","Clave de registro incorrecta");
			}else{
				muestraMensaje("verde","Registro Exitoso");
				setTimeout(function(){
					window.location.href="../index.html"
				}, 1000);
			}
		}).fail(function(response){
			muestraMensaje("rojo","Error de conexión");
		})	
	});

	$("#confirm").focusout(function(){
		if($(this).val() != $("#pass").val()){
			muestraMensaje("rojo","La contraseña no coincide");
			$(this).val("");
		}
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