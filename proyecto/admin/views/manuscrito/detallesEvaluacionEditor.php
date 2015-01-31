<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">

        	<div class="page-header">
	        	<h2>
	        		Evaluaciones del Editor
	        	</h2>
        	</div>

          <?php if(isset($this->detalleEvaluacion) && $this->detalleEvaluacion){ ?>

            <input type="hidden" name="id_manuscrito" id="id_manuscrito" value="<?php echo $this->id_manuscrito; ?>">

        	<table class="table table-bordered">
        		<thead>
        			<tr class="active">
        				<th>Manuscrito</th>
        				<th>Estatus</th>
                        <th>Observaciones</th>
                        <th>Fecha</th>
        			</tr>
        		</thead>
        		<tbody>
                    <?php for($i = 0; $i<count($this->detalleEvaluacion); $i++){ ?>
            			<tr>
                            <td><?php echo $this->detalleEvaluacion[$i]['titulo']; ?></td>
                            <td><?php echo $this->detalleEvaluacion[$i]['estatus']; ?></td>
                            <td><?php echo $this->detalleEvaluacion[$i]['observaciones']; ?></td>
                            <td><?php echo $this->detalleEvaluacion[$i]['fecha']; ?></td>
                        </tr>
                    <?php } ?>
        		</tbody>
        	</table>

            <ul class="pagination">
                <?php echo $this->paginacion; ?>
            </ul>

          <?php }else{ ?>
            <h3>
              No se encontraron resultados.
            </h3>
          <?php } ?>
        </div>
    </div>
</div>