<h2>Ejemplo Ajax</h2>

<form>
    
    Pais:
    <select id="pais">
        <option value="">-seleccione-</option>
        <?php for($i = 0; $i<count($this->paises); $i++): ?>
        <option value="<?php echo $this->paises[$i]['id']?>"> <?php echo $this->paises[$i]['pais'];?> </option>
        <?php endfor;?>
    </select>
    
    <p> </p>
    
    Ciudad:
    <select id="ciudad"></select>
    
    <p> </p>
    Ciudad a insertar:
    <input type="text" id="ins_ciudad" />
    <input type="button" value="insertar" id="btn_insertar"/>
</form>