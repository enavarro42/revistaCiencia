

<h3 class="text-center">Crear una cuenta</h3>

<div class="col-lg-3"></div>
<div class="account-wall col-lg-6">
    <img class="profile-img" src="<?php echo BASE_URL;?>image/register.png"
        alt="">



<form id="registro" method='post' action='' class="">

    
    <input type="hidden" value="1" name="enviar" />
    
    <div class="">
      
      <div class="form-group control_input">
        <label for="usuario">Usuario *</label>
        <input type="text" name="usuario" id="usuario" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['usuario']; ?>" />
        <label class="error" id="error_usuario"><?php if(isset($this->_error_usuario)) echo $this->_error_usuario; ?></label>
      </div>
      

      <div class="form-group">
        <label for="pass">Password *</label>
        <input type="password" name="pass" id="pass" class="form-control" />
        <label class="error" id="error_pass"><?php if(isset($this->_error_pass)) echo $this->_error_pass; ?></label>
      </div>


      <div class="form-group">
        <label for="confirmar">Confirmar *</label>
        <input type="password" name="confirmar" id="confirmar" class="form-control" />
        <label class="error" id="error_confir"><?php if(isset($this->_error_pass_confir)) echo $this->_error_pass_confir; ?></label>
      </div>
    </div>

    <div class="">
      
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
      <label for="din">DIN *</label>
      <input type="text" name="din" id="din" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['din']; ?>" />
      <label class="error" id="error_din"><?php if(isset($this->_error_din)) echo $this->_error_din; ?></label>
    </div>

    <div class="form-group ">
      <label for="email">Correo *</label>
      <input type="email" name="email" id="email" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['email']; ?>" />
      <label class="error" id="error_email"><?php if(isset($this->_error_email)) echo $this->_error_email; ?></label>
    </div>

 <div class="clearfix visible-lg-block"></div>


      <div class="form-group ">
          <label>G&eacute;nero *</label>
          <div class="radio">
              <label>
                <input type="radio" name="genero" id="opcionRadio1" value="M" checked>
                Masculino
              </label>
          </div>
          <div class="radio">
              <label>
                <input type="radio" name="genero" id="opcionRadio2" value="F">
                Femenino
              </label>
          </div>
      </div>
      

      <div class="clearfix visible-lg-block"></div>


    <div class="form-group">
      <label for="telefono">Tel&eacute;fono *</label>
      <input type="text" name="telefono" id="telefono" class="form-control" value="" />
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
      <label for="area">&Aacute;reas</label>
      <ul class="list-group caja_scroll">

        <?php for($i = 0; $i < count($this->areas); $i++){ ?>

          <li class="list-group-item">
            <?php if(isset($this->areas_seleccionados) && in_array($this->areas[$i]['id_area'], $this->areas_seleccionados)){ ?>
              <input type="checkbox" name="check_areas[]" value="<?php echo $this->areas[$i]['id_area']; ?>" checked> 
            <?php }else{ ?>
              <input type="checkbox" name="check_areas[]" value="<?php echo $this->areas[$i]['id_area']; ?>">
            <?php } ?>

            <span class="sp_left"><?php echo $this->areas[$i]['nombre']; ?></span>
          </li>

        <?php } ?>

        </ul>
        <label class="error"><?php if(isset($this->_error_area)) echo $this->_error_area; ?></label>
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

    
    <div class="form-group">
        <label>Registrarse como *</label>

<!--         <div class="checkbox">
            <label>
              <input type="checkbox" name="cuenta[]" value="<?php echo $this->cuentas[0]; ?>">&Aacute;rbitro
            </label>
        </div> -->

        <div class="checkbox">
            <label>
              <input type="checkbox" name="cuenta[]" value="<?php echo $this->cuentas[0]; ?>">Autor
            </label>
        </div>
        
        <label class="error" id="error_cuenta"><?php if(isset($this->_error_cuenta)) echo $this->_error_cuenta; ?></label>

        <div class="caja_btn_submit">
            <button class="btn btn-primary btn-block" type="submit" id="enviar">Crear cuenta</button>
        </div>
        
        
    </div>
</form>
</div>