var URL_BASE = "/revista/proyecto/";
$(document).on("ready", function(){

	$("#btn_aceptar").on("click", function(){
		if($("input[name=opcion]:checked").val()) {
			$.post(URL_BASE + 'arbitro/respSolicitud', {"id_persona" : $("#id_persona").val(), "id_manuscrito": $("#id_manuscrito").val(), "opcion": $("input[name=opcion]:checked").val() }, function(datos){
				data = JSON.parse(datos);
				if(parseInt(data.estatus)){
					alert("Solicitud aceptada...!");
					location.href="/revista/proyecto/";
				}else{
					alert("Ha rechazado la solicitud de arbitraje.");
					location.href="/revista/proyecto/";
				}
			});
		}else{
			alert("No ha seleccionado ninguna opción.");
		}
	});

});