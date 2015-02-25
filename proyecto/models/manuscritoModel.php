<?php


class manuscritoModel extends Model{
    
    public function __construct(){
        parent::__construct();
    }

    public function validarResponsable($id_manuscrito, $id_persona, $id_rol){
        $persona = $this->_db->query(
                "SELECT * FROM responsable WHERE id_manuscrito = $id_manuscrito and id_persona = $id_persona and id_rol = $id_rol"
                );
        return $persona->fetch();
    }

    public function updateEstatusManuscrito($id_manuscrito = false, $id_estatus = false){
            $this->_db->query(
                "UPDATE manuscrito ".
                "SET id_estatus = $id_estatus WHERE id_manuscrito = " . $id_manuscrito
                );
    }
    public function getResponsableById($id_responsable){
        $result = $this->_db->query("SELECT * FROM responsable where id_responsable = $id_responsable");
        return $result->fetch();
    }
    
    public function setObra($id_idioma, $issn, $id_area){
        $this->_db->prepare(
                "insert into obra(tipo, id_idioma, issn, id_area, fecha) values(:tipo, :id_idioma, :issn, :id_area, current_timestamp)"
                )
                ->execute(
                    array(
                        ':tipo' => 'manuscrito',
                        ':id_idioma' => $id_idioma,
                        ':issn' => $issn,
                        ':id_area' => $id_area
                    )
                );
    }
    
