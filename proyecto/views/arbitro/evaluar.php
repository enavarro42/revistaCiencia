<h3>Evaluacion de Manuscrito</h3>

<div class="panel panel-default">
  <div class="panel-heading">1. <?php echo $this->_seccion[0]['seccion']; ?></div>
  <div class="panel-body">
  	<input type="hidden" name="manuscrito" value="<?php echo $this->id_manuscrito; ?>">
    <p class="titulo">TITULO: <strong><?php echo $this->_datos_manuscrito['titulo']; ?></strong></p>
    <p>Fecha de recepción del documento: <?php echo $this->_datos_revision['fecha']; ?></p>
  </div>
</div>


<div class="panel panel-default">
  <div class="panel-heading">2. <?php echo $this->_seccion[1]['seccion']; ?></div>
  <div class="panel-body">

  	<p><?php echo $this->_seccion[1]['descripcion']; ?></p>

  	<table class="table table-hover table-bordered">
		<thead>
		<tr>
		  <th>Aspecto</th>
		  <?php for($j=0; $j < count($this->_opciones_seccion_2); $j++){ ?>
		  <th class="text-center"><?php echo $this->_opciones_seccion_2[$j]['opcion']; ?></th>
		  <?php } ?>
		</tr>
		</thead>
		<tbody>
			<?php for($i=0; $i < count($this->_pregunstas_seccion_2); $i++){ ?>
			<tr>
				<td>
					<?php echo $this->_pregunstas_seccion_2[$i]['pregunta'] ?>
					<input type="hidden" name="pregunta_<?php echo $i; ?>" id="pregunta_<?php echo $i; ?>" value="<?php echo $this->_pregunstas_seccion_2[$i]['id_pregunta']; ?>">
				</td>
				<?php for($j=0; $j < count($this->_opciones_seccion_2); $j++){ ?>
				<td class="text-center">
					<input type="radio" name="seccion2_<?php echo 'opcion_'.$i; ?>" value="<?php echo $this->_opciones_seccion_2[$j]['id_opcion']; ?>">
				</td>
				<?php } ?>
			</tr>
			<?php } ?>
		</tbody>
	</table>

	<label class="error" id="error_seccion2"></label>

  </div>
</div>


<div class="panel panel-default">
  <div class="panel-heading">3. <?php echo $this->_seccion[2]['seccion']; ?></div>
  <div class="panel-body">

  	<p><?php echo $this->_seccion[2]['descripcion']; ?> </p>

  	<table class="table table-hover table-bordered">
		<thead>
		<tr>
		  <th>Aspecto</th>
		  <th class="text-center">Decisi&oacute;n</th>
		</tr>
		</thead>
		<tbody>
			<input type="hidden" name="preguntaSeccion3" id="preguntaSeccion3" value="<?php echo $this->_pregunstas_seccion_3[0]['id_pregunta'] ?>">
			<?php for($i=0; $i < count($this->_opciones_seccion_3); $i++){ ?>
			<tr>
				<td><?php echo $this->_opciones_seccion_3[$i]['opcion']; ?></td>
				<td class="text-center"><input type="radio" name="seccion3_opcion" value="<?php echo $this->_opciones_seccion_3[$i]['id_opcion']; ?>"></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

	<label class="error" id="error_seccion3"></label>

	 <div class="form-group">
	    <label for="inputPassword" class="control-label">Comentario</label>

	    <textarea class="form-control" rows="3" name="comentario" id="comentario"></textarea>
	  </div>

  </div>
</div>


<div class="panel panel-default">
  <div class="panel-heading">4. <?php echo $this->_seccion[3]['seccion']; ?></div>
  <div class="panel-body">

  	<p>Escriba las sugerencias que usted crea conveniente</p>


  	<div class="form-group">
		<label for="inputPassword" class="control-label">Sugerencias</label>

	    <textarea class="form-control" rows="3" name="sugerencia" id="sugerencia"></textarea>
	</div>

	<p>Escriba los cambios realizados en el manuscrito</p>

	<div class="form-group">
		<label for="inputPassword" class="control-label">Cambios realizados</label>

	    <textarea class="form-control" rows="3" name="cambios" id="cambios"></textarea>
	</div>

  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">6. <?php echo $this->_seccion[4]['seccion']; ?></div>
  <div class="panel-body">

  	<p><?php echo $this->_seccion[4]['descripcion']; ?></p>

  	<div class="radio">
	  <label>
	    <input type="radio" name="evaluar" id="evaluar" value="1">
	    Si
	  </label>
	</div>

	<div class="radio">
	  <label>
	    <input type="radio" name="evaluar" id="evaluar" value="0" checked>
	    No
	  </label>
	</div>

  </div>
</div>


<div class="panel panel-default">
  <div class="panel-heading">5. <?php echo $this->_seccion[5]['seccion']; ?></div>
  <div class="panel-body">

  	<p><?php echo $this->_seccion[5]['descripcion']; ?></p>

  	<label for="archivo">Subir archivo</label>
	<input type="file" id="archivo" name="archivo" class="btn btn-default">
	<input type="hidden" name="manuscrito" id="manuscrito" value="<?php echo $this->id_manuscrito; ?>">
	<label class="text-success" id="status"></label>
	<label class="error" id="error_archivo"></label>

  </div>
</div>

<hr>
<progress id="progressBar" value="0" max="100"></progress>
<button type="button" class="btn btn-success" id="btn_enviar">Enviar</button>


