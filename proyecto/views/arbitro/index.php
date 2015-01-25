<h3>Arbitrar Manuscrito</h3>
<?php 
if(isset($this->manuscritos)){
?>
<table class="table table-hover">
	<thead>
	<tr>
	  <th>Manuscrito</th>
	  <th>Fecha</th>
	  <th class="text-center">Manuscrito Actual</th>
	  <th>Arbitrar</th>
	</tr>
	</thead>
	<tbody>
		<?php 
        	for($i = 0; $i < count($this->manuscritos); $i++){
        ?>
        <tr>
        	<td><?php echo $this->manuscritos[$i]['titulo']; ?></td>
        	<td><?php echo $this->manuscritos[$i]['fecha']; ?></td>
        	<td><a link="<?php echo $this->descargar[$i]; ?>" class="btn btn-success external">Descargar</a></td>
        	<td><a href="<?php echo $this->enlace . $this->manuscritos[$i]['id_manuscrito']; ?>" class="btn btn-info text-center">Evaluar</a></td>
        </tr>
		<?php } ?>
	</tbody>
</table>

<?php }else{ ?>
  <h4>
    <?php echo  $this->sin_manuscritos; ?>
  </h4>

<?php } ?>


<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Normas de evaluaci&oacute;n</h4>
      </div>
      <div class="modal-body">
        <p>Para evaluar el siguiente manuscrito le pedimos que utilice la herramienta de Comentarios, proporcionadas por el editor de Office Word.
        </p>

        <img src="<?php echo $_layoutParams['imageRuta']; ?>comentarioword.jpg" alt="" class="pd_tb">

        <p>
        	Para hacer un comentario solo dir&iacute;jace a la pestaña de <strong>REVISAR</strong>, luego aparecer&aacute;n varias opciones, de las cuales va a seleccionar <strong>Nuevo Comentario</strong>, luego debe sombrear el texto que llevar&aacute; el comentario y listo. Además podr&aacute; editar y borrar un comentario si así lo desea.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_dialog">Aceptar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->