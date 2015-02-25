var mostrar = 0;
$(document).ready(function(){

  $(".my_input").hide();

  $("input[name=optionsRadios]").on('change', function(){
    valor = $(this).val();
     if(valor == 'option1'){
       $(".my_input").hide();
     }else{
       $(".my_input").show();
     }

  });
    
    var URL_BASE = "/revista/proyecto/admin/";
    //var URL_BASE = "";
    
    $('input#usuario').keydown(function(event){ 
       if(event.keyCode != 8)
           $("label#error_usuario").text("");
    });
    
    $("input#pass").keyup(function(event){
       if(event.keyCode != 8)
           $("label#error_pass").text("");
    });
    
    $("input#confirmar").keyup(function(event){
       if(event.keyCode != 8)
           $("label#error_confir").text("");
    });
    
    $("input#primerNombre").keyup(function(event){
       if(event.keyCode != 8)
           $("label#error_nombre").text("");
    });

    $("input#segundoNombre").keyup(function(event){
       if(event.keyCode != 8)
           $("label#error_nombre").text("");
    });
    
    $("input#apellido").keyup(function(event){
       if(event.keyCode != 8)
           $("label#error_apellido").text("");
    });

    $("input#din").keyup(function(event){
       if(event.keyCode != 8)
           $("label#error_din").text("");
    });
    
    $("input#email").keyup(function(event){
       if(event.keyCode != 8)
           $("label#error_email").text("");
    });
    
    $("input#telefono").keyup(function(event){
       if(event.keyCode != 8)
           $("label#error_telefono").text("");
    });
    
    $("select#pais").change(function(){
       if($("select#pais").val() != "")
           $("label#error_pais").text("");
    });

    $("#cambiarPass").on("click", function(){
      if($("#nuevaPass").val() == 0){
        $("#cajaPass").fadeIn();
        mostrar = 1;
        $("#nuevaPass").val("1");
        $("#cambiarPass").html("Cancelar Cambiar Contrase&ntilde;a");
      }else{
        $("#cajaPass").fadeOut();
        mostrar = 0;
        $("#nuevaPass").val("0");
        $("#cambiarPass").html("Cambiar Contrase&ntilde;a");
      }

    });
    
    ///revista/proyecto
    
    
    $("input#usuario").keyup(function(event){

      if($("input#tipoAccion").val() == 'editar'){
            $.post(URL_BASE + '/usuario/comprobarUsuario', 
              {
                usuario: $("input#usuario").val(),
                id_persona: $("input#id_persona").val()
              },
              function(datos){
                $("label#error_usuario").html(datos);
              },
              'html');
        }else{
            $.post(URL_BASE + '/usuario/comprobarUsuario', 
              {
                usuario: $("input#usuario").val()
              },
              function(datos){
                $("label#error_usuario").html(datos);
              },
              'html');
        }
    });

    $("input#usuario").focusout(function(){
      if($("input#tipoAccion").val() == 'editar'){
            $.post(URL_BASE + '/usuario/comprobarUsuario', 
              {
                usuario: $("input#usuario").val(),
                id_persona: $("input#id_persona").val()
              },
              function(datos){
                $("label#error_usuario").html(datos);
              },
              'html');
        }else{
            $.post(URL_BASE + '/usuario/comprobarUsuario', 
              {
                usuario: $("input#usuario").val()
              },
              function(datos){
                $("label#error_usuario").html(datos);
              },
              'html');
        }
    });
    
    $("input#email").keyup(function(event){
      if($("input#tipoAccion").val() == 'editar'){
            $.post(URL_BASE + '/usuario/comprobarEmail',
            {
              email: $("input#email").val(),
              id_persona: $("input#id_persona").val()
            },
            function(datos){
              $("label#error_email").html(datos);
            }, 'html');
      }else{
          $.post(URL_BASE + '/usuario/comprobarEmail',
            {
              email: $("input#email").val()
            },
            function(datos){
              $("label#error_email").html(datos);
            }, 'html');
      }
    });

    $("input#email").focusout(function(){
      if($("input#tipoAccion").val() == 'editar'){
            $.post(URL_BASE + '/usuario/comprobarEmail',
            {
              email: $("input#email").val(),
              id_persona: $("input#id_persona").val()
            },
            function(datos){
              $("label#error_email").html(datos);
            }, 'html');
      }else{
          $.post(URL_BASE + '/usuario/comprobarEmail',
            {
              email: $("input#email").val()
            },
            function(datos){
              $("label#error_email").html(datos);
            }, 'html');
      }
    });
    
    
});