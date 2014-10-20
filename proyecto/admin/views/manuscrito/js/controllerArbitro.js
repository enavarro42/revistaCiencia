var URL_BASE = "/revista/proyecto/admin/";

$(document).on("ready", function(){

	$(".agregar").on("click", function(){

		$.post(URL_BASE +'manuscrito/setArbitroPostulado', {"id_persona" : $(this).attr("id"), "id_manuscrito": $("#id_manuscrito").val() },  function(datos){

   			location.reload();

   		}, 'json');

	});

	$(".quitar").on("click", function(){
		$.post(URL_BASE +'manuscrito/quitarArbitro', {"id_persona" : $(this).attr("id"), "id_manuscrito": $("#id_manuscrito").val() },  function(datos){

   			location.reload();

   		}, 'json');
	});

	$(".asignar").on("click", function(){

		$.post(URL_BASE +'manuscrito/asignarArbitroManuscrito', {"id_persona" : $(this).attr("id"), "id_manuscrito": $("#id_manuscrito").val() },  function(datos){

   			location.reload();

   		}, 'json');

	});

	$(".solicitud").on("click", function(){
		$.post(URL_BASE +'manuscrito/enviarSolicitud', {"id_persona" : $(this).attr("id"), "id_manuscrito": $("#id_manuscrito").val() })
		 .done(function( data ) {
			location.reload();
		 });
	});

});