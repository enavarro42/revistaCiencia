<h3 class="page-header">Detalles del Manuscritos</h3>

<?php if($this->estatusEvaluacionEditor){ ?>

	<h3><strong>Evaluaci&oacute;n del Editor</strong></h3>
	<p><strong>T&iacute;tulo: </strong><?php echo $this->data['titulo']; ?></p>
	<p><strong>Estatus: </strong><?php echo $this->data['estatus']; ?></p>
	<p><strong>Fecha: </strong><?php echo $this->data['fecha']; ?></p>

<?php }else if($this->estatusEvaluacionArbitro){ ?>
<h3><strong>Evaluaci&oacute;n de Árbitros</strong></h3>

	<p><strong>T&iacute;tulo: </strong><?php echo $this->data['titulo']; ?></p>
	<p><strong>Estatus: </strong><?php echo $this->data['estatus']; ?></p>
	<p><strong>Fecha: </strong><?php echo $this->data['fecha']; ?></p>
	<?php if(isset($this->data['evaluacion'])){ ?>
		<?php for($i = 0; $i<count($this->data['evaluacion']); $i++){ ?>
			<h4 class="page-header">&Aacute;rbitro #<?php echo ($i + 1); ?></h4>


			<h4>Sugerencias</h4>
			<pre><?php echo $this->data['evaluacion'][$i]['sugerencia']; ?></pre>


			<?php if(isset($this->data['evaluacion'][$i]['link_archivo'])){ ?> 
				<strong>Archivo Adjunto</strong> <a href="<?php echo BASE_URL . $this->data['evaluacion'][$i]['link_archivo']; ?>" class="btn btn-success"><span class="glyphicon glyphicon-save"></span> Descargar</a>
			<?php } ?>
		<?php } ?>
	<?php } ?>

<?php }else{ ?>
	<p><strong>T&iacute;tulo: </strong><?php echo $this->data['titulo']; ?></p>
	<p><strong>Estatus: </strong><?php echo $this->data['estatus']; ?></p>
	<p><strong>Fecha: </strong><?php echo $this->data['fecha']; ?></p>
	<?php if(isset($this->data['link_archivo'])){ ?> 
		<a href="<?php echo BASE_URL . $this->data['link_archivo']; ?>" class="btn btn-success"><span class="glyphicon glyphicon-save"></span> Descargar</a>
	<?php } ?>
<?php } ?>