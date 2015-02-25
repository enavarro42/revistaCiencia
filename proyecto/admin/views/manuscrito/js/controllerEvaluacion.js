var URL_BASE = "/revista/proyecto/admin/"

$(document).on("ready", function(){

	$(".btn_ver_resp").on("click", function(){
		var id = $(this).attr("clave");


		$.post(URL_BASE + "manuscrito/ajaxVerRespuesta",
		{
		  	id_evaluacion: id
		},
		 function(data,status){

		 	var datos = JSON.parse(data);
		 	var code = "";

		 	if(datos['result']){

				code += "<h3>Sugerencia del &Aacute;rbitro</h3>"+
	        		"<p>"+datos['result'].sugerencia+"</p>";

				code += "<h3>Respuesta del Autor</h3>"+
					"<p>"+datos['result'].cambios_realizados+"</p>";
			}else{
				code += "<h3>No se encontraron resultados</h3>";
			}

			$("#contentenidoRespuesta").empty();
			$("#contentenidoRespuesta").append(code);

			$('#modalRespuesta').modal({
			  keyboard: false
			});

		});
	});

	$(".btn_detalles").on("click", function(){	

		var id = $(this).attr("id");

		$.post(URL_BASE + "manuscrito/ajaxEvaluacionDetalles",
		{
		  	id_evaluacion: id
		},
		 function(data,status){

		 	var datos = jQuery.parseJSON(data);
		 	var code = "";

			code += "<h3>Sugerencias</h3>"+
	        		"<p>"+datos['evaluacion'].sugerencia+"</p>";

			
			var arreglo = [];

			for(i = 0; i<datos['detalles'].length; i++){
				//preguntar si existe en el arreglo
				if($.inArray(datos['detalles'][i].id_seccion , arreglo ) < 0){

					if(i > 0){
						code += "</tbody></table>";
					}


					arreglo[datos['detalles'].length] = datos['detalles'][i].id_seccion;
					code += "<h3>"+datos['detalles'][i].seccion+"</h3>";


					code += "<table class='table table-bordered'>"+
        						"<thead>"+
        							"<tr class='active'>"+
        								"<th>Pregunta</th>"+
        								"<th>Evaluaci&oacute;n</th>"+
        							"</tr>"+
        						"</thead><tbody>";
        			code+= "<tr><td>"+datos['detalles'][i].pregunta+"</td><td>"+datos['detalles'][i].opcion+"</td></tr>";
				}else{
					code+= "<tr><td>"+datos['detalles'][i].pregunta+"</td><td>"+datos['detalles'][i].opcion+"</td></tr>";
				}
			}

			code += "</tbody></table>";

			$("#contentResult").empty();
			$("#contentResult").append(code);

			$('#modalDetalles').modal({
			  keyboard: false
			});


		});
	});



	$("#enviarEvaluacion").on("click", function(){

		var arreglo = [];
		var param = "";
		var seleccionados = 0;
		seleccionados = $("input:checkbox[name=id]:checked").length;

		if(seleccionados > 0){

			$("input:checkbox[name=id]:checked").each(function(){
			    arreglo.push($(this).val());
			});
			
			param = arreglo.join();
			if(param != ""){


				$.post(URL_BASE + "manuscrito/getEstatus",
				  function(data,status){

				  	$("#desicion").html(data);

						$('#modalEnviar').modal({
						  keyboard: false
						});
				  });



			}else{
				alert("Debe seleccionar por lo menos una evaluación");
			}
		}else{
			alert("Debe seleccionar por lo menos una evaluación");
		}

	});


	$("#aceptar").on("click", function(){

		var arreglo = [];
		var param = "";

		$("input:checkbox[name=id]:checked").each(function(){
		    arreglo.push($(this).val());
		});
		
		param = arreglo.join();
		
		if(param != ""){


			$.post(URL_BASE + "manuscrito/enviarEvaluacion",
			{
				id_manuscrito: $("#id_manuscrito").val(),
			    ids: param,
			    id_estatus: $("#desicion").val()
			},
			function(data,status){
			  	//recargar
			  	$('#modalEnviar').modal("hide");
			  	location.reload();
			});
		}

	});

});