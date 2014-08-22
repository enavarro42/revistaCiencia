<h3>Oficina de Publicaciones</h3>

<?php echo $this->datosOficina['descripcion']; ?>

<ul class="list-group">
  <?php for($i = 0; $i < count($this->revistas); $i++): ?>
    <li class="list-group-item"><?php echo $this->revistas[$i]['nombre']; ?></li>
  <?php endfor; ?>
</ul>


<h3>Ubicaci&oacute;n y contacto</h3>

<p><strong>Coordinador / ra: </strong> <?php echo $this->datosOficina['coordinador']; ?></p>
<p><strong>Direcci&oacute;n: </strong> <?php echo $this->datosOficina['direccion']; ?></p>
<p><strong>Tel&eacute;fono: </strong> <?php echo $this->datosOficina['telefono']; ?></p>
<p><strong>Fax: </strong> <?php echo $this->datosOficina['fax']; ?></p>
<p><strong>Correo: </strong> <?php echo $this->datosOficina['correo']; ?></p>