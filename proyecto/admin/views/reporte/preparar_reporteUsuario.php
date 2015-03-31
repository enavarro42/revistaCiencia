<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header"><?php echo $this->titulo; ?></h2>

            <form action="" method="post" accept-charset="utf-8">

        		<div class="form-group col-md-3 col-lg-3">
				    <label for="rol">Tipo de Usuario</label>
				    <select name="rol" class="form-control">
				    	<option value="0" selected>Todos</option>

				    	<?php for($i = 0; $i<count($this->roles); $i++){ ?>
				    		<?php if(isset($this->tipoUsuario_selected) && $this->tipoUsuario_selected == $this->roles[$i]['id_rol']){ ?>
				    			<option value="<?php echo $this->roles[$i]['id_rol']; ?>" selected><?php echo $this->roles[$i]['rol']; ?></option>
				    		<?php }else{ ?>
				    			<option value="<?php echo $this->roles[$i]['id_rol']; ?>"><?php echo $this->roles[$i]['rol']; ?></option>
				    		<?php } ?>
				    	<?php } ?>
				    </select>
				    <label class="error"></label>
				 </div>

				 <div class="form-group col-md-3 col-lg-3">
				 	<label for="area">&Aacute;rea</label>
				    <select name="area" class="form-control">

				    	<option value="0" selected>Todos</option>

				    	<?php for($i = 0; $i<count($this->areas); $i++){ ?>
				    		<?php if(isset($this->area_selected) && $this->area_selected == $this->areas[$i]['id_area']){ ?>
				    			<option value="<?php echo $this->areas[$i]['id_area']; ?>" selected><?php echo $this->areas[$i]['nombre']; ?></option>
				    		<?php }else{ ?>
				    			<option value="<?php echo $this->areas[$i]['id_area']; ?>"><?php echo $this->areas[$i]['nombre']; ?></option>
				    		<?php } ?>
				    	<?php } ?>
				    	
				    </select>
				    <label class="error"></label>
				 </div>

            	<input type="hidden" value="1" name="enviar" />
            	<div class="col-lg-12">
            		<input type="submit" class="btn btn-primary" value="Generar Reporte" />
            	</div>
            	
            </form>
        </div>
    </div>
</div>