var URL_BASE = "/revista/proyecto/";
$(document).on("ready", function(){

	$("#btn_aceptar").on("click", function(){
		if($("input[name=opcion]:checked").val()) {
			$.post(URL_BASE + 'arbitro/respSolicitud', {"id_persona" : $("#id_persona").val(), "id_manuscrito": $("#id_manuscrito").val(), "opcion": $("input[name=opcion]:checked").val() }, function(datos){
				location.href="/revista/proyecto/";
			});
		}else{
			alert("No ha seleccionado ninguna opción.");
		}
	});

});