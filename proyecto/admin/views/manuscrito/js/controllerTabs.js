var URL_BASE = "/revista/proyecto/admin/"

var paises = [];

var array_numeros = new Array();

var cont = 0;
var str_param = "";

campo = new Object();
var formdata = new FormData();

$(document).on("ready", function(){  

    $( "#tabs" ).tabs();
     
    $( "#tabs" ).tabs({ active: 0 });

    Inicializar();

    $("#btn_add").click(function(){
        cont++;
        agregar_campos(cont);
        
    });
     
    cargarRevistas('#revista');
    cargarAreas('#area');
    cargarIdiomas('#idioma');

 });

function agregar_campos(iter){
    
    
        var campos = '<div id="caja_campos_'+iter+'">'+
                    '<div class="controls">'+
                        '<label for="primerNombre">Primer nombre </label>'+
                        '<input type="text" name="campo['+iter+'][primerNombre]" id="campo_'+iter+'_primerNombre" class="form-control" value="" />'+
                        '<label class="error" id="error_'+iter+'_primerNombre"></label>'+
                    '</div>'+

                    '<div class="controls">'+
                        '<label for="segundoNombre">Segundo nombre </label>'+
                        '<input type="text" name="campo['+iter+'][segundoNombre]" id="campo_'+iter+'_segundoNombre" class="form-control" value="" />'+
                        '<label class="error" id="error_'+iter+'_segundoNombre"></label>'+
                    '</div>'+

                    '<div class="controls">'+
                        '<label for="apellido">Apellido </label>'+
                        '<input type="text" name="campo['+iter+'][apellido]" id="campo_'+iter+'_apellido" class="form-control" value="" />'+
                        '<label class="error" id="error_'+iter+'_apellido"></label>'+
                    '</div>'+
                    
                    
                    '<div class="controls">'+
                        '<label>G&eacute;nero</label>'+
                        '<div class="radio">'+
                            '<label>'+
                              '<input type="radio" name="campo['+iter+'][genero]" id="campo_'+iter+'_opcionRadio1" value="M" checked>'+
                              'Masculino'+
                            '</label>'+
                        '</div>'+
                        '<div class="radio">'+
                            '<label>'+
                              '<input type="radio" name="campo['+iter+'][genero]" id="campo_'+iter+'_opcionRadio2" value="F">'+
                              'Femenino'+
                            '</label>'+
                        '</div>'+
                    '</div>'+

                    '<div class="controls">'+
                        '<label for="pais">Pa&iacute;s</label>'+
                        '<select id="campo_'+iter+'_pais" name="campo['+iter+'][pais]" class="form-control required">'+
                             '<option value="0">-seleccione-</option>'+
                        '</select>'+
                        '<label class="error" id="error_'+iter+'_pais"></label>'+
                        '<br />'+
                    '</div>'+

                    '<div class="controls">'+
                        '<label for="email">Email: </label>'+
                        '<input type="email" name="campo['+iter+'][email]" id="campo_'+iter+'_email" class="form-control" value="" />'+
                        '<label class="error" id="error_'+iter+'_email"></label>'+
                    '</div>'+
                    
                    '<div class="controls">'+
                      '<label for="telefono">Tel&eacute;fono</label>'+
                      '<input type="text" name="campo['+iter+'][telefono]" id="campo_'+iter+'_telefono" class="form-control" value="" />'+
                      '<label class="error" id="error_'+iter+'_telefono"></label>'+
                    '</div>'+
                    
                       '<div class="radio">'+
                            '<label>'+
                              '<input type="radio" name="campo_autorPrincipal" id="campo_'+iter+'_autorPrincipal" value="'+iter+'">'+
                              'Autor principal'+
                            '</label>'+
                        '</div>'+
                    
                    '<div class="controls"><button id="btn_delete_'+iter+'" campo="'+iter+'" type="button" class="btn btn-danger">Borrar Autor</button></div>'+
                '<hr></div>';
        
        
        $("div#caja_campos_autor").append(campos);
        
       for(var i = 0; i < paises.length; i++){
           $("#campo_"+iter+"_pais").append('<option value="'+ paises[i].id_pais +'">' + paises[i].pais + '</option>');
       }
        
        
        if(array_numeros.length == 0){
            //establecer por defecto el primer autor
            $("#campo_"+iter+"_autorPrincipal").prop('checked',true);
        }

        array_numeros[array_numeros.length] = cont;
        
        $("button#btn_delete_"+iter+"").bind("click", function(){
            
            var cambiarAutorPrincipal = false;
            //verificamos si el autor q se va a borrar es el q esta check
            if($("#campo_"+iter+"_autorPrincipal").prop('checked')){ 
                cambiarAutorPrincipal = true;
            }
            
            
            $("div#caja_campos_"+iter+"").remove();

            var idx = array_numeros.indexOf(iter);
            // if(array_numeros[idx] == campo.autor){
            //     campo.autor = -1;
            //     $("#msj_cargar_autor").show();
            // }
            if(idx!=-1) 
                array_numeros.splice(idx, 1);

            if(cambiarAutorPrincipal && array_numeros.length > 0){
                $("#campo_"+array_numeros[0]+"_autorPrincipal").prop('checked',true);
            }
            
            
        }); 
        
        $("input#campo_"+iter+"_email").keyup(function(event){
                $.post(URL_BASE +'manuscrito/comprobarEmail', 'email='+$("input#campo_"+iter+"_email").val(), function(datos){
                $("label#error_"+iter+"_email").html(datos);
            }, 'html');
        });
        
}

function siError(e){
    alert('Ocurri&oacute; un error al realizar la petici&oacute;n: '+e.statusText);
}

function Inicializar(){
    $.post(URL_BASE +'ajax/getPaises', function(datos){
        for(var i = 0; i < datos.length; i++){
            var obj = {id_pais: datos[i].id_pais, pais: datos[i].nombre}
            paises.push(obj);
        }
        
        //agregar_campos(array_numeros.length);
            
    }, 'json');
    
    
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