var URL_BASE = "/revista/proyecto/admin/";

var objeto = new Object();

var array_numeros = new Array();

var cont = 0;

objeto.iter = 0;

$(document).on("ready", function(){


    $("#btn_enviar").on("click", function(){
            
        if(validarDatos(array_numeros.length)){
            // alert("puyao todo..!");
            $("#progressBar").show();
            subirDatos();
        }
        
    });

	$("#btn_add_autor").on("click", function(){

        if(array_numeros.length == 0){
            agregarCampos(cont);
        }else{
            cont++;
            agregarCampos(cont);
        }


	});


	$("input#responsableEmail").on("focusout", function(){
        $.post(URL_BASE +'manuscrito/getEmail', 'email='+$("input#responsableEmail").val(), function(datos){
            // $("label#error_responsableCorreo").html(datos);

            // datos = JSON.parse(datos);

            // si hay errores
            if(datos.status == 1){
            	// alert(datos.error);
                //email invalido
            	$("label#error_responsableEmail").html(datos.error);
                $( "label#error_responsableEmail" ).removeClass( "ms-info-warning" );
                $( "label#error_responsableEmail" ).addClass( "error" );
            }else if(datos.status == 2){
            	// alert(datos.datos.primerNombre + " ERROR: "+ datos.error);
                //email en uso y luego se muestra el nombre y apellido del usuario
                $("label#error_responsableEmail").html(datos.error);
                $( "label#error_responsableEmail" ).removeClass( "error" );
                $( "label#error_responsableEmail" ).addClass( "ms-info-warning" );

                $("input#responsableNombre").val(datos.datos.primerNombre.trim()); 

                $("input#responsableApellido").val(datos.datos.apellido.trim());

            }else{
                $("label#error_responsableEmail").html("");
            }

            // alert(datos.toString());
            

        }, 'JSON');
    });


    // $("input#responsableCorreo").on("keyup", function(event){
    //     $.post(URL_BASE +'manuscrito/comprobarEmail', 'email='+$("input#responsableCorreo").val(), function(datos){
    //         $("label#error_responsableCorreo").html(datos);
    //     }, 'html');
    // });

    cargarRevistas('#revista');
    cargarAreas('#area');
    cargarIdiomas('#idioma');

    $("#progressBar").hide();

});


$.fn.upload = function(remote,data,successFn,progressFn) {
        // if we dont have post data, move it along
        if(typeof data != "object") {
                progressFn = successFn;
                successFn = data;
        }
        return this.each(function() {
                if($(this)[0].files[0]) {
                        //var formData = new FormData();
                        //formData.append($(this).attr("name"), $(this)[0].files[0]);
                        formdata.append($(this).attr("name"), $(this)[0].files[0]);
                        // if we have post data too
                        
                        /*if(typeof data == "object") {
                                for(var i in data) {
                                        formData.append(i,data[i]);
                                }
                        }*/

                        // do the ajax request
                        $.ajax({
                                url: remote,
                                type: 'POST',
                                xhr: function() {
                                        myXhr = $.ajaxSettings.xhr();
                                        if(myXhr.upload && progressFn){
                                                myXhr.upload.addEventListener('progress',function(prog) {
                                                        var value = ~~((prog.loaded / prog.total) * 100);

                                                        // if we passed a progress function
                                                        if(progressFn && typeof progressFn == "function") {
                                                                progressFn(prog,value);

                                                        // if we passed a progress element
                                                        } else if (progressFn) {
                                                                $(progressFn).val(value);
                                                        }
                                                }, false);
                                        }
                                        return myXhr;
                                },
                                data: formdata,
                                dataType: "json",
                                cache: false,
                                contentType: false,
                                processData: false,
                                complete : function(res) {
                                        var json;
                                        try {
                                                json = JSON.parse(res.responseText);
                                        } catch(e) {
                                                json = res.responseText;
                                        }

                                        if(successFn) successFn(json);
                                }
                        });
                }
        });

};


