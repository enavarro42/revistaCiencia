<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        	<h2 class="page-header">Evaluaciones del Manuscrito</h2>

        	<?php if(isset($this->manuscritos) && $this->manuscritos){ ?>

        	<table class="table table-bordered">
        		<thead>
        			<tr class="active">
        				<th>ID</th>
        				<th>Manuscrito</th>
        				<th>Evaluaciones de los &Aacute;rbitros</th>
                        <th>Evaluaciones del Editor</th>

        			</tr>
        		</thead>
        		<tbody>
                    <?php for($i = 0; $i < count($this->manuscritos); $i++){ ?>
            			<tr>
                            <td><?php echo $this->manuscritos[$i]['id_manuscrito']; ?></td>
                            <td><?php echo $this->manuscritos[$i]['titulo']; ?></td>
            				<td><a href="<?php echo $this->enlace . "/" . $this->manuscritos[$i]['id_manuscrito']; ?>" class="btn btn-primary">Ver Evaluaci&oacute;n</a></td>
            			    <td><a href="<?php echo $this->enlaceCrearEvaluacionEditor . $this->manuscritos[$i]['id_manuscrito']; ?>" class="btn btn-success">Crear</a> <a href="<?php echo $this->enlaceDetalleEvaluacionEditor . $this->manuscritos[$i]['id_manuscrito']; ?>" class="btn btn-default">Ver Evaluaci&oacute;n</a></td>
                        </tr>
                    <?php } ?>
        		</tbody>
        	</table>

        	<ul class="pagination">
                <?php echo $this->paginacion;?>
            </ul>

        	<?php }else{ ?>
        		<div class="alert alert-danger" role="alert"> No se encontraron resultados.</div>
        	<?php } ?>
        </div>
    </div>
</div>