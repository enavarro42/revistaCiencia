<?php

class arbitroModel extends Model{
    public function __construct(){
        parent::__construct();
    }

    public function validarSolicitud($id_persona, $id_manuscrito, $codigo){
        $arbitro = $this->_db->query("select * from arbitros_manuscrito where id_manuscrito = $id_manuscrito and id_persona = $id_persona and codigo = $codigo");
        
        return $arbitro = $arbitro->fetch();

    }

    public function getRevistaManuscrito($id_manuscrito){
        $manuscrito = $this->_db->query("select * from manuscrito where id_manuscrito = $id_manuscrito");
        $result = $manuscrito->fetch();

        $obra = $this->_db->query("select * from obra where id_obra = ". $result['id_obra']);
        $result = $obra->fetch();

        return $result['issn'];
    }

    public function getPlantillaRevista($issn){
        $plantilla = $this->_db->query("select * from plantilla where issn = '$issn'");
        return $plantilla->fetch();
    }

    public function getPlantillaSeccion($id_plantilla){
        $plantilla = $this->_db->query("select * from plantilla_seccion where id_plantilla = $id_plantilla order by id_seccion");
        return $plantilla->fetchAll();
    }

    public function getSecciones($seccion){
        $secciones = $this->_db->query("select * from seccion where id_seccion IN ($seccion) order by id_seccion");
        return $secciones->fetchAll();
    }

    public function getPreguntas($id_plantilla, $id_seccion){
        $preguntas = $this->_db->query("select * from pregunta where id_plantilla = $id_plantilla and id_seccion = $id_seccion");
        return $preguntas->fetchAll();
    }

    public function getOpciones($id_seccion){
        $opciones = $this->_db->query("select * from opcion where id_seccion = $id_seccion");
        return $opciones->fetchAll();
    }

    public function getNumEvaluaciones($id_responsable){
        $evaluaciones = $this->_db->query("select * from evaluacion where id_responsable = $id_responsable order by evaluacion DESC LIMIT 1");
        if($evaluaciones === false)
            return false;
        return $evaluaciones->fetch();
    }

    public function setEvaluacion($id_responsable, $num_evaluaciones, $evaluar,  $id_fisico, $sugerencia, $comentario){
         $this->_db->prepare("INSERT INTO evaluacion(id_responsable, id_revision, evaluacion, fecha, evaluar_nuevamente, id_fisico, sugerencia, comentario) VALUES(:id_responsable, :id_revision, :num_evaluaciones, current_timestamp, :evaluar,  :id_fisico, :sugerencia, :comentario )")
         ->execute(
                 array(
                     ":id_responsable" => $id_responsable,
                     ":id_revision" => null,
                     ":num_evaluaciones" => $num_evaluaciones,
                     ":evaluar" => $evaluar,
                     ":id_fisico" => $id_fisico,
                     ":sugerencia" => $sugerencia,
                     ":comentario" => $comentario
                 ));
    }

    public function getLastEvaluacion(){
        $ultimoRegistro = $this->_db->query("select * from evaluacion order by id_evaluacion DESC LIMIT 1");
        return $ultimoRegistro->fetch();
    }

    public function setEvaluacionDetalles($id_evaluacion, $id_pregunta, $id_opcion){
        $this->_db->prepare("INSERT INTO evaluacion_detalle(id_evaluacion, id_pregunta, id_opcion) VALUES(:id_evaluacion, :id_pregunta, :id_opcion)")
         ->execute(
                 array(
                     ":id_evaluacion" => $id_evaluacion,
                     ":id_pregunta" => $id_pregunta,
                     ":id_opcion" => $id_opcion
                 ));
    }
    
}

?>