<h3>Correcciones de manuscrito</h3>

<?php //if($this->tipo == 1) var_dump("Correccion Editor"); ?>

<?php //if($this->tipo == 2) var_dump("Correccion Arbitro"); ?>

<label for="archivo">Subir archivo</label>
<input type="file" id="archivo" name="archivo" class="btn btn-default">
<input type="hidden" name="manuscrito" id="manuscrito" value="<?php echo $this->id_manuscrito; ?>">
<input type="hidden" name="tipo" id="tipo" value="<?php echo $this->tipo; ?>">
<label class="text-success" id="status"></label>
<label class="error" id="error_archivo"></label>
<label class="error" id="error_evaluacion"></label>

<input type="hidden" id="contador" value="<?php echo count($this->evaluaciones); ?>">

<?php for($i=0; $i<count($this->evaluaciones); $i++){ ?>


	<input type="hidden" id="id_<?php echo $i; ?>" value="<?php echo $this->evaluaciones[$i]['id_evaluacion']; ?>">

	<div class="form-group">
		<label>Respuesta arbitro # <?php echo ($i +1); ?></label>
		<textarea id="resp_<?php echo $i; ?>" class="form-control"></textarea>
	</div>

<?php } ?>

<!-- <button type="button" class="btn btn-info" name="btn_test" id="btn_test">test</button> -->


<a class="btn btn-default">
	<span class="glyphicon glyphicon-warning-sign icon_gly"></span>
	Asegur&aacute;ndose que la revisi&oacute;n sea a ciegas
</a>







<div class="controls">
	<button type="button" class="btn btn-success" id="btn_enviar">Enviar</button>
	<progress id="progressBar" value="0" max="100" style="width: 250px;"></progress>
</div>