<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        	<form id="form_manuscrito" enctype="multipart/form-data" method="post">
            	<h2 class="page-header">Crear Manuscrito <input type="submit" class="btn btn-success pull-right" name="btn_enviar" value="Guardar"></h2>

				            
				<div id="tabs">
					<ul>
			            <li><a href="#tabs-1"><span class="glyphicon glyphicon-pencil icon_gly"></span>Autores</a></li>
			            <li><a href="#tabs-2"><span class="glyphicon glyphicon-book icon_gly"></span>Manuscrito</a></li>
					</ul>
				    <!--Autor-->
					<div id="tabs-1">
				            
				            <div class="form-group col-md-4">
				            	<label>DNI</label>
				            	<input type="text" class="form-control" name="consultar_cedula" value="" placeholder="DNI, C&eacute;dula de identidad">
				            	
				            </div>
				            <div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div>
				            <div class="form-group col-md-4">
				            	<button type="button" id="btn_consultar" class="btn btn-info">Consultar</button>
				            </div>

				            <div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div>
				            
				           <div class="col-md-7" id="caja_campos_autor">
				                
				           </div>
				            
				            <div class="col-md-8">
				                <button id="btn_add" type="button" class="btn btn-default">A&ntilde;adir</button>
				            </div>
				            
					</div>

				        <!--Manuscrito-->
				    <div id="tabs-2">
				            
			                <div class="col-md-12">

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
			</form>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->