    public function setRevision($id_responsable, $id_estatus, $id_fisico){
        $this->_db->query("INSERT INTO revision(id_responsable, id_estatus, id_fisico, fecha) VALUES($id_responsable, $id_estatus, $id_fisico, LOCALTIMESTAMP)");

        $responsable = manuscritoModel::getResponsableById($id_responsable);

        if($responsable)
            manuscritoModel::updateEstatusManuscrito($responsable["id_manuscrito"], $id_estatus);

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
    
    public function setResponsable($id_manuscrito, $id_persona, $id_rol, $permiso, $correspondencia){
         $this->_db->prepare("INSERT INTO responsable(id_manuscrito, id_persona, id_rol, permiso, correspondencia) VALUES(:id_manuscrito, :id_persona, :id_rol, :permiso, :correspondencia)")
                 ->execute(
                         array(
                             ":id_manuscrito" => $id_manuscrito,
                             ":id_persona" => $id_persona,
                             ":id_rol" => $id_rol,
                             ":permiso" => $permiso,
                             ":correspondencia" => $correspondencia
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

    public function setRespuestaEvaluacion($id_evaluacion, $id_responsable, $cambios){
        $this->_db->prepare("INSERT INTO respuesta_evaluacion(id_evaluacion, id_responsable, cambios_realizados) VALUES(:id_evaluacion, :id_responsable, :cambios_realizados)")
                ->execute(
                        array(
                            ":id_evaluacion" => $id_evaluacion,
                            ":id_responsable" => $id_responsable,
                            ":cambios_realizados" => $cambios
                        ));
    }
    
    public function getCountFisico(){
       $id = $this->_db->query(
                "SELECT COUNT(*) as count_fisico from fisico"
                );
        return $id->fetch();
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
        $revisiones = $this->_db->query("SELECT DISTINCT manuscrito.id_manuscrito, manuscrito.titulo, revista.nombre, rol.rol, estatus.id_estatus, estatus.estatus, revision.fecha, revision.id_revision ".
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


    public function getRevisionById($id_revision){
        $revision = $this->_db->query("SELECT * from revision where id_revision = $id_revision");
        return $revision->fetch();
    }


    public function getEstatusById($id_estatus){
        $id = $this->_db->query(
                "SELECT * FROM estatus where id_estatus = $id_estatus"
                );
        return $id->fetch();
    }

    public function getEstatusByClave($clave){
        $id = $this->_db->query("SELECT * FROM estatus where clave = '$clave'");
        return $id->fetch();
    }

    public function getEstatusByClaves($clave){
        $id = $this->_db->query("SELECT * FROM estatus where clave in ($clave) ");
        return $id->fetchAll();
    }

    public function getEstatusByTipo($tipo){
        $id = $this->_db->query("SELECT * FROM estatus where tipo = '$tipo'");
        return $id->fetchAll();
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
        $autor = $this->_db->query("select id_persona from persona_obra where id_obra = $id_obra");
        return $autor;
    }
    
    public function getObraManuscrito($id_persona){
        $obra = $this->_db->query("SELECT DISTINCT obra.id_obra FROM obra, persona_obra WHERE persona_obra.id_persona = $id_persona and persona_obra.id_obra = obra.id_obra and obra.tipo='manuscrito'");
        return $obra;
    }
    
    
    public function getIdRol($rol){
        $id_rol = $this->_db->query("select id_rol from rol where rol = $rol");
        return $id_rol->fetch();
    }
    
    public function getRol($id_rol){
        $rol = $this->_db->query("select rol from rol where id_rol = $id_rol");
        return $rol->fetch();
    }

    public function editarSolicitudArbitraje($id_persona, $id_manuscrito, $opcion){
        $this->_db->query("UPDATE arbitros_manuscrito SET estatus=$opcion WHERE id_persona = $id_persona and id_manuscrito = $id_manuscrito;");
    }
    
    public function getManuscrito($id_manuscrito){

        $manuscrito = $this->_db->query("select * from manuscrito where id_manuscrito = $id_manuscrito");
        return $manuscrito->fetch();
        
    }
    
    public function getManuscritoObra($id_obra){
        $manuscrito = $this->_db->query("select * from manuscrito where id_obra = $id_obra");
        return $manuscrito->fetch();
    }

    public function getAutorManuscrito($id_manuscrito){
        $persona = $this->_db->query("SELECT id_persona ".
                                 "FROM responsable ".
                                  "WHERE responsable.id_manuscrito = $id_manuscrito ".
                                  "and (responsable.id_rol = (select id_rol from rol where rol = 'Autor') or responsable.id_rol = (select id_rol from rol where rol = 'Co-Autor')) order by id_persona");
        return $persona;
    }

    public function getManuscritoEvaluar($id_persona, $id_rol){

        $manuscrito = $this->_db->query("SELECT  manuscrito.id_manuscrito, manuscrito.titulo, revision.fecha ".
            "FROM manuscrito, responsable, revision ".
            "WHERE responsable.id_persona = $id_persona ".
            "and responsable.id_rol = $id_rol ".
            "and responsable.id_manuscrito = manuscrito.id_manuscrito ".
            "and responsable.permiso > 0 ".
            "and revision.id_responsable = responsable.id_responsable ".
            "order by revision.fecha DESC");



        return $manuscrito->fetchAll();
    }

    
    public function getManuscritosPersona($id_persona){
        // $manuscrito = $this->_db->query("SELECT manuscrito.id_manuscrito, manuscrito.titulo ".
        //                                 "FROM manuscrito, obra, persona, persona_obra ".
        //                                 "WHERE persona.id_persona = $id_persona ".
        //                                 "and persona.id_persona = persona_obra.id_persona ".
        //                                 "and persona_obra.id_obra = obra.id_obra ".
        //                                 "and obra.tipo = 'manuscrito' ".
        //                                 "and manuscrito.id_obra = obra.id_obra ".
        //                                 "order by manuscrito.id_manuscrito DESC");

        // $manuscrito = $this->_db->query("SELECT manuscrito.id_manuscrito, manuscrito.titulo ".
        //                                  "FROM manuscrito, persona, correspondencia ".
        //                                  "WHERE persona.id_persona = $id_persona ".
        //                                  "and persona.id_persona = correspondencia.id_persona ".
        //                                  "and correspondencia.id_manuscrito = manuscrito.id_manuscrito ".
        //                                  "order by manuscrito.id_manuscrito DESC");

        $manuscrito = $this->_db->query("SELECT manuscrito.id_manuscrito, manuscrito.titulo ".
                                         "FROM manuscrito, persona, responsable ".
                                         "WHERE persona.id_persona = $id_persona ".
                                         "and persona.id_persona = responsable.id_persona ".
                                         "and responsable.id_manuscrito = manuscrito.id_manuscrito ".
                                         "order by manuscrito.id_manuscrito DESC");

        if($manuscrito != false)
            return $manuscrito->fetchAll();
        return false;
    }

    
    public function getResponsable($id_manuscrito){
        $responsable = $this->_db->query("select * from responsable where id_manuscrito = $id_manuscrito and correspondencia = 1");
        return $responsable->fetch();
    }


    public function getPrimeraRevision($id_responsable){
        $responsable = $this->_db->query("select * from revision where id_responsable = $id_responsable order by fecha LIMIT 1");
        return $responsable->fetch();
    }

    public function getManuscritoActual($responsable){
        
        $revision = $this->_db->query("select * from revision where id_responsable = $responsable order by fecha DESC LIMIT 1");
        return $revision->fetch();
        
    }
    
    public function getRevision($responsable){
        
        $revision = $this->_db->query("select * from revision where id_responsable = $responsable order by fecha DESC");
        return $revision->fetch();
        
    }

    public function getManuscritoUbicacion($id_fisico){
        $ubicacion = $this->_db->query("select * from fisico where id_fisico = $id_fisico");
        return $ubicacion->fetch();
    }

    public function getEstatusPorNombre($estatus){
        $estatus = $this->_db->query("select * from estatus where estatus = '$estatus'");
        return $estatus->fetch();
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
    
    public function getAreas($id_area){
        $area = $this->_db->query("select nombre from area where id_area = $id_area");
        return $area->fetch();
    }
    
    public function getObraArea($id_obra){
        $area = $this->_db->query("select distinct id_area from obra where id_obra = $id_obra");
        return $area->fetch();
    }

    public function getEvaluacionesByRevision($id_revision = false){
        $result = $this->_db->query("select * from evaluacion where id_revision = $id_revision order by id_evaluacion ASC");
        return $result->fetchAll();
    }

    public function getArbitrosByManuscrito($id_manuscrito, $id_rol){
        $arbitro = $this->_db->query("SELECT re.id_responsable FROM responsable re, rol where rol.id_rol = re.id_rol and re.id_rol = $id_manuscrito and re.id_manuscrito = $id_rol");
        return $arbitro->fetchAll();
    }
    
}


?>