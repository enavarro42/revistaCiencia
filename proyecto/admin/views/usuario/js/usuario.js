$(document).ready(function(){

  jQuery.validator.addMethod("noSpace", function(value, element) { 
  return value.indexOf(" ") < 0; 
}, "No puede escribir espacios");


$("#form_user").validate({
   rules: {
      busqueda: {
          noSpace: true
      }
   }
  });

	$("#eliminar").click(function(){

		var arreglo = [];

		$("input:checkbox[name=id]:checked").each(function(){
		    // add $(this).val() to your array
		    arreglo.push($(this).val());
		});
		
		param = arreglo.join();
		if(param != ""){

			  $.post("/revista/proyecto/admin/usuario/eliminar",
			  {
			    ids: param
			  },
			  function(data,status){
			    location.reload();
			  });

		}else{
			alert("Debe seleccionar un usuario");
		}

	});

	$("#checkall").click(function () {
    	var checkAll = $("#checkall").prop('checked');
        if (checkAll) {
            $(".case").prop("checked", true);
        } else {
            $(".case").prop("checked", false);
        }
    });


});
