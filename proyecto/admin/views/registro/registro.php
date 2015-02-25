<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php if(isset($this->titulo)) echo $this->titulo; ?></title>
<!--CSS-->
    <link href='<?php echo $_layoutParams['ruta_css'];?>bootstrap.min.css' rel='stylesheet' type='text/css'>
    <link href="<?php echo $_layoutParams['ruta_css'];?>font-awesome.min.css" rel="stylesheet"  type="text/css" />
    <link href="<?php echo $_layoutParams['ruta_css'];?>estilo-admin.css" rel="stylesheet"  type="text/css" />


    <?php if(isset($_layoutParams['cssPublic']) && count($_layoutParams['cssPublic'])): ?>
    <?php for($i=0; $i < count($_layoutParams['cssPublic']); $i++): ?>
    
    <link href="<?php echo $_layoutParams['cssPublic'][$i] ?>" rel="stylesheet"  type="text/css" />
    
    <?php endfor; ?>
    <?php endif; ?>

    <script src="<?php echo BASE_URL;?>public/js/jquery-1.10.2.min.js"></script>
    <script src="<?php echo BASE_URL;?>public/js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL;?>public/js/jquery.metisMenu.js"></script>
    <script src="<?php echo BASE_URL;?>public/js/sb-admin.js"></script>
    
<!--JS publicos -->
    
    <?php if(isset($_layoutParams['jsPublic']) && count($_layoutParams['jsPublic'])): ?>
    <?php for($i=0; $i < count($_layoutParams['jsPublic']); $i++): ?>
    
    <script src="<?php echo $_layoutParams['jsPublic'][$i] ?>" type="text/javascript"></script>
    
    <?php endfor; ?>
    <?php endif; ?>
    
    
    <!--JS privados de cada vista-->
    <?php if(isset($_layoutParams['js']) && count($_layoutParams['js'])): ?>
    <?php for($i=0; $i < count($_layoutParams['js']); $i++): ?>
    
    <script src="<?php echo $_layoutParams['js'][$i] ?>" type="text/javascript"></script>
    
    <?php endfor; ?>
    <?php endif; ?>

</head>

<body>

    <div class="">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="background-color: #488AC4; margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <img src="<?php echo $_layoutParams['ruta_img']; ?>logo_min.png" alt="" style="float: left; margin: 2px 0px 0px 5px;">
                <a class="navbar-brand" href="index.html">Revistas Arbitradas</a>


            </div>
        </nav>


		    <div class="row">
		    	<div class="col-lg-3"></div>
		        <div class="col-lg-6">

		        	<p></p>

		        	<img class="img-user-login center-block" src="<?php echo $_layoutParams['ruta_img']; ?>signup.png" alt="">

		        	<form role="form" id="form_usuario" class="panel panel-default" action="" method="POST">



		        		<input type="hidden" name="tipoAccion" id="tipoAccion" value="<?php echo $this->tipoAccion; ?>">

		            	<div class="panel-heading">
			            	<h3 clasbtns="page-header"><i class="fa fa-user-plus"></i> <?php if(isset($this->titulo)) echo $this->titulo; ?></h3>
			        	</div>

			        	<!--<div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div>-->

			        	<div class="panel-body">


				        	<input type="hidden" name="enviar" value="1">

				        	<div class="form-group">
						  		<label for="exampleInputEmail1">Rol *</label>
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

						  	<!-- <div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div> -->
						  	<hr>

							<div>
								<?php if(isset($this->tipoAccion) && $this->tipoAccion == 'editar'){ ?>
								<input type="hidden" name="id_persona" id="id_persona" value="<?php echo $this->id_persona; ?>">
								<?php } ?>
							      
						      <div class="form-group control_input">
						        <label for="usuario">Usuario *</label>
						        <input type="text" name="usuario" id="usuario" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['usuario']; ?>" />
						        <label class="error" id="error_usuario"><?php if(isset($this->_error_usuario)) echo $this->_error_usuario; ?></label>
						      </div>



						      <div id="cajaPass" style="display:<?php echo $this->cajaPass; ?>">

						      	<div class="radio">
								  <label>
								    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
								    <strong>Contrase&ntilde;a aleatoria</strong>
								  </label>
								</div>

								<div class="radio">
								  <label>
								    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
								    <strong>Asignar contrase&ntilde;a</strong>
								  </label>
								</div>
						      	

								<br>


						      	<input type="hidden" name="nuevaPass" id="nuevaPass" value="<?php if(isset($this->nuevaPass)) echo $this->nuevaPass; else echo 0; ?>">
						      		
							      <div class="form-group my_input">
							        <label for="pass">Contrase&ntilde;a *</label>
							        <input type="password" name="pass" id="pass" class="form-control" value="" />
							        <label class="error" id="error_pass"><?php if(isset($this->_error_pass)) echo $this->_error_pass; ?></label>
							      </div>


							      <div class="form-group my_input">
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

						    <div>
						      
						      <div class="form-group">
						        <label for="primerNombre">Primer nombre *</label>
						        <input type="text" name="primerNombre" id="primerNombre" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['primerNombre']; ?>" />
						        <label class="error" id="error_primerNombre"><?php if(isset($this->_error_primerNombre)) echo $this->_error_primerNombre; ?></label>
						      </div>

						      <div class="form-group">
						        <label for="segundoNombre">Segundo nombre</label>
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

						    <div class="form-group">
						      <label for="din">DIN</label>
						      <input type="text" name="din" id="din" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['din']; ?>" />
						      <label class="error" id="error_din"><?php if(isset($this->_error_din)) echo $this->_error_din; ?></label>
						    </div>

			 			    <div class="form-group">
						      <label for="email">Correo *</label>
						      <input type="text" name="email" id="email" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['email']; ?>" />
						      <label class="error" id="error_email"><?php if(isset($this->_error_email)) echo $this->_error_email; ?></label>
						    </div>



						 <div class="clearfix visible-lg-block"></div>


						      <div class="form-group">
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
						         <label class="error"><?php if(isset($this->_error_genero)) echo $this->_error_genero; ?></label>
						      </div>
						      

						      <div class="clearfix visible-lg-block"></div>


						    <div class="form-group">
						      <label for="telefono">Tel&eacute;fono</label>
						      <input type="text" name="telefono" id="telefono" class="form-control" value="<?php if(isset($this->datos)) echo $this->datos['telefono']; ?>" />
						      <label class="error" id="error_telefono"><?php if(isset($this->_error_telefono)) echo $this->_error_telefono; ?></label>
						    </div>


						    <div class="form-group">
						      <label for="pais">Pa&iacute;s</label>
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
						  		<label for="areas">Areas *</label>
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
						    
						    <div class="form-group">
						        <label for="resumenBiografico">Resumen Biogr&aacute;fico</label>
						        <textarea id="resumenBiografico" name="resumenBiografico" class="form-control" rows="5"><?php if(isset($this->datos)) echo $this->datos['resumenBiografico']; ?></textarea>
						        <label class="text_ejemplo">(Nivel acad&eacute;mico y cargo laboral)</label>
						    </div>


				        	<div class="caja_acciones">
				        		<a href="<?php echo BASE_URL; ?>usuario" class="btn btn-danger btn_accion">Cancelar</a>
				        		<button type="submit" class="btn btn-success btn_accion">Aceptar</button>
				        	</div>

			        	</div>


			        </form>
		        </div>
		        <!-- /.col-lg-12 -->
		    </div>
		    <!-- /.row -->

</div>

</body>

</html>