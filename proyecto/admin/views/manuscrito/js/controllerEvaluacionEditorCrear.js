var URL_BASE = "/revista/proyecto/admin/";

var formdata = new FormData();

$(document).on("ready", function(){
  $("#progressBar").hide();
  $("#error_archivo").hide();
  $("#error_observacion").hide();


  $("#btn_enviar").click(function(){
    if(validarEnvio()){

      var archivo = $("#archivo").val();
      if(!archivo){
        enviarDatos();
      }else{
        $("#progressBar").show();
        subirDatos();
      }

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
    $("#archivo").upload(URL_BASE + "manuscrito/enviarEvaluacionEditor", formdata
    ,function(data){
            console.log("done", data);


            if(!parseInt(data.status)){
                document.getElementById("status").innerHTML = "Ocurri&oacute; un error, al enviar el documento."; 
                $("#progressBar").hide();
            }
            else{
                document.getElementById("status").innerHTML = "Completado..!";
                alert("La evaluación se realizó con éxito!");
                url = URL_BASE + "manuscrito/evaluaciones";
                $(location).attr('href',url);
            }

            $("#progressBar").val(0);
    },$("#progressBar")
);
                        

}


function enviarDatos(){
    
    $.post(URL_BASE + "manuscrito/enviarEvaluacionEditor",{archivo: -1, manuscrito: $("#manuscrito").val(), estatus: $("#tipoEstatus").val(), observaciones: $("#observaciones").val()},
      function(data,status){
        var datos = jQuery.parseJSON(data);
          if(!parseInt(datos['status']) == 1){
            alert("Ocurrió un error inesperado al realizar la evaluación.");
            url = URL_BASE + "manuscrito/evaluaciones";
            $(location).attr('href',url);
          }else{
            alert("La evaluación se realizó con éxito!");
            url = URL_BASE + "manuscrito/evaluaciones";
            $(location).attr('href',url);
          }
      });

}

function validarEnvio(){
    var valido = true;
    var archivo = $("#archivo").val();
    var observacion = $("#observaciones").val();
    var estatus = $("#tipoEstatus").val();
    var msj_archivo = "";

    extensiones_permitidas = new Array(".doc", ".docx"); 
   
   if (archivo){

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
         $("#error_archivo").show();
         valido = false;
      }

      var file = document.getElementById("archivo").files[0];
        
      formdata.append("archivo", file);
      formdata.append("manuscrito", $("#manuscrito").val());
      formdata.append("estatus", estatus);
      formdata.append("observaciones", observacion);
   }else{
      formdata.append("archivo", -1);
      formdata.append("manuscrito", $("#manuscrito").val());
      formdata.append("estatus", estatus);
      formdata.append("observaciones", observacion);
   }

   if(estatus == 'corregirFormato' && (observacion == '' || observacion.length == 0)){
      $("#error_observacion").html("Dede llenar el campo observaciones.");
      $("#error_observacion").show();
      valido = false;
   }

   $("#error_archivo").html(msj_archivo);

   return valido;
}