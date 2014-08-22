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
        	<td><a href="<?php echo $this->descargar[$i]; ?>" class="btn btn-success">Descargar</a></td>
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