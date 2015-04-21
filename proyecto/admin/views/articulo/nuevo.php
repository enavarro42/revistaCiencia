<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header"><?php echo $this->titulo; ?></h2>
        </div>
        <!-- /.col-lg-12 -->

        <?php if((int)$this->id_articulo == 0){ ?>
        <div class="col-lg-6">

            <input type="hidden" name="id_articulo" id="id_articulo" value="0">

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
        <?php }else{ ?>
            <input type="hidden" name="id_articulo" id="id_articulo" value="<?php echo $this->id_articulo; ?>">
        <?php } ?>

        <div class="col-lg-6">
            
            <div class="controls">
                <label for="titulo">T&iacute;tulo: </label>
                <input type="text" name="titulo" id="titulo" class="form-control" value="<?php if(isset($this->titulo_articulo)) echo $this->titulo_articulo; ?>" />
                <label class="error" id="error_titulo"></label>
            </div>

            <div class="controls">
                <label for="resumen">Resumen</label>
                <textarea id="resumen" name="resumen" class="form-control" rows="3"><?php if(isset($this->resumen)) echo $this->resumen; ?></textarea>
                <label class="error" id="error_resumen"></label>
                <br />
            </div>

            <div class="controls">
                <label for="revista">Revista</label>
                <select id="revista" name="revista" class="form-control required">
                     <option value="0">-seleccione-</option>
                     <?php 
                        for($i = 0; $i<count($this->revista); $i++){
                            if(isset($this->obra) && $this->revista[$i]["issn"] == $this->obra["issn"]){
                                echo "<option value='".$this->revista[$i]["issn"]."' selected>".$this->revista[$i]["nombre"]."</option>";
                            }else{
                                echo "<option value='".$this->revista[$i]["issn"]."'>".$this->revista[$i]["nombre"]."</option>";
                            }
                        }
                     ?>
                </select>
                <label class="error" id="error_revista"></label>
                <br />
            </div>

            <div class="controls">
                <label for="area">&Aacute;rea</label>
                <select id="area" name="area" class="form-control required">
                     <option value="0">-seleccione-</option>
                        <?php 
                            for($i = 0; $i<count($this->areas); $i++){
                                if(isset($this->obra) && $this->areas[$i]["id_area"] == $this->obra["id_area"]){
                                    echo "<option value='".$this->areas[$i]["id_area"]."' selected>".$this->areas[$i]["nombre"]."</option>";
                                }else{
                                    echo "<option value='".$this->areas[$i]["id_area"]."'>".$this->areas[$i]["nombre"]."</option>";
                                }
                            }
                        ?>
                </select>
                <label class="error" id="error_area"></label>
                <br />
            </div>

            <div class="controls">
                <label for="idioma">Idioma</label>
                <select id="idioma" name="idioma" class="form-control required">
                     <option value="0">-seleccione-</option>
                        <?php 
                            for($i = 0; $i<count($this->idiomas); $i++){
                                if(isset($this->obra) && $this->idiomas[$i]["id_idioma"] == $this->obra["id_idioma"]){
                                    echo "<option value='".$this->idiomas[$i]["id_idioma"]."' selected>".$this->idiomas[$i]["nombre"]."</option>";
                                }else{
                                    echo "<option value='".$this->areas[$i]["id_idioma"]."'>".$this->areas[$i]["nombre"]."</option>";
                                }
                            }
                        ?>
                </select>
                <label class="error" id="error_idioma"></label>
                <br />
            </div>


            <div class="controls">
                <label for="palabrasClaves">Palabras claves: </label>
                <input type="text" name="palabrasClaves" id="palabrasClave" class="form-control" value="<?php if(isset($this->palabras_claves)) echo $this->palabras_claves; ?>" placeholder="Ejemplo: test1, test2" />
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

        <div class="col-lg-12 pd">
            <progress id="progressBar" value="0" max="100" style="width:350px;"></progress>
            <h3 id="status"></h3> 

            <input type="button" class="btn btn-success" name="btn_enviar" id="btn_enviar" value="Guardar">

        </div>



    </div>
    <!-- /.row -->
</div>