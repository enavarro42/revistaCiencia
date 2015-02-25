<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            	<h2 clasbtns="page-header">Grupo de Usuario</h2>
        	</div>

        	<div class="caja_acciones col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?php if($this->eliminar){ ?>
        		<button class="btn btn-danger btn_accion" id="eliminar">Eliminar</button>
                <?php } ?>
        		<a href="<?php echo BASE_URL; ?>acl/crear" class="btn btn-success btn_accion">Insertar</a>
        	</div>

        	<div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div>

            <hr>

        	<div class="table-responsive">
        		<table class="table table-hover table-bordered">

        			<thead>
        				<tr>
        					<th class="col_check"><input type="checkbox" id="checkall" value="all"></th>
        					<th>Rol</th>
        					<th class="col_accion">Acci&oacute;n</th>
        				</tr>
        			</thead>
        			<tbody>
        				<?php for($i = 0; $i<count($this->roles); $i++){ ?>
        				<tr>
        					<td><input type="checkbox" name="id" class="case" value="<?php echo $this->roles[$i]['id_rol']; ?>"></td>
        					<td><?php echo $this->roles[$i]['rol']; ?></td>
        					<td><a href="<?php echo BASE_URL ?>acl/editar/<?php echo $this->roles[$i]['id_rol']; ?>" class="btn btn-info">Editar</a></td>
        				</tr>
        				<?php } ?>
        			</tbody>
			
				</table>
			</div>
            
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->