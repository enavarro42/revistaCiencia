var URL_BASE = "/revista/proyecto/admin/"

$(document).on("ready", function(){

	cargarAutores();

	$("#checkall").on("click", function () {
    	var checkAll = $("#checkall").prop('checked');
        if (checkAll) {
            $(".case").prop("checked", true);
        } else {
            $(".case").prop("checked", false);
        }
    });


    $("#agregar").on("click", function(){
    	if($("#id_persona").val() != ''){
		    $.post(URL_BASE +'manuscrito/setAutor', {"id_persona" : $("#id_persona").val(), "rol" : $("#rol").val(), "id_manuscrito" : $("#id_manuscrito").val()}, function(datos){
		        if(datos.result){
		        	$("#info").html('<div class="alert alert-success" id="alert_agregar" role="alert">'+datos.response+
                      '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+
                    '</div>');
		        	$("#alert_agregar").alert();

		        	cargarAutores();
		        	$("#id_persona").val("");
		        }else{
		        	$("#info").html('<div class="alert alert-warning" id="alert_agregar" role="alert">'+datos.response+
                      '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+
                    '</div>');
                    $("#alert_agregar").alert();
		        }
		    }, 'json');
    	}else{
    		alert("Debe proporcionar un ID");
    	}
    });


    $("#eliminar").on("click", function(){

		var arreglo = [];
		var param = "";

		var total = 0;
		var seleccionados = 0;

		total = $("input:checkbox[name=id]").length;
		seleccionados = $("input:checkbox[name=id]:checked").length;

		if(total != seleccionados){

			$("input:checkbox[name=id]:checked").each(function(){
			    arreglo.push($(this).val());
			});
			
			param = arreglo.join();
			if(param != ""){

				  $.post(URL_BASE + "manuscrito/eliminarAutores",
				  {
				  	id_manuscrito: $("#id_manuscrito").val(),
				    ids: param
				  },
				  function(data,status){
				  	$("#autores").empty();
				    cargarAutores();
				  });

			}else{
				alert("Debe seleccionar un Autor");
			}
		}else{
			alert("No esta permitido borrar todos los autores");
		}

	});

});

function cargarAutores(){

	$.post(URL_BASE +'manuscrito/getResponsable', 'id_manuscrito='+$("#id_manuscrito").val(), function(datos){
        if(parseInt(datos)){
        	$("#rol").empty();
        	$("#rol").append(
        		"<option value='Co-Autor'>Co-Autor</option>"
        		);
        }else{
        	$("#rol").empty();
        	$("#rol").append(
        		"<option value='Autor'>Autor</option>"+
        		"<option value='Co-Autor'>Co-Autor</option>"
        		);
        }
    }, 'json');

	$.post(URL_BASE +'manuscrito/getAutoresManuscrito', 'id_manuscrito='+$("#id_manuscrito").val(), function(datos){
		$("#autores").empty();
        for(var i = 0; i < datos.length; i++){
                $("#autores").append(
                	'<tr><td><input type="checkbox" name="id" class="case" value="'+datos[i].id_persona+'"></td>'+
                   	'<td>'+datos[i].id_persona+'</td>'+
                   	'<td>'+datos[i].nombrecompleto+'</td></tr>'
                	);
            }
    }, 'json');
}