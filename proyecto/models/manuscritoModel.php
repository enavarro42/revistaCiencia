<?php


class manuscritoModel extends Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function setObra($id_idioma, $issn, $id_materia){
        $this->_db->prepare(
                "insert into obra(tipo, id_idioma, issn, id_materia) values(:tipo, :id_idioma, :issn, :id_materia)"
                )
                ->execute(
                    array(
                        ':tipo' => 'manuscrito',
                        ':id_idioma' => $id_idioma,
                        ':issn' => $issn,
                        ':id_materia' => $id_materia
                    )
                );
    }
    
    public function setRevision($id_responsable, $id_estatus, $id_fisico){
        $this->_db->query("INSERT INTO revision(id_responsable, id_estatus, id_fisico, fecha) VALUES($id_responsable, $id_estatus, $id_fisico, NOW())");
    }
    
    public function setManuscrito($titulo, $resumen, $id_obra){
        $this->_db->prepare("INSERT INTO manuscrito(titulo, resumen, id_obra) VALUES(:titulo, :resumen, :id_obra)")
                ->execute(
                        array(
                            ":titulo" => $titulo,
                            ":resumen" => $resumen,
                            ":id_obra" => $id_obra
                        )
                 );
    }
    
    public function setResponsable($id_manuscrito, $id_persona, $id_rol){
         $this->_db->prepare("INSERT INTO responsable(id_manuscrito, id_persona, id_rol) VALUES(:id_manuscrito, :id_persona, :id_rol)")
                 ->execute(
                         array(
                             ":id_manuscrito" => $id_manuscrito,
                             ":id_persona" => $id_persona,
                             ":id_rol" => $id_rol
                         ));
    }
    
    public function setFisico($carpeta, $nombre_arch){
        $this->_db->prepare("INSERT INTO fisico(carpeta, nombre) VALUES(:carpeta, :nombre_arch)")
                ->execute(
                        array(
                            ":carpeta" => $carpeta,
                            ":nombre_arch" => $nombre_arch
                        ));
    }
    
    public function getCountFisico(){
       $id = $this->_db->query(
                "SELECT COUNT(*) from fisico"
                );
        return $id->rowCount();
    }
    
    public function getUltimoFisico(){
        $id = $this->_db->query(
                "SELECT MAX(id_fisico) as id_fisico from fisico"
                );
        return $id->fetch();
    }
    
    public function getUltimoResponsable(){
        $id = $this->_db->query(
                "SELECT MAX(id_responsable) as id_responsable from responsable"
                );
        return $id->fetch();
    }
    
    public function getUltimaObra(){
        $id_obra = $this->_db->query("select MAX(id_obra) as id_obra from obra");
        return $id_obra->fetch();
    }
    
    public function getRevisiones($id_manuscrito){ //obra, autor, autor_obra,
        $revisiones = $this->_db->query("SELECT DISTINCT manuscrito.id_manuscrito, manuscrito.titulo, revista.nombre, rol.rol, estatus.estatus, revision.fecha ".
                                        "FROM manuscrito, obra, revista, responsable, rol, persona_rol, revision, estatus ".
                                        "WHERE manuscrito.id_manuscrito = $id_manuscrito ".
                                        "and manuscrito.id_obra = obra.id_obra ".
                                        "and obra.issn = revista.issn ".
                                        "and manuscrito.id_manuscrito = responsable.id_manuscrito ".
                                        "and persona_rol.id_persona = responsable.id_persona ".
                                        "and rol.id_rol = responsable.id_rol ".
                                        "and revision.id_responsable = responsable.id_responsable ".
                                        "and revision.id_estatus = estatus.id_estatus ".
                                        "order by revision.fecha DESC");
        return $revisiones->fetchAll();
    }
    
    
     public function getPersona($id_persona){
        $persona = $this->_db->query("select * from persona where id_persona = $id_persona");
        return $persona->fetch();
    }
    
    public function getObra($id_obra){
        $obra = $this->_db->query("select * from obra where id_obra = $id_obra");
        return $obra;
    }
    
    public function getObraAutor($id_obra){
        $autor = $this->_db->query("select id_autor from autor_obra where id_obra = $id_obra");
        return $autor;
    }
    
    public function getObraManuscrito($id_autor){
        $obra = $this->_db->query("SELECT DISTINCT obra.id_obra FROM obra, autor_obra WHERE autor_obra.id_autor = $id_autor and autor_obra.id_obra = obra.id_obra and obra.tipo='manuscrito'");
        return $obra;
    }
    
    public function getIdAutor($id_persona){
        $autor = $this->_db->query("select id_autor from autor where id_persona = $id_persona");
        return $autor->fetch();
    }
    
    public function getAutor($id_autor){
        $autor = $this->_db->query("select * from autor where id_autor = $id_autor");
        return $autor->fetch();
    }
    
    public function getIdRol($rol){
        $id_rol = $this->_db->query("select id_rol from rol where rol = $rol");
        return $id_rol->fetch();
    }
    
    public function getRol($id_rol){
        $rol = $this->_db->query("select rol from rol where id_rol = $id_rol");
        return $rol->fetch();
    }
    
    public function getManuscrito($id_manuscrito){

        $manuscrito = $this->_db->query("select * from manuscrito where id_manuscrito = $id_manuscrito");
        return $manuscrito->fetch();
        
    }
    
    public function getManuscritoObra($id_obra){
        $manuscrito = $this->_db->query("select * from manuscrito where id_obra = $id_obra");
        return $manuscrito->fetch();
    }

    
    public function getManuscritosAutor($id_autor){
        $manuscrito = $this->_db->query("SELECT manuscrito.id_manuscrito, manuscrito.titulo ".
                                        "FROM manuscrito, obra, autor, autor_obra ".
                                        "WHERE autor.id_autor = $id_autor ".
                                        "and autor.id_autor = autor_obra.id_autor ".
                                        "and autor_obra.id_obra = obra.id_obra ".
                                        "and obra.tipo = 'manuscrito' ".
                                        "and manuscrito.id_obra = obra.id_obra ".
                                        "order by manuscrito.id_manuscrito DESC");
        return $manuscrito->fetchAll();
    }
    
    public function getResponsable($id_manuscrito){
        $responsable = $this->_db->query("select id_responsable from responsable where id_manuscrito = $id_manuscrito");
        return $responsable->fetch();
    }
    
    public function getRevision($responsables){
        $sql_responsable;
        for($i=0; $i<count($responsables); $i++){
            if($i != 0) $sql_responsable .= ' and ';
            $sql_responsable .= 'id_responsable = '.$responsables[$i];
        }
        
        $revision = $this->_db->query("select * from revision where $sql_responsable order by fecha DESC");
        return $revision->fetch();
        
    }
    
    public function getEstatus($id_estatus){
        $estatus = $this->_db->query("select * from estatus where id_estatus = $id_estatus");
        return $estatus->fetch();
    }
    
    public function getFisico($id_fisico){
        $fisico = $this->_db->query("select * from fisico where id_fisico = $id_fisico");
        return $fisico->fetch();
    }
    
    public function getRevista($id_revista){
        $revista = $this->_db->query("select nombre from revista where id_revista = $id_revista");
        return $revista->fetch();
    }
    
    public function getObraRevista($id_obra){
        $revista = $this->_db->query("select distinct issn from obra where id_obra = $id_obra");
        return $revista->fetch();
    }
    
    public function getMateria($id_materia){
        $materia = $this->_db->query("select nombre from materia where id_materia = $id_materia");
        return $materia->fetch();
    }
    
    public function getObraMeteria($id_obra){
        $materia = $this->_db->query("select distinct id_materia from obra where id_obra = $id_obra");
        return $materia->fetch();
    }
    
}


?>