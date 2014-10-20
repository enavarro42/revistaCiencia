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

    public function getArbitros($id_rol, $ids = false){

        if($ids != '' && $ids != false){
            $persona = $this->_db->query("SELECT p.id_persona, p.\"primerNombre\" || ' ' || p.apellido as nombrecompleto, p.filiacion, p.\"resumenBiografico\" FROM persona p, persona_rol pr WHERE pr.id_rol = $id_rol and p.id_persona = pr.id_persona and p.id_persona NOT IN ($ids) order by p.\"primerNombre\"");
        }else{
            $persona = $this->_db->query("SELECT p.id_persona, p.\"primerNombre\" || ' ' || p.apellido as nombrecompleto, p.filiacion, p.\"resumenBiografico\" FROM persona p, persona_rol pr WHERE pr.id_rol = $id_rol and p.id_persona = pr.id_persona order by p.\"primerNombre\"");
        }
        
        return $persona->fetchAll();
    }

    public function asignarArbitroManuscrito($id_persona, $id_manuscrito, $rol){
        $this->_db->query("INSERT INTO responsable(id_manuscrito, id_persona, id_rol, permiso, correspondencia) ".
                        "VALUES ($id_manuscrito, $id_persona, $rol, 0, 0);");
    }

    public function editarEstatusArbitro($id_persona, $id_manuscrito, $estatus){
        $this->_db->query("UPDATE arbitros_manuscrito SET estatus=$estatus WHERE id_persona = $id_persona and id_manuscrito = $id_manuscrito;");
    }

    public function quitarArbitro($id_persona, $id_manuscrito){
        $this->_db->query("DELETE FROM arbitros_manuscrito WHERE id_persona = $id_persona and id_manuscrito = $id_manuscrito;");
        $this->_db->query("DELETE FROM responsable WHERE id_persona = $id_persona and id_manuscrito = $id_manuscrito;");
    }

    public function setArbitroPostulado($id_persona, $id_manuscrito){
        $random = rand(1718, 9999999999);
        $this->_db->query("INSERT INTO arbitros_manuscrito(id_persona, id_manuscrito, codigo) VALUES ($id_persona, $id_manuscrito, $random);");
    }

    public function getArbitrosPostulados($id_manuscrito){

        $arbitros = $this->_db->query("SELECT p.id_persona, p.\"primerNombre\" || ' ' || p.apellido as nombrecompleto, p.filiacion, p.\"resumenBiografico\", am.estatus FROM persona p, arbitros_manuscrito am WHERE am.id_persona = p.id_persona and am.id_manuscrito = $id_manuscrito order by p.\"primerNombre\"");

        return $arbitros->fetchAll();
    }

    public function getArbitroPostulado($id_persona, $id_manuscrito){

        $arbitro = $this->_db->query("SELECT * from arbitros_manuscrito where id_persona = $id_persona and id_manuscrito = $id_manuscrito");

        return $arbitro->fetch();
    }

    public function editarManuscrito($datos = false){
        if($datos){
            $this->_db->query(
                "UPDATE manuscrito ".
                "SET titulo='".$datos['titulo']."', resumen='".$datos['resumen']."' WHERE id_manuscrito = " . $datos['id_manuscrito']
                );

            $manuscrito = $this->_db->query("select * from manuscrito WHERE id_manuscrito = " . $datos['id_manuscrito']);

            if($manuscrito){
                $manuscrito = $manuscrito->fetch();

                $id_obra = $manuscrito['id_obra'];

                $this->_db->query("UPDATE obra set id_idioma=" . $datos['idioma'] . ", issn='".$datos['revista']."', id_area=". $datos['area'] . " where id_obra = " . $id_obra);
            }
        }
    }
    
    public function setObra($id_idioma, $issn, $id_area){
        $this->_db->prepare(
                "insert into obra(tipo, id_idioma, issn, id_area) values(:tipo, :id_idioma, :issn, :id_area)"
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
        $revisiones = $this->_db->query("SELECT DISTINCT manuscrito.id_manuscrito, manuscrito.titulo, revista.nombre, rol.rol, estatus.estatus, revision.fecha, revision.id_revision ".
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

    // ============================================

    public function getResponsableManuscrito($id_manuscrito = false, $id_rol = false){
        $responsable = $this->_db->query("select * from responsable where id_manuscrito = $id_manuscrito and id_rol = $id_rol");
        return $responsable->fetchAll();
    }

    public function getDetallesEvaluacionArbitro($ids = false){
        $evaluaciones = $this->_db->query("select evaluacion.id_evaluacion, evaluacion.id_responsable, evaluacion.id_revision, evaluacion.evaluacion, evaluacion.fecha, persona.\"primerNombre\" || ' ' || persona.apellido as nombreCompleto ".
                        "from evaluacion, responsable, persona ".
                        "where evaluacion.id_responsable IN ($ids) and ".
                        "responsable.id_responsable = evaluacion.id_responsable ".
                        "and responsable.id_persona = persona.id_persona ".
                        "ORDER BY evaluacion.id_responsable, evaluacion.evaluacion DESC");

        return $evaluaciones->fetchAll();
    }

    public function getEvaluacionById($id_evaluacion){
        $evaluacion = $this->_db->query("SELECT e.sugerencia, e.cambios, e.evaluar_nuevamente ".
                                    "FROM evaluacion e ".
                                    "WHERE e.id_evaluacion = $id_evaluacion");

        return $evaluacion->fetch(PDO::FETCH_ASSOC);
    }

    public function getEvaluacionResult($id_evaluacion){
        $result = $this->_db->query("SELECT ed.id_pregunta, ed.id_opcion, p.pregunta, s.seccion, p.id_seccion, o.opcion, o.id_opcion ".
                                        "from evaluacion_detalle ed, pregunta p, seccion s, opcion o ".
                                        "where ed.id_evaluacion = $id_evaluacion and ed.id_pregunta = p.id_pregunta and p.id_seccion = s.id_seccion and o.id_opcion = ed.id_opcion");

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
//query 1
SELECT e.sugerencia, e.cambios, e.evaluar_nuevamente
FROM evaluacion e
WHERE e.id_evaluacion = 5


//query 2
SELECT ed.id_pregunta, ed.id_opcion, p.pregunta, s.seccion, p.id_seccion, o.opcion, o.id_opcion
from evaluacion_detalle ed, pregunta p, seccion s, opcion o
where ed.id_evaluacion = 12 and ed.id_pregunta = p.id_pregunta and p.id_seccion = s.id_seccion and o.id_opcion = ed.id_opcion

    */

    //=============================================

    // ----------------------------------

    public function getManuscritosParaAdmin(){
        $manuscrito = $this->_db->query("SELECT m.id_manuscrito, m.titulo, r.nombre, o.fecha FROM manuscrito m, revista r, obra o WHERE m.id_obra = o.id_obra and o.issn = r.issn order by r.issn");
        return $manuscrito->fetchAll();
    }

    public function getInfoManuscrito($id_manuscrito){
        $manuscrito = $this->_db->query("SELECT m.id_manuscrito, m.titulo, m.resumen, o.issn, o.id_idioma, o.id_area FROM manuscrito m, obra o WHERE m.id_manuscrito = $id_manuscrito and m.id_obra = o.id_obra");
        return $manuscrito->fetch();
    }

    public function getAutoresManuscrito($id_manuscrito){
        $persona = $this->_db->query("SELECT p.id_persona, p.\"primerNombre\" || ' ' || p.apellido as nombreCompleto ".
                                 "FROM responsable r, persona p ".
                                  "WHERE r.id_manuscrito = $id_manuscrito and r.id_persona = p.id_persona ".
                                  "and (r.id_rol = (select id_rol from rol where rol = 'Autor') or r.id_rol = (select id_rol from rol where rol = 'Co-Autor')) order by p.id_persona");
        return $persona->fetchAll();
    }

    public function eliminarAutores($id_manuscrito=false, $ids=false){
        if($ids && $id_manuscrito){
            $this->_db->query("DELETE FROM responsable WHERE id_manuscrito = $id_manuscrito and id_persona IN($ids)");
        }
    }

    // ----------------------------------

    public function getManuscritos(){
        $manuscrito = $this->_db->query("select * from manuscrito order by id_manuscrito DESC");
        return $manuscrito->fetchAll();
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

    public function getAutoresByManuscrito($id_manuscrito){

        $responsables = $this->_db->query("select * from responsable where id_manuscrito = $id_manuscrito and (id_rol = 4 or id_rol = 5)");

        $responsables = $responsables->fetchAll();

        $ids = '';

        for($i=0; $i<count($responsables); $i++){
            if($ids == '') $ids .= $responsables[$i]['id_persona'];
            else
                $ids .= ", " . $responsables[$i]['id_persona'];
        }

        $autores = $this->_db->query("select (p.\"primerNombre\" || ' ' || p.apellido) as nombreCompleto from persona p where id_persona IN ($ids) order by nombreCompleto");

        $autores = $autores->fetchAll();

        return $autores;
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
    
}


?>