var URL_BASE = "/revista/proyecto/admin/"

$(document).on("ready", function(){

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
	        		"<p>"+datos['evaluacion'].sugerencia+"</p>"+

	        		"<h3>Cambios Realizados</h3>"+
	        		"<p>"+datos['evaluacion'].cambios+"</p>";

			
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

				  $.post(URL_BASE + "manuscrito/enviarEvaluacion",
				  {
				  	id_manuscrito: $("#id_manuscrito").val(),
				    ids: param
				  },
				  function(data,status){
				  	//recargar
				  });

			}else{
				alert("ebe seleccionar por lo menos una evaluación");
			}
		}else{
			alert("Debe seleccionar por lo menos una evaluación");
		}

	});

});