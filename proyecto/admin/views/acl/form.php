<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        	<form role="form" name='form_group' method='post' action=''>
	            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
	            	<h2 clasbtns="page-header">Crear Rol</h2>
	        	</div>

	        	<div class="caja_acciones col-xs-6 col-sm-6 col-md-6 col-lg-6">
	        		<a href="<?php echo BASE_URL; ?>acl" class="btn btn-danger btn_accion">Cancelar</a>
	        		<button type="submit" class="btn btn-success btn_accion">Guardar</button>
	        	</div>

	        	<div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div>

	        	<hr>


            	<input type="hidden" name="enviar" value="1" />

			    <div class="form-group col-lg-4">
			    	<label for="rol">Rol</label>
			    	<input type="text" class="form-control" name="rol" placeholder="Escriba el Rol" value="<?php echo $this->data['value_rol']; ?>"/>
			    </div>

			    <div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div>

			  	<div class="form-group col-lg-12">
			  		<label for="exampleInputEmail1">Permisos</label>
					<ul class="list-group caja_scroll">

					<?php for($i = 0; $i < count($this->permisos); $i++){ ?>

					  <li class="list-group-item">
					  	<?php if(isset($this->permisos_seleccionados) && in_array($this->permisos[$i]['id_permiso'], $this->permisos_seleccionados)){ ?>
					  		<input type="checkbox" name="check_permiso[]" value="<?php echo $this->permisos[$i]['id_permiso']; ?>" checked> 
					  	<?php }else{ ?>
					  		<input type="checkbox" name="check_permiso[]" value="<?php echo $this->permisos[$i]['id_permiso']; ?>">
					  	<?php } ?>

					  	<span class="sp_left"><?php echo $this->permisos[$i]['permiso']; ?></span>
					  </li>

					<?php } ?>

					</ul>
			  	</div>

			</form>

        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->