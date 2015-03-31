<h3>Bienvenido, tu registro ha sido completado satisfactoriamente.</h3>

<p>Un correo electr&oacute;nico fu&eacute; enviado a su cuenta con los pasos para proceder a la activaci&oacute;n..!</p>

<a href='<?php echo BASE_URL; ?>'>Ir al inicio</a>

<?php if(!Session::get('autenticado')): ?>

   | <a href="<?php echo BASE_URL; ?>login">Iniciar Sesi&oacute;n</a>
<?php endif;?>