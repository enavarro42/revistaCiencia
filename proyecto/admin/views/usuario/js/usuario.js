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


});