var URL_BASE = "/revista/proyecto/";

var formdata = new FormData();

$(document).on("ready", function(){
  $("#progressBar").hide();

  $("#btn_enviar").click(function(){
    if(validarEnvio()){
      $.blockUI({message: "Procesando...!", css: { 
          border: 'none', 
          padding: '15px', 
          backgroundColor: '#000', 
          '-webkit-border-radius': '10px', 
          '-moz-border-radius': '10px', 
          opacity: .5, 
          color: '#fff' 
      }});
      $("#progressBar").show();
      subirDatos();
    }
  });



  // $("#btn_test").click(function(){
  //     cont = $("#contador").val();
  //     //alert(cont);
  //     for(i = 0; i<cont; i++){
  //       alert($("#resp_"+i).val());
  //     }
  // });

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
    $("#archivo").upload(URL_BASE + "manuscrito/enviarCorreccion", formdata
    ,function(data){
            console.log("done", data);
            $.unblockUI();

            if(!parseInt(data.status)){
                document.getElementById("status").innerHTML = "Ocurri&oacute; un error, al enviar el documento."; 
                $("#progressBar").hide();
            }
            else{
                document.getElementById("status").innerHTML = "Completado..!";
                alert("Manuscrito enviado con éxito!");
                url = URL_BASE + "manuscrito/misManuscritos/"+$("#manuscrito").val()+"/1";
                // $(location).attr('href',url);
            }

            $("#progressBar").val(0);
    },$("#progressBar")
);
                        

}


function validarEnvio(){
    var valido = true;
    var archivo = $("#archivo").val();
    var tipo = $("#tipo").val();
    var msj_archivo = "";
    var msj_evaluacion = "";

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

   if(parseInt(tipo) == 2){

      cont = parseInt($("#contador").val());


      for(i = 0; i<cont; i++){
        if($("#resp_"+i).val() == ""){
          valido = false;
          msj_evaluacion = "Debe llenar el campo de Respuesta al &aacute;rbitro.";
        }
      }
    }

   if(valido){
        var file = document.getElementById("archivo").files[0];
        
        formdata.append("archivo", file);
        formdata.append("manuscrito", $("#manuscrito").val());
        formdata.append("contador", cont);
        formdata.append("tipo", parseInt(tipo));

        if(tipo == 2){

          for(i = 0; i<cont; i++){
            formdata.append("id_"+i, $("#id_"+i).val());
            formdata.append("resp_"+i, $("#resp_"+i).val());
          }
        }
   }

   $("#error_archivo").html(msj_archivo);
   $("#error_evaluacion").html(msj_evaluacion);

   return valido;
}