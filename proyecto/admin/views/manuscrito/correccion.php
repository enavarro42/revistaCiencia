<h3>Correcciones de manuscrito</h3>


<label for="archivo">Subir archivo</label>
<input type="file" id="archivo" name="archivo" class="btn btn-default">
<input type="hidden" name="manuscrito" id="manuscrito" value="<?php echo $this->id_manuscrito; ?>">
<label class="text-success" id="status"></label>
<label class="error" id="error_archivo"></label>
<a class="btn btn-default">
	<span class="glyphicon glyphicon-warning-sign icon_gly"></span>
	Asegur&aacute;ndose que la revisi&oacute;n sea a ciegas
</a>

<div class="controls">
	<button type="button" class="btn btn-success" id="btn_enviar">Enviar</button>
	<progress id="progressBar" value="0" max="100" style="width: 250px;"></progress>
</div>