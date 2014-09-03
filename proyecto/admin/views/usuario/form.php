<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">

        	<form role="form" id="form_usuario" action="" method="POST">

        		<input type="hidden" name="tipoAccion" id="tipoAccion" value="<?php echo $this->tipoAccion; ?>">

            	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
	            	<h2 clasbtns="page-header"><?php if(isset($this->titulo)) echo $this->titulo; ?></h2>
	        	</div>

	        	<div class="caja_acciones col-xs-6 col-sm-6 col-md-6 col-lg-6">
	        		<a href="<?php echo BASE_URL; ?>usuario" class="btn btn-danger btn_accion">Cancelar</a>
	        		<button type="submit" class="btn btn-success btn_accion">Guardar</button>
	        	</div>

	        	<div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div>

	        	<hr>

	        	<input type="hidden" name="enviar" value="1">

	        	<div class="form-group col-lg-4">
			  		<label for="exampleInputEmail1">Roles</label>
					<ul class="list-group caja_scroll">

					<?php for($i = 0; $i < count($this->roles); $i++){ ?>

					  <li class="list-group-item">
					  	<?php if(isset($this->roles_seleccionados) && in_array($this->roles[$i]['id_rol'], $this->roles_seleccionados)){ ?>
					  		<input type="checkbox" name="check_rol[]" value="<?php echo $this->roles[$i]['id_rol']; ?>" checked> 
					  	<?php }else{ ?>
					  		<input type="checkbox" name="check_rol[]" value="<?php echo $this->roles[$i]['id_rol']; ?>">
					  	<?php } ?>

					  	<span class="sp_left"><?php echo $this->roles[$i]['rol']; ?></span>
					  </li>

					<?php } ?>

					</ul>
					<label class="error"><?php if(isset($this->_error_rol)) echo $this->_error_rol; ?></label>
			  	</div>

			  	<div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div>
			  	<hr>

				<div class="col-md-6 col-lg-6">
					<?php if(isset($this->tipoAccion) && $this->tipoAccion == 'editar'){ ?>
					<input type="hidden" name="id_persona" id="id_persona" value="<?php echo $this->id_persona; ?>">
					<?php } ?>
				      
			      <div class="form-group control_input">
			        <label for="usuario">Usuario *</label>
			        <input type="text" name="usuario" id="usuario" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['usuario']; ?>" />
			        <label class="error" id="error_usuario"><?php if(isset($this->_error_usuario)) echo $this->_error_usuario; ?></label>
			      </div>

			      <div id="cajaPass" style="display:<?php echo $this->cajaPass; ?>">

			      	<input type="hidden" name="nuevaPass" id="nuevaPass" value="<?php if(isset($this->nuevaPass)) echo $this->nuevaPass; else echo 0; ?>">
			      
				      <div class="form-group">
				        <label for="pass">Contrase&ntilde;a *</label>
				        <input type="password" name="pass" id="pass" class="form-control" value="" />
				        <label class="error" id="error_pass"><?php if(isset($this->_error_pass)) echo $this->_error_pass; ?></label>
				      </div>


				      <div class="form-group">
				        <label for="confirmar">Confirmar *</label>
				        <input type="password" name="confirmar" id="confirmar" value=""  class="form-control" />
				        <label class="error" id="error_confir"><?php if(isset($this->_error_pass_confir)) echo $this->_error_pass_confir; ?></label>
				      </div>
				   </div>

				   <div class="form-group" style="display:<?php echo $this->linkCambiarPass; ?>">
				   	<?php if(isset($this->nuevaPass) && $this->nuevaPass){ ?>
			      		<a id="cambiarPass" class="link">Cancelar Cambiar Contrase&ntilde;a</a>
			      	<?php }else{ ?>
			      		<a id="cambiarPass" class="link">Cambiar Contrase&ntilde;a</a>
			      	<?php } ?>
			  		</div>
			    </div>

			    <div class="col-md-6 col-lg-6">
			      
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

			    <div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div>

			    <div class="form-group col-md-6 col-lg-6">
			      <label for="din">DIN *</label>
			      <input type="text" name="din" id="din" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['din']; ?>" />
			      <label class="error" id="error_din"><?php if(isset($this->_error_din)) echo $this->_error_din; ?></label>
			    </div>

 			    <div class="form-group col-md-6 col-lg-6">
			      <label for="email">Correo *</label>
			      <input type="text" name="email" id="email" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['email']; ?>" />
			      <label class="error" id="error_email"><?php if(isset($this->_error_email)) echo $this->_error_email; ?></label>
			    </div>



			 <div class="clearfix visible-lg-block"></div>


			      <div class="form-group col-md-6 col-lg-6">
			          <label>G&eacute;nero *</label>
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
			      </div>
			      

			      <div class="clearfix visible-lg-block"></div>


			    <div class="form-group col-md-6 col-lg-6">
			      <label for="telefono">Tel&eacute;fono *</label>
			      <input type="text" name="telefono" id="telefono" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['telefono']; ?>" />
			      <label class="error" id="error_telefono"><?php if(isset($this->_error_telefono)) echo $this->_error_telefono; ?></label>
			    </div>


			    <div class="form-group col-md-6 col-lg-6">
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

			    <div class="form-group col-lg-12">
			        <label for="filiacion">Filiaci&oacute;n</label>
			        <textarea id="filiacion" name="filiacion" class="form-control" rows="2"><?php if(isset($this->datos)) echo $this->datos['filiacion']; ?></textarea>
			        <label class="text_ejemplo">(Su instituci&oacute;n, por ejemplo: Universidad del Zulia)</label>
			    </div>
			    
			    <div class="form-group col-lg-12">
			        <label for="resumenBiografico">Resumen Biogr&aacute;fico</label>
			        <textarea id="resumenBiografico" name="resumenBiografico" class="form-control" rows="5"><?php if(isset($this->datos)) echo $this->datos['resumenBiografico']; ?></textarea>
			        <label class="text_ejemplo">(Nivel acad&eacute;mico y cargo laboral)</label>
			    </div>

	        </form>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->