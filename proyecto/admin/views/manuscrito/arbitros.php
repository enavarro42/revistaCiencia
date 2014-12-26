<div class="modal fade" id="modalArbitroAgregado">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">&Aacute;rbitro asignado</h4>
      </div>
      <div class="modal-body">
        <p>El &aacute;rbitro fu&eacute; asignado corr&eacute;ctamente.</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="aceptarAgregar" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        	<h2 class="page-header">&Aacute;rbitros</h2>

        	<h3 class="text-center">&Aacute;rbitros Postulados <img id="img_arbitros_postulados" src="<?php echo ROOT_IMAGE . "load.gif"; ?>" alt=""></h3>
            <input type="hidden" name="id_manuscrito" id="id_manuscrito" value="<?php echo $this->id_manuscrito; ?>">
        	<table class="table table-bordered">
        		<thead>
        			<tr class="active">
        				<th>&Aacute;rbitro</th>
        				<th>Filiaci&oacute;n</th>
        				<th>Descripci&oacute;n</th>
        				<th>Acci&oacute;n</th>
        				<th>Estatus</th>
        				<th>Enviar Solicitud</th>
        			</tr>
        		</thead>
        		<tbody>
                    <?php for($i = 0; $i < count($this->arbitrosPostulados); $i++){ ?>
            			<tr>
                            <td><?php echo $this->arbitrosPostulados[$i]['nombrecompleto']; ?></td>
                            <td><?php echo $this->arbitrosPostulados[$i]['filiacion']; ?></td>
                            <td><?php echo $this->arbitrosPostulados[$i]['resumenBiografico']; ?></td>
            				<td><a class="quitar btn btn-danger" id="<?php echo $this->arbitrosPostulados[$i]['id_persona']; ?>">Quitar</a> <?php if($this->arbitrosPostulados[$i]['estatus'] == 1) echo '<a class="asignar btn btn-success" id="'. $this->arbitrosPostulados[$i]['id_persona'] .'">Asignar</a>'; ?></td>
            				<td class="text-center"><?php if($this->arbitrosPostulados[$i]['estatus'] == NULL) echo '<i class="color_red fa fa-circle"> Por enviar</i>'; else if($this->arbitrosPostulados[$i]['estatus'] == 0) echo '<i class="color_red fa fa-circle"> No acepta</i>'; else if($this->arbitrosPostulados[$i]['estatus'] == 1) echo '<i class="color_green fa fa-check"> Acepta</i>'; else if($this->arbitrosPostulados[$i]['estatus'] == 2) echo '<i class="color_green fa fa-check"> Asignado</i>'; else if($this->arbitrosPostulados[$i]['estatus'] < 0) echo '<i class="color_red">Enviado</i>'; ?></td>
            				<td><button type="button" class="solicitud btn btn-primary" id="<?php echo $this->arbitrosPostulados[$i]['id_persona']; ?>">Enviar Solicitud</button></td>
            			</tr>
                    <?php } ?>
        		</tbody>
        	</table>


        	<h3 class="text-center">&Aacute;rbitros <img id="img_arbitros" src="<?php echo ROOT_IMAGE . "load.gif"; ?>" alt=""></h3>
        	<table class="table table-bordered">
        		<thead>
        			<tr class="active">
        				<th>&Aacute;rbitro</th>
        				<th>Filiaci&oacute;n</th>
        				<th>Descripci&oacute;n</th>
        				<th>Agregar</th>
        			</tr>
        		</thead>
        		<tbody>
        			<?php for($i = 0; $i < count($this->arbitros); $i++){ ?>
        			<tr>
        				<td><?php echo $this->arbitros[$i]['nombrecompleto']; ?></td>
        				<td><?php echo $this->arbitros[$i]['filiacion']; ?></td>
        				<td><?php echo $this->arbitros[$i]['resumenBiografico']; ?></td>
        				<td><a class="agregar btn btn-info" id="<?php echo $this->arbitros[$i]['id_persona']; ?>">Agregar</a></td>
        			</tr>
        			<?php } ?>
        		</tbody>
        	</table>

            <?php //var_dump($this->arbitrosPostulados); ?>

            <?php //var_dump($this->arbitros); ?>

        	<ul class="pagination">
                <?php if(isset($this->arbitros) && isset($this->paginacion)) echo $this->paginacion;?>
            </ul>

        </div>
    </div>
</div>