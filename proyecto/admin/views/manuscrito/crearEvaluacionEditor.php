<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        	<h3 class="page-header">[Editor] Crear Evaluaci&oacute;n del Manuscrito</h3>


        	<form method="post" accept-charset="utf-8" enctype="multipart/form-data">
        		<input type="hidden" name="manuscrito" id="manuscrito" value="<?php echo $this->manuscrito['id_manuscrito']; ?>">

        		<div class="form-group">
        			<p><strong>T&iacute;tulo:</strong> <?php echo $this->manuscrito['titulo']; ?></p>
        		</div>

        		<div class="form-group">
        			<label for="tipoEstatus" class="control-label">Estatus</label>
   				
        			<select class="form-control form-select" name="tipoEstatus" id="tipoEstatus">
        				<option value="formatoValido">Formato v&aacute;lido</option>
        				<option value="corregirFormato">Corregir formato</option>
        			</select>
        			
        		</div>

        		<div class="form-group">
        			<label for="observaciones" class="control-label">Observaciones</label>
        			<textarea class="form-control" rows="3" id="observaciones" name="observaciones"></textarea>
                    <label class="error" id="error_observacion"></label>
        		</div>

        		<div class="form-group">
				    <label for="uploadFile">Seleccione un archivo</label>
				    <input type="file" id="archivo" name="archivo">
				    <label class="error" id="error_archivo"></label>
				    <p class="help-block">Puede subir el manuscrito con las observaciones pertinentes.</p>
				    <progress id="progressBar" value="0" max="100" style="width:350px;"></progress>
				    <h3 id="status"></h3> 
				</div>

				<div class="form-group"><button type="button" id="btn_enviar" class="btn btn-primary">Evaluar</button></div>
        		
        	</form>

        	
        </div>
    </div>
</div>