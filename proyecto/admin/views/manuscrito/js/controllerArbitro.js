var URL_BASE = "/revista/proyecto/admin/";

$(document).on("ready", function(){

	$("#img_arbitros").hide();
	$("#img_arbitros_postulados").hide();

	$("#aceptarAgregar").on("click", function(){
		$('#modalArbitroAgregado').modal('hide');
		location.reload();
	});



	$(".agregar").on("click", function(){

		// $.post(URL_BASE +'manuscrito/setArbitroPostulado', {"id_persona" : $(this).attr("id"), "id_manuscrito": $("#id_manuscrito").val() },  function(datos){

  //  			location.reload();

  //  		}, 'json');


   		$.ajax({
			type: "POST",
			dataType: "json",
			url: URL_BASE +'manuscrito/setArbitroPostulado',
			data: {"id_persona" : $(this).attr("id"), "id_manuscrito": $("#id_manuscrito").val() },
			beforeSend: function(){
				$("#img_arbitros").show();
			},

			success: function(data){
				$("#img_arbitros").hide();
				location.reload();
			}
		});

	});

	$(".quitar").on("click", function(){
		// $.post(URL_BASE +'manuscrito/quitarArbitro', {"id_persona" : $(this).attr("id"), "id_manuscrito": $("#id_manuscrito").val() },  function(datos){

  //  			location.reload();

  //  		}, 'json');


   		$.ajax({
			type: "POST",
			dataType: "json",
			url: URL_BASE +'manuscrito/quitarArbitro',
			data: {"id_persona" : $(this).attr("id"), "id_manuscrito": $("#id_manuscrito").val() },
			beforeSend: function(){
				$("#img_arbitros_postulados").show();
			},

			success: function(data){
				$("#img_arbitros_postulados").hide();
				location.reload();
			}
		});


	});

	$(".asignar").on("click", function(){

		// $.post(URL_BASE +'manuscrito/asignarArbitroManuscrito', {"id_persona" : $(this).attr("id"), "id_manuscrito": $("#id_manuscrito").val() },  function(datos){

   			
  //  			$('#modalArbitroAgregado').modal('show');

  //  		}, 'json');


   		$.ajax({
			type: "POST",
			dataType: "json",
			url: URL_BASE +'manuscrito/asignarArbitroManuscrito',
			data: {"id_persona" : $(this).attr("id"), "id_manuscrito": $("#id_manuscrito").val() },
			beforeSend: function(){
				$("#img_arbitros_postulados").show();
			},

			success: function(data){
				
				$('#modalArbitroAgregado').modal('show');
			},
			complete: function(){
				$("#img_arbitros_postulados").hide();
			}
		});

	});

	$(".solicitud").on("click", function(){
		// $.post(URL_BASE +'manuscrito/enviarSolicitud', {"id_persona" : $(this).attr("id"), "id_manuscrito": $("#id_manuscrito").val() })
		//  .done(function( data ) {
		// 	location.reload();
		//  });

		$.ajax({
			type: "POST",
			dataType: "json",
			url: URL_BASE +'manuscrito/enviarSolicitud',
			data: {"id_persona" : $(this).attr("id"), "id_manuscrito": $("#id_manuscrito").val() },
			beforeSend: function(){
				$("#img_arbitros_postulados").show();
			},

			success: function(data){
				// if(data.estatus == null)
				// 	$("#estatus_postulados").html('<i class="color_red fa fa-circle"> Por enviar</i>');
				// else if(parseInt(data.estatus) < 0)
				// 	$("#estatus_postulados").html('<i class="color_red">Enviado</i>');
				// else if(parseInt(data.estatus) == 0)
				// 	$("#estatus_postulados").html('<i class="color_red fa fa-circle"> No acepta</i>');
				// else if(parseInt(data.estatus) == 1)
				// 	$("#estatus_postulados").html('<i class="color_green fa fa-check"> Acepta</i>');
				// else if(parseInt(data.estatus) == 2)
				// 	$("#estatus_postulados").html('<i class="color_green fa fa-check"> Asignado</i>');
				

			},
			complete: function(){
				$("#img_arbitros_postulados").hide();
				location.reload();
			}
		});

	});

});