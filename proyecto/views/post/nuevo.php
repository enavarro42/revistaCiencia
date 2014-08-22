<form id="form1" method="post" action="<?php echo BASE_URL; ?>post/nuevo" enctype="multipart/form-data">
    <input type="hidden" name="guardar" value="1" />
    <p>Titulo:<br />
        <input type="text" name="titulo" value="<?php if(isset($this->datos['titulo'])) echo $this->datos['titulo']; ?>" />
    </p>
    <p>Cuerpo:<br />
        <textarea  name="cuerpo" ><?php if(isset($this->datos['cuerpo'])) echo $this->datos['cuerpo']; ?></textarea>
    </p>
    
    <input type="file" name="imagen" />
    
    <p><input type="submit" class="btn" value="Guardar" /></p>
</form>