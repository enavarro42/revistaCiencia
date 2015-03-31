<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        	<form action="" method="post" accept-charset="utf-8">

            <input type="hidden" name="id_manuscrito" id="id_manuscrito" value="<?php echo $this->id_manuscrito; ?>">
            <input type="hidden" name="enviado" id="enviado" value="1">

            	<h2 class="page-header"><?php echo $this->titulo; ?> <button type="submit" class="btn btn-success pull-right sp_left">Guardar</button></h2>

            		<div class="form-group">
                        <label for="titulo">T&iacute;tulo </label>
                        <input type="text" name="titulo" id="titulo" class="form-control" value="<?php if(isset($this->manuscrito['titulo'])) echo $this->manuscrito['titulo']; ?>" />
                        <label class="error" id="error_titulo"><?php if(isset($this->error_titulo)) echo $this->error_titulo; ?></label>
                    </div>

                    <div class="form-group">
                        <label for="resumen">Resumen</label>
                        <textarea id="resumen" name="resumen" class="form-control" rows="3"><?php if(isset($this->manuscrito['resumen'])) echo $this->manuscrito['resumen']; ?></textarea>
                        <label class="error" id="error_resumen"><?php if(isset($this->error_resumen)) echo $this->error_resumen; ?></label>
                        <br />
                    </div>

                    <div class="col-md-6 col-lg-6">

	                    <div class="form-group">
	                        <label for="revista">Revista</label>
	                        <select id="revista" name="revista" class="form-control required">
                            <?php if(isset($this->revista) && $this->revista){ ?>
	                             <option value="0">-seleccione-</option>
                               <?php for($i = 0; $i<count($this->revista); $i++){ ?>
                                  <?php if(trim($this->manuscrito['issn']) == trim($this->revista[$i]['issn'])){ ?>
                                    <option value="<?php echo $this->revista[$i]['issn']; ?>" selected><?php echo $this->revista[$i]['nombre']; ?></option>
                                  <?php }else{ ?>
                                    <option value="<?php echo $this->revista[$i]['issn']; ?>"><?php echo $this->revista[$i]['nombre']; ?></option>
                                  <?php } ?>
                              <?php } ?>
                            <?php }else{ ?>
                              <option value="0">-seleccione-</option>
                            <?php } ?>
	                        </select>
	                        <label class="error" id="error_revista"><?php if(isset($this->error_revista)) echo $this->error_revista; ?></label>

	                    </div>

	                    <div class="form-group">
	                        <label for="area">&Aacute;rea</label>
	                        <select id="area" name="area" class="form-control required">
	                          <?php if(isset($this->areas) && $this->areas){ ?>
                               <option value="0">-seleccione-</option>
                               <?php for($i = 0; $i<count($this->areas); $i++){ ?>
                                  <?php if(trim($this->manuscrito['id_area']) == trim($this->areas[$i]['id_area'])){ ?>
                                    <option value="<?php echo $this->areas[$i]['id_area']; ?>" selected><?php echo $this->areas[$i]['nombre']; ?></option>
                                  <?php }else{ ?>
                                    <option value="<?php echo $this->areas[$i]['id_area']; ?>"><?php echo $this->areas[$i]['nombre']; ?></option>
                                  <?php } ?>
                              <?php } ?>
                            <?php }else{ ?>
                              <option value="0">-seleccione-</option>
                            <?php } ?>
	                        </select>
	                        <label class="error" id="error_area"><?php if(isset($this->error_area)) echo $this->error_area; ?></label>
	                    </div>
                    </div>

                    <div class="col-md-6 col-lg-6">
	                    <div class="form-group">
	                        <label for="idioma">Idioma</label>
	                        <select id="idioma" name="idioma" class="form-control required">
	                         <?php if(isset($this->idiomas) && $this->idiomas){ ?>
                               <option value="0">-seleccione-</option>
                               <?php for($i = 0; $i<count($this->idiomas); $i++){ ?>
                                  <?php if(trim($this->manuscrito['id_idioma']) == trim($this->idiomas[$i]['id_idioma'])){ ?>
                                    <option value="<?php echo $this->idiomas[$i]['id_idioma']; ?>" selected><?php echo $this->idiomas[$i]['nombre']; ?></option>
                                  <?php }else{ ?>
                                    <option value="<?php echo $this->idiomas[$i]['id_idioma']; ?>"><?php echo $this->idiomas[$i]['nombre']; ?></option>
                                  <?php } ?>
                              <?php } ?>
                            <?php }else{ ?>
                              <option value="0">-seleccione-</option>
                            <?php } ?>
	                        </select>
	                        <label class="error" id="error_idioma"><?php if(isset($this->error_idioma)) echo $this->error_idioma; ?></label>
	                    </div>


	                    <div class="form-group">
	                        <label for="palabrasClaves">Palabras claves </label>
	                        <input type="text" name="palabrasClaves" id="palabrasClave" class="form-control" value="<?php if(isset($this->manuscrito['palabras_claves'])) echo $this->manuscrito['palabras_claves']; ?>" placeholder="Ejemplo: test1, test2" />
	                        <label class="error" id="error_palabrasClave"></label>
	                    </div>
                   </div>

                   <h2 class="page-header">Autores</h2>

                   <div id="info"></div>

                   <table class="table table-bordered">
                   	<thead>
                   		<tr>
                   			<th class="col_check"><input type="checkbox" name="checkall" id="checkall" value="all"></th>
                        <th>ID</th>
                   			<th>Nombre y Apellido</th>
                   		</tr>
                   	</thead>
                   	<tbody id="autores">
                   	</tbody>
                   </table>
                   <div class="form-group pull-right">
                     <button type="button" class="btn btn-danger " id="eliminar"><span class="glyphicon glyphicon-trash sp-gly-icon"></span>Eliminar</button>
                   </div>

                   <div class="clearfix visible-xs-block visible-sm-block visible-md-block visible-lg-block"></div>

                   <div class="form-group col-md-4 col-lg-4">
                        <label for="rol">Rol</label>
                        <select id="rol" name="rol" id="rol" class="form-control">
                          <option value="Autor">Autor</option>
                          <option value="Co-Autor">Co-Autor</option>
                        </select>
                    </div>


                    <div class="form-group col-md-4 col-lg-4">
                        <label for="id_persona">Agregar un Autor (Por ID) </label>
                        <input type="text" name="id_persona" id="id_persona" class="form-control" value="" />
                        <label class="error" id="error_id_persona"></label>
                    </div>


                    <div class="form-group col-lg-12">
                      <button type="button" id="agregar" class="btn btn-primary">Agregar</button>
                    </div>

                    

        	</form>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->