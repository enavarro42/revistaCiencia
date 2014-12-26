<h3 class="page-header"><span class="glyphicon glyphicon-user"></span> <?php echo $this->titulo; ?></h3>
<form id="registro" method='post' action='' class="">

    
    <input type="hidden" value="1" name="enviar" />

    <div class="alert alert-<?php if(isset($this->tipoMensaje)) echo $this->tipoMensaje; ?> <?php echo $this->display_msj; ?>" role="alert"><?php if(isset($this->mensaje_actualizacion)) echo $this->mensaje_actualizacion; ?></div>

    <div class="col-lg-8">

    <h3>Datos personales</h3>
      
      <div class="form-group control_input">
        <label for="usuario">Usuario</label>
        <input type="text" name="usuario" id="usuario" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['usuario']; ?>" readonly />
        <label class="error" id="error_usuario"><?php if(isset($this->_error_usuario)) echo $this->_error_usuario; ?></label>
      </div>

    <div>
      
      <div class="form-group">
        <label for="primerNombre">Primer nombre *</label>
        <input type="text" name="primerNombre" id="primerNombre" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['primerNombre']; ?>" />
        <label class="error" id="error_primerNombre"><?php if(isset($this->_error_primerNombre)) echo $this->_error_primerNombre; ?></label>
      </div>

      <div class="form-group">
        <label for="segundoNombre">Segundo nombre *</label>
        <input type="text" name="segundoNombre" id="segundoNombre" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['segundoNombre']; ?>" />
        <label class="error" id="error_segundoNombre"><?php if(isset($this->_error_segundoNombre)) echo $this->_error_segundoNombre; ?></label>
      </div>


      <div class="form-group">
        <label for="apellido">Apellidos *</label>
        <input type="text" name="apellido" id="apellido" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['apellido']; ?>" />
        <label class="error" id="error_apellido"><?php if(isset($this->_error_apellido)) echo $this->_error_apellido; ?></label>
      </div>

    </div>

    <div class="form-group ">
      <label for="din">DIN</label>
      <input type="text" name="din" id="din" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['din']; ?>" />
      <label class="error" id="error_din"><?php if(isset($this->_error_din)) echo $this->_error_din; ?></label>
    </div>

    <div class="form-group ">
      <label for="email">Correo *</label>
      <input type="email" name="email" id="email" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['email']; ?>" readonly />
      <label class="error" id="error_email"><?php if(isset($this->_error_email)) echo $this->_error_email; ?></label>
    </div>

 	<div class="clearfix visible-lg-block"></div>


      <div class="form-group">
          <label>G&eacute;nero</label>
          <div class="radio">
            <?php if(isset($this->datos['genero']) && $this->datos['genero'] == 'M'){ ?>
              <label>
                <input type="radio" name="genero" id="opcionRadio1" value="M" checked>
                Masculino
              </label>
            <?php }else{ ?>
            <label>
                <input type="radio" name="genero" id="opcionRadio1" value="M">
                Masculino
              </label>
            <?php } ?>
          </div>
          <div class="radio">
            <?php if(isset($this->datos['genero']) && $this->datos['genero'] == 'F'){ ?>
              <label>
                <input type="radio" name="genero" id="opcionRadio2" value="F" checked>
                Femenino
              </label>
            <?php }else{ ?>

            <label>
                <input type="radio" name="genero" id="opcionRadio2" value="F">
                Femenino
              </label>

            <?php } ?>
          </div>
         <label class="error"><?php if(isset($this->_error_genero)) echo $this->_error_genero; ?></label>
      </div>
      

      <div class="clearfix visible-lg-block"></div>


    <div class="form-group">
      <label for="telefono">Tel&eacute;fono</label>
      <input type="text" name="telefono" id="telefono" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['telefono']; ?>" />
      <label class="error" id="error_telefono"><?php if(isset($this->_error_telefono)) echo $this->_error_telefono; ?></label>
    </div>


    <div class="form-group ">
      <label for="pais">Pa&iacute;s *</label>
      <select id="pais" name="pais" class="form-control required">
           <option value="">-seleccione-</option>
           <?php for($i = 0; $i<count($this->paises); $i++): ?>
                <?php if($this->paises[$i]['id_pais'] == $this->datos['pais']): ?>
                    <option value="<?php echo $this->paises[$i]['id_pais']?>" selected> <?php echo $this->paises[$i]['nombre'];?> </option>
                <?php else: ?>
                    <option value="<?php echo $this->paises[$i]['id_pais']?>"> <?php echo $this->paises[$i]['nombre'];?> </option>
                <?php endif; ?>
           <?php endfor;?>
      </select>
      <label class="error" id="error_pais"><?php if(isset($this->_error_pais)) echo $this->_error_pais; ?></label>
    </div>


    <div class="form-group">
        <label for="filiacion">Filiaci&oacute;n</label>
        <textarea id="filiacion" name="filiacion" class="form-control" rows="2"><?php if(isset($this->datos)) echo $this->datos['filiacion']; ?></textarea>
        <label class="text_ejemplo">(Su instituci&oacute;n, por ejemplo: Universidad del Zulia)</label>
    </div>
    
    <div class="form-group ">
        <label for="resumenBiografico">Resumen Biogr&aacute;fico</label>
        <textarea id="resumenBiografico" name="resumenBiografico" class="form-control" rows="5"><?php if(isset($this->datos)) echo $this->datos['resumenBiografico']; ?></textarea>
        <label class="text_ejemplo">(Nivel acad&eacute;mico y cargo laboral)</label>
    </div>

	<h3>Cambiar la contraseña</h3>

	  <div class="form-group">
        <label for="pass_actual">Contrase&ntilde;a  Actual</label>
        <input type="password" name="pass_actual" id="pass_actual" class="form-control" />
        <label class="error" id="error_pass"><?php if(isset($this->_error_pass)) echo $this->_error_pass; ?></label>
      </div>

       <div class="form-group">
        <label for="pass">Password</label>
        <input type="password" name="pass" id="pass" class="form-control" />
        <label class="error" id="error_pass"><?php if(isset($this->_error_pass)) echo $this->_error_pass; ?></label>
      </div>


      <div class="form-group">
        <label for="confirmar">Confirmar</label>
        <input type="password" name="confirmar" id="confirmar" class="form-control" />
        <label class="error" id="error_confir"><?php if(isset($this->_error_pass_confir)) echo $this->_error_pass_confir; ?></label>
      </div>

	    <div class="form-group">
	    	<button class="btn btn-primary" type="submit">Actualizar perfil</button>
	    </div>

    </div>

        
</form>