    <div>
            <h2 class="text-center login-title">Generar una nueva contrase&ntilde;a</h2>
            <p>Introduzca el correo con el que se registr&oacute; y en breves momentos recibir&aacute; una nueva contrase&ntilde;a</p>

            <form action="" method="post" accept-charset="utf-8" class="form-inline">
            	<span class="error"><?php if(isset($this->_error_email)) echo $this->_error_email; ?></span>
            	  <input type="hidden" id="enviado" name="enviado" value="1" />
            	  <div class="form-group">
				    <input type="text" class="form-control" id="correo" name="correo" placeholder="Correo electr&oacute;nico">
				  </div>
				  <button type="submit" class="btn btn-primary" id="enviar">Enviar</button>
            </form>
    </div>