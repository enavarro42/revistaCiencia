
<!-- Modal -->
<div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Detalles de la Evaluaci&oacute;n</h4>
      </div>
      <div class="modal-body" id="contentResult">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalRespuesta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Respuesta del Autor</h4>
      </div>
      <div class="modal-body" id="contentenidoRespuesta">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modalEnviar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Desici&oacute;n de la Evaluación</h4>
      </div>
      <div class="modal-body" id="contentResult">
        <label for="desicion">Desici&oacute;n</label>
        
        <select name="desicion" id="desicion" class="form-control">
            <option value="">Publicable con Ligeras Modificaciones</option>
            <option value="">Publicable con Modificaciones Sustanciales</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="aceptar">Aceptar</button>
      </div>
    </div>
  </div>
</div>



<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">

        	<div class="page-header">
	        	<h2>
	        		Evaluaciones del &Aacute;rbitro
	        	</h2>
        	</div>

          <?php if(isset($this->detalleEvaluacion) && $this->detalleEvaluacion){ ?>
        	
        	<button type="button" class="btn btn-success pull-right btn-margin" id="enviarEvaluacion">Enviar Evaluaci&oacute;n</button>

            <input type="hidden" name="id_manuscrito" id="id_manuscrito" value="<?php echo $this->id_manuscrito; ?>">

        	<table class="table table-bordered">
        		<thead>
        			<tr class="active">
        				<th><input type="checkbox" name="checkAll" value="checkAll"></th>
        				<th>&Aacute;rbitro</th>
        				<th>Nro Evaluaci&oacute;n</th>
                <th>Estatus</th>
                <th>Fecha</th>
                <th>Ver Evaluaci&oacute;n</th>
                <th>Respuesta del Autor</th>
        			</tr>
        		</thead>
        		<tbody>
                    <?php for($i = 0; $i<count($this->detalleEvaluacion); $i++){ ?>
            			<tr>
                        <!-- verificar si tiene revision asiganada, si true entonces, no mostrar, si es false entonces mostrar -->
                          <?php if($this->detalleEvaluacion[$i]['id_revision'] == '' || $this->detalleEvaluacion[$i]['id_revision'] == null){ ?>
                            <td><input type="checkbox" name="id" value="<?php echo $this->detalleEvaluacion[$i]['id_evaluacion']; ?>"></td>
                            <?php }else{ ?>
                            <td></td>
                            <?php } ?>
                            <td><?php echo $this->detalleEvaluacion[$i]['nombrecompleto']; ?></td>
                            <td><?php echo $this->detalleEvaluacion[$i]['evaluacion']; ?></td>
                            <td><?php if($this->detalleEvaluacion[$i]['id_revision'] == null || $this->detalleEvaluacion[$i]['id_revision'] == '') echo 'Pendiente'; else echo 'Evaluaci&oacute;n enviada'; ?></td>
                            <td><?php echo $this->detalleEvaluacion[$i]['fecha']; ?></td>
                            <td><button type="button" class="btn btn-primary btn_detalles" id="<?php echo $this->detalleEvaluacion[$i]['id_evaluacion']; ?>">Ver Detalles</button></td>
                            <td> <button type="button" class="btn btn-info btn_ver_resp" clave="<?php echo $this->detalleEvaluacion[$i]['id_evaluacion']; ?>">Respuesta del Autor</button> </td>
                        </tr>
                    <?php } ?>
        		</tbody>
        	</table>
          <?php }else{ ?>
            <h3>
              No se encontraron resultados.
            </h3>
          <?php } ?>
        </div>
    </div>
</div>