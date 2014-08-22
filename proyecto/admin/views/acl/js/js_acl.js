

$(document).on("ready", function(){

	$("#eliminar").click(function(){

		var arreglo = [];

		$("input:checkbox[name=id]:checked").each(function(){
		    // add $(this).val() to your array
		    arreglo.push($(this).val());
		});
		
		param = arreglo.join();
		if(param != ""){

			  $.post("/revista/proyecto/admin/acl/eliminar",
			  {
			    ids: param
			  },
			  function(data,status){
			    location.reload();
			  });

		}else{
			alert("Debe seleccionar un rol");
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