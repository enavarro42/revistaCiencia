<h3>Nuevo Manuscrito</h3>


<!--

<div class="alert alert-warning fade in">
    <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
    <strong>Holy guacamole!</strong>
    Best check yo self, you're not looking too good.
</div>

-->

<div id="prueba"></div>
<form id="form_manuscrito" enctype="multipart/form-data" method="post">
<div id="tabs">
	<ul>
            <li><a href="#tabs-1"><span class="glyphicon glyphicon-pencil icon_gly"></span>Autores</a></li>
            <li><a href="#tabs-2"><span class="glyphicon glyphicon-ok icon_gly"></span>Pol&iacute;ticas</a></li>
            <li><a href="#tabs-3"><span class="glyphicon glyphicon-book icon_gly"></span>Manuscrito</a></li>
            
	</ul>
    <!--Autor-->
	<div id="tabs-1">
            
           <div class="col-md-12" id="msj_cargar_autor">
               Cargar datos del Autor de &eacute;sta cuenta pulse <button type="button" id="btn_cargar_autor" class="btn btn-link">Aqu&iacute;</button>
           </div>
            
           <div class="col-md-7" id="caja_campos_autor">
                
           </div>
            
            <div class="col-md-8">
                <button id="btn_add" type="button" class="btn btn-default">A&ntilde;adir</button>
            </div>
            
	</div>
    <!--Politicas-->
	<div id="tabs-2">
            <h3>Lista de comprobaci&oacute;n de env&iacute;o</h3>
            <ul class="list-group">
                <li class="list-group-item">Cras justo odio</li>
                <li class="list-group-item">Dapibus ac facilisis in</li>
                <li class="list-group-item">Morbi leo risus</li>
                <li class="list-group-item">Porta ac consectetur ac</li>
                <li class="list-group-item">Vestibulum at eros</li>
            </ul>
            
            <h3>Polit&iacute;cas de privacidad</h3>
            <p>Los nombres y direcciones de correo introducidos en esta revista se usar&aacute;n exclusivamente para fines declarados por esta revista y no estar&aacute;n disponibles para ning&uacute;n otro prop&oacute;sito.</p>
            
            <br />
            
            <div class="checkbox">
                <label>
                  <input type="checkbox" id="checkbox" value="">
                  Acepto
                </label>
            </div>
            
	</div>
        <!--Manuscrito-->
    <div id="tabs-3">
            
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
                        <p class="help-block">ASEGURÁNDOSE QUE LA REVISIÓN SEA A CIEGAS</p>
                        <!--<div id="xhr_result"></div>-->
                        
                    </div>

                </div>
            
    </div>
</div>

<div id="btn_tabs">
    
    <button id="btn_enviar" type="button" class="btn btn-success">Enviar</button>
    <progress id="progressBar" value="0" max="100" style="width:350px;"></progress> 
    <button id="btn_siguiente" class="btn btn-default btn-right" type="button">Siguente</button>
    <button id="btn_anterior" class="btn btn-default btn-right" type="button">Anterior</button>
    
    
</div>
    
    <h3 id="status"></h3> 
    <h3 id="resp"></h3> 
    <p id="loaded_n_total"></p> 
</form>