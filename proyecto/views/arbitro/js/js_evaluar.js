var URL_BASE = "/revista/proyecto/";

var formdata = new FormData();


$(document).on("ready", function(){
  $("#progressBar").hide();

  $("#btn_enviar").click(function(){
    if(validarEnvio()){
      var archivo = $("#archivo").val();

      if(archivo){
        $("#progressBar").show();
      }
      subirDatos();
    }
  });


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
    var archivo = $("#archivo").val();

    if(archivo){

          $("#archivo").upload(URL_BASE + "arbitro/enviarEvaluacion", formdata
              ,function(data){
                      console.log("done", data);


                      if(!parseInt(data.status)){
                          document.getElementById("status").innerHTML = "Ocurri&oacute; un error, al enviar el documento."; 
                          $("#progressBar").hide();
                      }
                      else{
                          document.getElementById("status").innerHTML = "Completado..!";
                          alert("Evaluacion exitosa!");
                          url = URL_BASE + "arbitro/";
                          $(location).attr('href',url);
                      }

                      $("#progressBar").val(0);
              },$("#progressBar")
          );
    }else{

      $.ajax({
        url: URL_BASE + "arbitro/enviarEvaluacion",
        type: "POST",
        data: formdata,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(data){

            if(parseInt(data.status)){
                document.getElementById("status").innerHTML = "Completado..!";
                alert("Evaluacion exitosa!");
                url = URL_BASE + "arbitro/";
                $(location).attr('href',url);
            }
        }
      });

    }


                        

}

function validarEnvio(){
    var valido = true;
    var archivo = $("#archivo").val();
    var msj_archivo = "";
    var extension = "";

    extensiones_permitidas = new Array(".doc", ".docx");
   
   if (!archivo && $('input:radio[name=seccion3_opcion]:checked').attr("titulo") != "publicable") {
       msj_archivo = "No has seleccionado ning&uacute;n archivo";
       valido = false;
   }else{
      //recupero la extensión de este nombre de archivo
      
      //compruebo si la extensión está entre las permitidas
      if(archivo != ""){

        extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();

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
   }

   if(extension != "" && valido){
        var file = document.getElementById("archivo").files[0];
        
        formdata.append("archivo", file);
        formdata.append("manuscrito", $("#manuscrito").val());
   }

   for(var i = 0; i < 10; i++){
      if($('input:radio[name=seccion2_opcion_'+i+']:checked').length == 0){
        valido = false;
        $("#error_seccion2").html("Debe responder a todas las preguntas");
      }
   }

   if($('input:radio[name=seccion3_opcion]:checked').length == 0){
    valido = false;
    $("#error_seccion3").html("Debe tomar una decisi&oacute;n");
  }

  if(valido){


    for(var i = 0; i < 10; i++){
      formdata.append('seccion2_opcion_'+i, $('input:radio[name=seccion2_opcion_'+i+']:checked').val());
      formdata.append('pregunta_'+i, $('#pregunta_'+i).val());
    }

    formdata.append('preguntaSeccion3', $('#preguntaSeccion3').val());

    formdata.append('seccion3_opcion', $('input:radio[name=seccion3_opcion]:checked').val());

    formdata.append("manuscrito", $("#manuscrito").val());

    formdata.append('id_manuscrito', $('input:text[name=manuscrito]').val());

    formdata.append('comentario', $('#comentario').val());
    formdata.append('sugerencia', $('#sugerencia').val());
    formdata.append('cambios', $('#cambios').val());

    formdata.append('evaluar', $('input:radio[name=evaluar]:checked').val());


  }

   $("#error_archivo").html(msj_archivo);

   return valido;
}
	
