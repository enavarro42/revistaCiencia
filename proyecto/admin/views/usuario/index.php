<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            
	            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
	            	<h2 clasbtns="page-header">Usuarios</h2>
	        	</div>

	        	<div class="caja_acciones col-xs-6 col-sm-6 col-md-6 col-lg-6">
	        		<?php if($this->eliminar){ ?>
	        			<a href="" id="eliminar" class="btn btn-danger btn_accion">Eliminar</a>
	        		<?php } ?>
	        		<a href="<?php echo BASE_URL; ?>usuario/insertar" class="btn btn-success btn_accion">Insertar</a>
	        	</div>

	        	<div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div>

	        	<hr>

	        	<form role="form" class="pd col-lg-12" id="form_user" name='form_user' method='post' action='<?php echo BASE_URL ?>usuario'>

	        		<input type="hidden" name="enviar" value="1">

	        		<div class="form-group col-md-3 col-lg-3">
					    <label for="tipoBusqueda">Buscar Por</label>
					    <select name="tipoBusqueda" class="form-control">

					    	<?php if(isset($this->tipoBusqueda_selected) && $this->tipoBusqueda_selected == 'nombre'){ ?>
					    		<option value="nombre" selected>Nombre</option>
					    	<?php }else{ ?>
					    		<option value="nombre">Nombre</option>
					    	<?php } ?>

					    	<?php if(isset($this->tipoBusqueda_selected) && $this->tipoBusqueda_selected == 'apellido'){ ?>
					    		<option value="apellido" selected>Apellido</option>
					    	<?php }else{ ?>
					    		<option value="apellido">Apellido</option>
					    	<?php } ?>
					    	
					    </select>
					 </div>
					 <div class="form-group col-md-3 col-lg-3">
					 	<label>Busqueda</label>
					 	<input type="text" name="busqueda" value="<?php echo $this->busqueda; ?>" class="form-control" placeholder="Buscar por...">
					 </div>

					 <!-- <div class="form-group div_vertical"></div> -->

					 <div class="form-group col-md-3 col-lg-3">
					    <label for="tipoUsuario">Tipo de Usuario</label>
					    <select name="tipoUsuario" class="form-control">
					    	<?php if(isset($this->tipoUsuario_selected) && $this->tipoUsuario_selected == '0'){ ?>
					    		<option value="0" selected>Todos</option>
					    	<?php }else{ ?>
					    		<option value="0">Todos</option>
					    	<?php } ?>
					    	<?php for($i = 0; $i<count($this->roles); $i++){ ?>
					    		<?php if(isset($this->tipoUsuario_selected) && $this->tipoUsuario_selected == $this->roles[$i]['id_rol']){ ?>
					    			<option value="<?php echo $this->roles[$i]['id_rol']; ?>" selected><?php echo $this->roles[$i]['rol']; ?></option>
					    		<?php }else{ ?>
					    			<option value="<?php echo $this->roles[$i]['id_rol']; ?>"><?php echo $this->roles[$i]['rol']; ?></option>
					    		<?php } ?>
					    	<?php } ?>
					    </select>
					    <label class="error" for="tipoBusqueda"></label>
					 </div>

					 <div class="form-group col-md-3 col-lg-3">
					 	<label for="area">&Aacute;rea</label>
					    <select name="area" class="form-control">
					    	<?php if(isset($this->area_selected) && $this->area_selected == '0'){ ?>
					    		<option value="0" selected>Todos</option>
					    	<?php }else{ ?>
					    		<option value="0">Todos</option>
					    	<?php } ?>
					    	<?php for($i = 0; $i<count($this->areas); $i++){ ?>
					    		<?php if(isset($this->area_selected) && $this->area_selected == $this->areas[$i]['id_area']){ ?>
					    			<option value="<?php echo $this->areas[$i]['id_area']; ?>" selected><?php echo $this->areas[$i]['nombre']; ?></option>
					    		<?php }else{ ?>
					    			<option value="<?php echo $this->areas[$i]['id_area']; ?>"><?php echo $this->areas[$i]['nombre']; ?></option>
					    		<?php } ?>
					    	<?php } ?>
					    	
					    </select>
					    <label class="error" for="error_area"></label>
					 </div>

					 <div class="form-group col-md-3 col-lg-3">
					 	<button type="submit" class="btn btn-primary btn-filtro"><i class="fa fa-search"></i> Buscar</button>
					 </div>
	        	</form>


	        	<div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div>

		       	<div class="table-responsive">
		       		<?php if($this->resultado){ ?>
	        		<table class="table table-hover table-bordered">

	        			<thead>
	        				<tr>
	        					<th class="col_check"><input type="checkbox" id="checkall" value="all"></th>
	        					<th>Nombre y Apellido</th>
	        					<th>Correo</th>
	        					<th class="col_accion">Acci&oacute;n</th>
	        				</tr>
	        			</thead>
	        			<tbody>
	        				
		        				<?php for($i = 0; $i<count($this->resultado); $i++){ ?>
		        				<tr>
		        					<td><input type="checkbox" name="id" class="case"  value="<?php echo $this->resultado[$i]['id_persona']; ?>"></td>
		        					<td><?php echo $this->resultado[$i]['nombrecompleto']; ?></td>
		        					<td><?php echo $this->resultado[$i]['email']; ?></td>
		        					<td><a href="<?php echo BASE_URL . "usuario/editar/" . $this->resultado[$i]['id_persona']; ?>" class="btn btn-info">Editar</a></td>
		        				</tr>
		        				<?php } ?>
		        				
	        			</tbody>
				
					</table>
					<?php }else{  ?>
        				<div class="alert alert-info" role="alert">
        					No se encontraron resultados...
        				</div>
		        	<?php } ?>
				</div>

				<ul class="pagination">
                	<?php if(isset($this->resultado) && isset($this->paginacion)) echo $this->paginacion;?>
            	</ul>
            
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->