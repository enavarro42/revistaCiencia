
<?php


class articuloModel extends Model{
    
    public function __construct(){
        parent::__construct();
    }

    public function setArticulo($titulo, $resumen, $id_obra, $palabras_claves){
    	
    	$this->_db->query(
                "INSERT INTO articulo(titulo, resumen, id_obra, palabras_claves) VALUES ('$titulo', '$resumen', $id_obra, '$palabras_claves');"
                );
    }

    public function crearArticulo($titulo, $resumen, $issn, $id_area, $id_idioma, $palabras_claves){


    	$this->_db->query(
                "INSERT INTO obra(tipo, id_idioma, issn, id_area, fecha) VALUES ('articulo', $id_idioma, '$issn', $id_area, current_date);"
                );

    	 $obra = $this->_db->query(
                "SELECT MAX(id_obra) as id_obra from obra"
                );
        $obra = $obra->fetch();

    	$this->_db->query(
                "INSERT INTO articulo(titulo, resumen, id_obra, palabras_claves) VALUES ('$titulo', '$resumen',".$obra["id_obra"].", '$palabras_claves');"
                );
    }

    public function setArticuloPersona($id_articulo, $id_persona, $esAutor){
    	$this->_db->query(
                "INSERT INTO articulo_autor(id_articulo, id_persona, autor_principal) VALUES ($id_articulo, $id_persona, $esAutor);"
                );
    }

    public function getUltimoArticulo(){
        $id = $this->_db->query(
                "SELECT MAX(id_articulo) as id_articulo from articulo"
                );
        return $id->fetch();
    }

    public function setFisicoArticulo($id_articulo, $id_fisico){
        
        $this->_db->query("UPDATE articulo SET id_fisico=$id_fisico WHERE id_articulo = $id_articulo;");
    }
}

?>