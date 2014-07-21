$(document).ready(function(){
    
    //var URL_BASE = "/revista/proyecto/";
    var URL_BASE = "";
    
    var getCiudades = function(){
        $.post(URL_BASE + 'ajax/getCiudades', 'pais='+$("#pais").val(), function(datos){
            $("#ciudad").html('');
            for(var i = 0; i < datos.length; i++){
                $('#ciudad').append('<option value=">'+ datos[i].id +'">' + datos[i].ciudad + '</option>');
            }
        }, 'json');
    }
    
    $('#pais').change(function(){
        if(!$('#pais').val()){
            $('#ciudad').html('');
        }else{
            getCiudades();
        }
        
    });
    
    $("#btn_insertar").click(function(){
        $.post(URL_BASE + 'ajax/insertarCiudad', 'pais='+$("#pais").val() + '&ciudad='+$('#ins_ciudad').val());
        $("#ins_ciudad").val("");
        getCiudades();
    });
});