function subirDatos(){
    $("#archivo").upload(URL_BASE + "manuscrito/insertarManuscrito", formdata
    ,function(data){
            console.log("done", data);


            if(!parseInt(data.status)){
                $.each(data['data'], function(index, value) {
                    $("#error_"+data['data'][index]['idx']+"_primerNombre").html(data['data'][index]['primerNombre']);
                    $("#error_"+data['data'][index]['idx']+"_apellido").html(data['data'][index]['apellido']);
                    $("#error_"+data['data'][index]['idx']+"_correo").html(data['data'][index]['email']);
                });
               // alert(data[0].nombre);
                document.getElementById("status").innerHTML = "Ocurri&oacute; un error, ver&iacute;fica los datos..."; 
                $("#progressBar").hide();
                //alert("Los datos fueron almacenados exitosamente...!");
                //document.getElementById("status").innerHTML = event.target.responseText; 
            }
            else{
                document.getElementById("status").innerHTML = "Datos almacenados corr&eacute;ctamente...!";
                alert("Nuevo Manuscrito creado con éxito!");
                url = URL_BASE + "manuscrito/";
                $(location).attr('href',url);
            }

            $("#progressBar").val(0);
        },$("#progressBar")
    );
            

}


function cargarRevistas(id){
    $.post(URL_BASE +'ajax/getRevistas', function(datos){
        for(var i = 0; i < datos.length; i++){
                $(id).append('<option value="'+ datos[i].issn +'">' + datos[i].nombre + '</option>');
            }
    }, 'json');
    
}


function cargarAreas(id){
    $.post(URL_BASE +'ajax/getAreas', function(datos){
        for(var i = 0; i < datos.length; i++){
                $(id).append('<option value="'+ datos[i].id_area +'">' + datos[i].nombre + '</option>');
            }
    }, 'json');
    
}


function cargarIdiomas(id){
    $.post(URL_BASE +'ajax/getIdiomas', function(datos){
        for(var i = 0; i < datos.length; i++){
                $(id).append('<option value="'+ datos[i].id_idioma +'">' + datos[i].nombre + '</option>');
            }
    }, 'json');
    
}


function agregarCampos(iter){

		code = '<div id="caja_campos_'+iter+'">'+
                    '<div class="controls">'+
                        '<label for="email">Correo </label>'+
                        '<input type="text" name="campo['+iter+'][email]" id="campo_'+iter+'_email" class="form-control" value="" />'+
                        '<label class="error" id="error_'+iter+'_email"></label>'+
                    '</div>'+
                    '<div class="controls">'+
                        '<label for="primerNombre">Nombre </label>'+
                        '<input type="text" name="campo['+iter+'][primerNombre]" id="campo_'+iter+'_primerNombre" class="form-control" value="" />'+
                        '<label class="error" id="error_'+iter+'_primerNombre"></label>'+
                    '</div>'+
'<div class="controls">'+
                        '<label for="apellido">Apellido </label>'+
                        '<input type="text" name="campo['+iter+'][apellido]" id="campo_'+iter+'_apellido" class="form-control" value="" />'+
                        '<label class="error" id="error_'+iter+'_apellido"></label>'+
                    '</div>'+
                    '<button type="button" class="btn btn-danger btn-margin" id="quitar_'+iter+'">Quitar</button>'
                '</div>';

        $("div#panelAutores").append(code);

        array_numeros[array_numeros.length] = iter;
		objeto.iter = objeto.iter + 1;



		$("#quitar_"+iter+"").on("click", function(){

                        
            $("div#caja_campos_"+iter+"").remove();

            var idx = array_numeros.indexOf(iter);

            if(idx != -1) 
                array_numeros.splice(idx, 1);

            // alert(array_numeros.join());
            
            
        });

        $("input#campo_"+iter+"_email").on("focusout", function(){
            $.post(URL_BASE +'manuscrito/getEmail', 'email='+$("input#campo_"+iter+"_email").val(), function(datos){
                // $("label#error_responsableCorreo").html(datos);

                // datos = JSON.parse(datos);

                // si hay errores
                if(datos.status == 1){
                    // alert(datos.error);
                    //email invalido
                    $("label#error_"+iter+"_email").html(datos.error);
                    $( "label#error_"+iter+"_email" ).removeClass( "ms-info-warning" );
                    $( "label#error_"+iter+"_email" ).addClass( "error" );
                }else if(datos.status == 2){
                    // alert(datos.datos.primerNombre + " ERROR: "+ datos.error);
                    //email en uso y luego se muestra el nombre y apellido del usuario
                    $("label#error_"+iter+"_email").html(datos.error);
                    $( "label#error_"+iter+"_email" ).removeClass( "error" );
                    $( "label#error_"+iter+"_email" ).addClass( "ms-info-warning" );

                    $("input#campo_"+iter+"_primerNombre").val(datos.datos.primerNombre.trim()); 

                    $("input#campo_"+iter+"_primerNombre").prop('readonly', true);

                    $("input#campo_"+iter+"_apellido").val(datos.datos.apellido.trim());
                    $("input#campo_"+iter+"_apellido").prop('readonly', true);

                }else{
                    $("label#error_"+iter+"_email").html("");
                    $("input#campo_"+iter+"_primerNombre").prop('readonly', false);
                    $("input#campo_"+iter+"_primerNombre").val(""); 
                    $("input#campo_"+iter+"_apellido").val("");
                    $("input#campo_"+iter+"_apellido").prop('readonly', false);
                }

                // alert(datos.toString());
            

            }, 'JSON');
        });

        // alert(array_numeros.join() ); 
}


