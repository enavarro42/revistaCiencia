$(document).ready(function(){
    
    var URL_BASE = "/revista/proyecto/";
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
    
    ///revista/proyecto
    
    
    $("input#usuario").keyup(function(event){
            $.post(URL_BASE + '/registro/comprobarUsuario', 'usuario='+$("input#usuario").val(), function(datos){
            $("label#error_usuario").html(datos);
        }, 'html');
    });

    $("input#usuario").focusout(function(){
       $.post(URL_BASE + '/registro/comprobarUsuario', 'usuario='+$("input#usuario").val(), function(datos){
              $("label#error_usuario").html(datos);
          }, 'html');
    });
    
    $("input#email").keyup(function(event){
            $.post(URL_BASE + '/registro/comprobarEmail', 'email='+$("input#email").val(), function(datos){
            $("label#error_email").html(datos);
        }, 'html');
    });

    $("input#email").focusout(function(){
       $.post(URL_BASE + '/registro/comprobarEmail', 'email='+$("input#email").val(), function(datos){
            $("label#error_email").html(datos);
        }, 'html');
    });


    $("#enviar").on('click', function() {
      $.blockUI({message: "Procesando...!", css: { 
          border: 'none', 
          padding: '15px', 
          backgroundColor: '#000', 
          '-webkit-border-radius': '10px', 
          '-moz-border-radius': '10px', 
          opacity: .5, 
          color: '#fff' 
      }});
    });
    
    
});


