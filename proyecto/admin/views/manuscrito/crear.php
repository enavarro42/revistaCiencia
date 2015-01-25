<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        	<form id="form_manuscrito" enctype="multipart/form-data" method="post">
            	<h2 class="page-header">Crear Manuscrito <input type="button" class="btn btn-success pull-right" name="btn_enviar" id="btn_enviar" value="Guardar"></h2>



            	<div role="tabpanel">

				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#autores" aria-controls="autores" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-pencil icon_gly"></span> Autores</a></li>
				    <li role="presentation"><a href="#manuscrito" aria-controls="manuscrito" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-book icon_gly"></span> Manuscrito</a></li>
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="autores">
				    	<h4>Autor Responsable</h4>
				    	<div class="alert alert-info" role="alert">

				    		<div class="controls">
		                        <label for="responsableEmail">Correo </label>
		                        <input type="text" name="responsableEmail" id="responsableEmail" class="form-control" value="" />
		                        <label class="error" id="error_responsableCorreo"></label>
		                    </div>
				    		
				    		<div class="controls">
		                        <label for="responsableNombre">Nombre </label>
		                        <input type="text" name="responsableNombre" id="responsableNombre" class="form-control" value="" />
		                        <label class="error" id="error_responsableNombre"></label>
		                    </div>

		                    <div class="controls">
		                        <label for="responsableApellido">Apellido </label>
		                        <input type="text" name="responsableApellido" id="responsableApellido" class="form-control" value="" />
		                        <label class="error" id="error_responsableApellido"></label>
		                    </div>

				    	</div>

				    	
				    	<h4>Co-Autores</h4>

				    	<div id="panelAutores">
				    		
				    	</div>

				    	<button type="button" id="btn_add_autor" class="btn btn-success "><i class="fa fa-plus"></i> Agregar Co-Autores</button>

				    </div>
				    <div role="tabpanel" class="tab-pane" id="manuscrito">
				    	
				    	<div class="col-lg-6">
					    	<div class="controls">
		                        <label for="titulo">T&iacute;tulo: </label>
		                        <input type="text" name="titulo" id="titulo" class="form-control" value="" />
		                        <label class="error" id="error_titulo"></label>
		                    </div>

		                    <div class="controls">
		                        <label for="resumen">Resumen</label>
		                        <textarea id="resumen" name="resumen" class="form-control" rows="3"></textarea>
		                        <label class="error" id="error_resumen"></label>
		                        <br />
		                    </div>

		                    <div class="controls">
		                        <label for="revista">Revista</label>
		                        <select id="revista" name="revista" class="form-control required">
		                             <option value="0">-seleccione-</option>
		                        </select>
		                        <label class="error" id="error_revista"></label>
		                        <br />
		                    </div>

		                    <div class="controls">
		                        <label for="area">&Aacute;rea</label>
		                        <select id="area" name="area" class="form-control required">
		                             <option value="0">-seleccione-</option>
		                        </select>
		                        <label class="error" id="error_area"></label>
		                        <br />
		                    </div>

		                    <div class="controls">
		                        <label for="idioma">Idioma</label>
		                        <select id="idioma" name="idioma" class="form-control required">
		                             <option value="0">-seleccione-</option>
		                        </select>
		                        <label class="error" id="error_idioma"></label>
		                        <br />
		                    </div>


		                    <div class="controls">
		                        <label for="palabrasClaves">Palabras claves: </label>
		                        <input type="text" name="palabrasClaves" id="palabrasClave" class="form-control" value="" placeholder="Ejemplo: test1, test2" />
		                        <label class="error" id="error_palabrasClave"></label>
		                    </div>

		                    <br />
		                    <div class="controls">
		                        <label for="archivo">Subir archivo</label>
		                        <input type="file" id="archivo" name="archivo">
		                        <div id="xhr_status"></div>
		                        <label class="error" id="error_archivo"></label>
		                        <br />
		                        <!--<div id="xhr_result"></div>-->
		                        
		                    </div>
				    	</div>

				    </div>
				  </div>

				</div>
				            
			</form>

			<div class="col-lg-12 pd">
				<progress id="progressBar" value="0" max="100" style="width:350px;"></progress>
	        	<h3 id="status"></h3> 

			</div>
        </div>
        <!-- /.col-lg-12 -->


    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->