//corregir este metodo de validacion

function validarDatos(iter){
     var valido = true;
     formdata = new FormData();

     //validadndo el autor responsable

    var responsableNombre = $("#responsableNombre").val();
    var msj_responsable_nombre = "";

    var responsableApellido = $("#responsableApellido").val();
    var msj_responsable_apellido = "";
    
    var responsableEmail = $("#responsableEmail").val();
    var msj_responsable_email = "";

    var exp = /^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/;

    if(responsableNombre == ""){
        msj_responsable_nombre = "El campo nombre est&aacute; vac&iacute;o";
        valido = false;
    }
    else if(!exp.test(responsableNombre)){
        msj_responsable_nombre = "El nombre no es v&aacute;lido";
        valido = false;
    }

    exp = /^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/;

    test_apellido1 = true;
    test_apellido2 = true;

    if(responsableApellido == ""){
        msj_responsable_apellido = "El campo apellido est&aacute; vac&iacute;o";
        valido = false;
    }
    else if(!exp.test(responsableApellido)){
        // msj_responsable_apellido = "El apellido no es v&aacute;lido";
        //valido = false;
        test_apellido1 = false;
    }

    exp = /^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/;

    if(!exp.test(responsableApellido)){
        // msj_responsable_apellido = "El apellido no es v&aacute;lido";
        //valido = false;
        test_apellido2 == false
    }

    if(test_apellido1 == false && test_apellido2 == false){
        msj_responsable_apellido = "El apellido no es v&aacute;lido";
        valido = false;
    }

    //validar email
    // exp = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.([a-zA-Z]{2,4})+$/;

    // exp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    //     alert(exp.test(responsableEmail));

    // if(responsableEmail == ""){
    //     msj_responsable_email = "El campo email est&aacute; vac&iacute;o";
    //     valido = false;
    // }

    // else if(!exp.test(responsableEmail)){
    //     msj_responsable_email = "El email no es v&aacute;lido";
    //     valido = false;
        
    // }
    
    
    
    if(valido){

        formdata.append("responsableNombre", responsableNombre);
        formdata.append("responsableApellido", responsableApellido);
        formdata.append("responsableEmail", responsableEmail);
        
    }

    $("#error_responsableNombre").html(msj_responsable_nombre);
    $("#error_responsableApellido").html(msj_responsable_apellido);
    // $("#error_responsableCorreo").html(msj_responsable_email);


     //validando los co-autores
     for(i = 0; i < iter; i++){
        var idx = array_numeros[i];

        var primerNombre = $("#campo_"+idx+"_primerNombre").val();
        var msj_primerNombre = "";


        var apellido = $("#campo_"+idx+"_apellido").val();
        var msj_apellido = "";
        
        var email = $("#campo_"+idx+"_email").val();
        var msj_email = "";

        var exp = /^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/;

        if(primerNombre == ""){
            msj_primerNombre = "El campo primer nombre est&aacute; vac&iacute;o";
            valido = false;
        }
        else if(!exp.test(primerNombre)){
            msj_primerNombre = "El nombre no es v&aacute;lido";
            valido = false;
        }

        exp = /^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/;

        test_apellido1 = true;
        test_apellido2 = true;

        if(apellido == ""){
            msj_apellido = "El campo apellido est&aacute; vac&iacute;o";
            valido = false;
        }
        else if(!exp.test(apellido)){
            msj_apellido = "El apellido no es v&aacute;lido";
            //valido = false;
            test_apellido1 = true;
        }

        exp = /^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/;

        if(!exp.test(apellido)){
            // msj_apellido = "El apellido no es v&aacute;lido";
            //valido = false;
            test_apellido2 = true;
        }

        if(test_apellido1 == false && test_apellido2 == false){
            msj_apellido = "El apellido no es v&aacute;lido";
            valido = false;
        }

        //validar email
        // exp = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.([a-zA-Z]{2,4})+$/;

        // if(email == ""){
        //     msj_email = "El campo email est&aacute; vac&iacute;o";
        //     valido = false;
        // }
        // else if(!exp.test(email)){
        //     msj_email = "El email no es v&aacute;lido";
        //     valido = false;
        // }
        
        
        
        if(valido){

            formdata.append("nombre"+i, primerNombre);
            formdata.append("apellido"+i, apellido);
            formdata.append("email"+i, email);
            
        }

        $("#error_"+idx+"_primerNombre").html(msj_primerNombre);
        $("#error_"+idx+"_apellido").html(msj_apellido);
        // $("#error_"+idx+"_email").html(msj_email);
    }
    
    //validando la seccion de manuscrito
    var titulo = $("#titulo").val();
    var msj_titulo = "";
    var resumen = $("#resumen").val();
    var msj_resumen = "";
    var revista = $("#revista").val();
    var msj_revista = "";
    var area = $("#area").val();
    var msj_area = "";
    var idioma = $("#idioma").val();
    var msj_idioma = "";
    
    var palabrasClave = $("#palabrasClave").val();
    var msj_palabrasClave = "";
    
    var archivo = $("#archivo").val();
    var msj_archivo = "";
    
    if(titulo == ""){
        msj_titulo = "El campo t&iacute;tulo est&aacute; vac&iacute;o";
        valido = false;
    }
    
    if(resumen == ""){
        msj_resumen = "El campo resumen est&aacute; vac&iacute;o";
        valido = false;
    }
    
    if(revista == 0){
        msj_revista = "Debe seleccionar una revista";
        valido = false;
    }
    
    if(area == 0){
        msj_area = "Debe seleccionar un &aacute;rea";
        valido = false;
    }
    
    if(idioma == 0){
        msj_idioma = "Debe seleccionar un idioma";
        valido = false;
    }
    
    if(palabrasClave == ""){
        msj_palabrasClave = "No ha escrito las palabras clave";
        valido = false;
    }
    
    
   extensiones_permitidas = new Array(".doc", ".docx"); 
   
   if (!archivo) {
       msj_archivo = "No has seleccionado ning&uacute;n archivo";
       valido = false;
   }else{
      //recupero la extensión de este nombre de archivo
      extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
      //alert (extension);
      //compruebo si la extensión está entre las permitidas
      permitida = false;
      for (var i = 0; i < extensiones_permitidas.length; i++) {
         if (extensiones_permitidas[i] == extension) {
         permitida = true;
         break;
         }
      }
      if (!permitida) {
         msj_archivo = "Comprueba la extensi&oacute;n de los archivos a subir. S&oacute;lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
         valido = false;
       }
   }
   
    if(valido){
        
        formdata.append("titulo", titulo);
        formdata.append("resumen", resumen);
        formdata.append("revista", revista);
        formdata.append("area", area);
        formdata.append("idioma", idioma);
        formdata.append("palabrasClave", palabrasClave);
        formdata.append("iter", iter);
        
        // formdata.append("idx_autor", idx_autor); 
        
        var file = document.getElementById("archivo").files[0];
        
        formdata.append("archivo", file);
    }
   
   $("#error_titulo").html(msj_titulo);
   $("#error_resumen").html(msj_resumen);
   $("#error_revista").html(msj_revista);
   $("#error_area").html(msj_area);
   $("#error_idioma").html(msj_idioma);
   $("#error_palabrasClave").html(msj_palabrasClave);
   $("#error_archivo").html(msj_archivo);
   
    
     return valido;
     
 }