<?php

class arbitroController extends Controller{
    public function __construct() {
        parent::__construct();
        $this->_persona = $this->loadModel('persona');
        $this->_manuscrito = $this->loadModel('manuscrito');
        $this->_rol = $this->loadModel("rol");
        $this->_arbitro = $this->loadModel("arbitro");
    }
    
    public function index($pagina = false){
        
        //validar si es arbitro
        
        if(!Session::get('autenticado')){
            header('location:' . BASE_URL . 'error/access/5050');
            exit;
        }else{

            $this->_view->setJs(array('js_index'));

            //temporalmenta hasta pensarlo bien...!
            Session::set('level', 'Arbitro');

            if(!$this->filtrarInt($pagina)){
               $pagina = false;
               $this->_view->pagina = 1;
           }else{
               $pagina = (int) $pagina;
               $this->_view->pagina = $pagina;
           }
            
            $this->getLibrary('paginador');
            $paginador = new Paginador();
            
            $id_persona = Session::get('id_person');

            $rolArbitro = $this->_rol->getIdRol("Arbitro");
            $manuscritos = $this->_manuscrito->getManuscritoEvaluar($id_persona, $rolArbitro[0]);
            if($manuscritos)
                $this->_view->manuscritos = $paginador->paginar($manuscritos, $pagina);
            else
                $this->_view->sin_manuscritos = "No tiene ninguna evaluaci&oacute;n pendiente";

            $this->_view->enlace = $this->getUrl("arbitro/evaluar/");

            $manuscritoActual = array();

            for($i = 0; $i<count($manuscritos); $i++){
                $responsable =  $this->_manuscrito->getResponsable($manuscritos[$i]['id_manuscrito']);
                $revision = $this->_manuscrito->getManuscritoActual($responsable['id_responsable']);

                $ubicacion = $this->_manuscrito->getManuscritoUbicacion($revision['id_fisico']);

                $manuscritoActual[] = BASE_URL . $ubicacion['carpeta'] . '/' . $ubicacion['nombre'];
            }

            $this->_view->descargar = $manuscritoActual;

            //consulta para obtener el autor del manuscrito
            //$autor_manuscrito =  $this->_manuscrito->getResponsable();


            $this->_view->paginacion = $paginador->getView('prueba', 'arbitro/index');

            $this->_view->titulo = '&Aacute;rbitro';
            $this->_view->renderizar('index', 'arbitro');
        }
        
    }

    public function solicitud($id_persona = false, $id_manuscrito = false, $codigo = false){

        $this->_view->setJs(array('js_solicitud'));

        if($id_persona && $id_manuscrito && $codigo){

            $result = arbitroController::validarSolicitud($id_persona, $id_manuscrito, $codigo);

            if($result != false){
                // var_dump("correcto");
                // var_dump($result);
                //titulo del manuscrito
                $manuscrito = $this->_manuscrito->getManuscrito($id_manuscrito);
                $this->_view->manuscrito = $manuscrito;

                //nombre del arbitro
                $persona = $this->_persona->getDatos($id_persona);
                $this->_view->persona = $persona;

                $this->_view->solicitud = $result;

                $this->_view->id_manuscrito = $id_manuscrito;

                $this->_view->id_persona = $id_persona;


            }else{
                //arreglar esto
                var_dump("Incorrecto");
            }

        }

        $this->_view->titulo = 'Solicitud de Arbitraje';
        $this->_view->renderizar('solicitud', 'arbitro');
    }

    public function validarSolicitud($id_persona = false, $id_manuscrito = false, $codigo = false){
        if($id_persona && $id_manuscrito && $codigo){
            return $this->_arbitro->validarSolicitud($id_persona, $id_manuscrito, $codigo);
        }

        return false;
    }

    public function respSolicitud(){
        $json = array();
        $json["estatus"] = 0;
        if($this->getInt('id_manuscrito') && $this->getInt('id_persona')){
            $this->_manuscrito->editarSolicitudArbitraje($this->getInt('id_persona'), $this->getInt('id_manuscrito'), $this->getInt('opcion'));
            $json["estatus"] = 1;
        }
        echo json_encode($json);
    }

    public function evaluar($id_manuscrito = null){
        if(!Session::get('autenticado')){
            header('location:' . BASE_URL . 'error/access/5050');
            exit;
        }else{
            if($id_manuscrito != null){
                $this->_view->setCssPublic(array('jquery-ui.min'));
                $this->_view->setCssPublic(array('jquery-ui.theme.min'));
                $this->_view->setJsPublic(array('jquery-ui.min'));
                $this->_view->setJs(array('js_evaluar'));

                // var_dump($_SESSION['id_persona']);




                $this->_view->id_manuscrito = $this->filtrarInt($id_manuscrito);

                $responsable = $this->_persona->getPersonaResponsable($_SESSION['id_person'], $this->filtrarInt($id_manuscrito));

                // var_dump($responsable);

                if($responsable['permiso'] == 0)
                    $this->redireccionar('arbitro');

                //obtener la revista de el manuscrito pasado por parametro
                $revista = $this->_arbitro->getRevistaManuscrito($this->filtrarInt($id_manuscrito));

                //obtener la plantilla dada una revista
                $plantilla = $this->_arbitro->getPlantillaRevista($revista);

                //obtener las secciones de la plantilla
                $plantilla_seccion = $this->_arbitro->getPlantillaSeccion($plantilla['id_plantilla']);
                // var_dump($plantilla_seccion);

                $secciones = '';

                for($i = 0; $i < count($plantilla_seccion); $i++){
                    if($i == 0)
                        $secciones .= $plantilla_seccion[$i]['id_seccion'];
                    else
                        $secciones .= ", " . $plantilla_seccion[$i]['id_seccion'];
                }

                //secciones
                $seccion = $this->_arbitro->getSecciones($secciones);
                $this->_view->_seccion = $seccion;
                // var_dump($seccion);

                //obtener pregunstas dado una plantilla y seccion
                $pregunstas_seccion_2 = $this->_arbitro->getPreguntas($plantilla['id_plantilla'], 2);
                $this->_view->_pregunstas_seccion_2 = $pregunstas_seccion_2;

                // var_dump("=========================================");
                // var_dump($pregunstas_seccion_2);
                // var_dump("=========================================");

                $pregunstas_seccion_3 = $this->_arbitro->getPreguntas($plantilla['id_plantilla'], 3);
                $this->_view->_pregunstas_seccion_3 = $pregunstas_seccion_3;

                // var_dump($pregunstas_seccion_3);

                //obtener las opciones de cada pregunta
                $opciones_seccion_2 = $this->_arbitro->getOpciones(2);
                $this->_view->_opciones_seccion_2 = $opciones_seccion_2;

                $opciones_seccion_3 = $this->_arbitro->getOpciones(3);
                $this->_view->_opciones_seccion_3 = $opciones_seccion_3;

                // var_dump($opciones_seccion_2);
                // var_dump($opciones_seccion_3);

                $responsable = $this->_manuscrito->getResponsable($this->filtrarInt($id_manuscrito));
                // var_dump($responsable);

                $primeraRevision = $this->_manuscrito->getPrimeraRevision($responsable['id_responsable']);
                // var_dump($primeraRevision);
                $this->_view->_datos_revision = $primeraRevision;

                $datos_manuscrito = $this->_manuscrito->getManuscrito($this->filtrarInt($id_manuscrito));
                $this->_view->_datos_manuscrito = $datos_manuscrito;

                $this->_view->titulo = 'Evaluaci&oacute;n';
                $this->_view->renderizar('evaluar', 'arbitro');
            }else{
                $this->redireccionar('arbitro');
            }
        }
    }

    public function prueba(){
                $responsable = $this->_persona->getPersonaResponsable($_SESSION['id_person'], 13);

                // $num_evaluaciones = $this->_arbitro->getNumEvaluaciones($responsable['id_responsable']);
                // $evaluaciones = 0;
                // if($num_evaluaciones === false)
                //     $evaluaciones =  1;
                // else if($num_evaluaciones)
                //     $evaluaciones += $num_evaluaciones['evaluacion'] + 1;

                $this->_arbitro->setEvaluacion($responsable['id_responsable'], 4, 1,  null, "asdddddd", "ttttttt");

                $lastEvaluacion = $this->_arbitro->getLastEvaluacion();

                //seccion 2
                for($i = 0; $i<10; $i++){
                    $this->_arbitro->setEvaluacionDetalles($lastEvaluacion['id_evaluacion'], $this->filtrarInt($_POST['pregunta_'.$i]), $this->filtrarInt($_POST['seccion2_opcion_'.$i]));
                }

                //seccion 3
                 $this->_arbitro->setEvaluacionDetalles($lastEvaluacion['id_evaluacion'], $this->filtrarInt($_POST['preguntaSeccion3']), $this->filtrarInt($_POST['seccion3_opcion']));

                 //set permiso en 0
                 $this->_persona->setPermisoResponsable($responsable['id_responsable'], 0);
    }

    public function enviarEvaluacion(){
        $arreglo["status"] = 1;

        $comentario = $this->getSql('comentario');
        $sugerencia = $this->getSql('sugerencia');
        //$cambios = $this->getSql('cambios');
        $evaluar = $this->getInt('evaluar');


        $responsable = $this->_manuscrito->getResponsable($this->filtrarInt($_POST['manuscrito']));

        $datos_persona = $this->_persona->getDatos($responsable['id_persona']);

        $apellido = explode(" ", $datos_persona['apellido']);
        $ruta = "manuscritos";
        $ruta .= '/' . $apellido[0] . "_" .$responsable['id_persona'];
        $ruta .= "/manuscrito_". $this->filtrarInt($_POST['manuscrito']);

        $count_fisico = $this->_manuscrito->getCountFisico();
        //$count_fisico = 0;
        $control_arch = 0;
        
        if((int)$count_fisico['count_fisico'] == 0){
            $control_arch = 1;
        }else{
            $control_arch = (int)$count_fisico['count_fisico'] + 1;
        }

        if(isset($_FILES["archivo"])){

            //upload file
            $fileName = $control_arch . "_" . $_FILES["archivo"]["name"]; 

            // The file name 
            $fileTmpLoc = $_FILES["archivo"]["tmp_name"]; // File in the PHP tmp folder 
            $fileType = $_FILES["archivo"]["type"]; // The type of file it is 
            $fileSize = $_FILES["archivo"]["size"]; // File size in bytes 
            $fileErrorMsg = $_FILES["archivo"]["error"]; // 0 for false... and 1 for true 
            if (!$fileTmpLoc) { 
                    // if file not chosen 
                    //echo "ERROR: Please browse for a file before clicking the upload button."; 
                $arreglo["msj_file"] = "Error: Debe seleccionar un archivo";
                $arreglo["status"] = 0;
                exit(); 
            } 
            if(move_uploaded_file($fileTmpLoc, $ruta."/".$fileName)){ 
                    //echo "$fileName upload is complete";
                $arreglo["msj_file"] = "$fileName Subida completada";
                
                $this->_manuscrito->setFisico($ruta, $fileName);

                $fisico = $this->_manuscrito->getUltimoFisico();

                $responsable = $this->_persona->getPersonaResponsable($_SESSION['id_person'], $this->filtrarInt($_POST['manuscrito']));

                $num_evaluaciones = $this->_arbitro->getNumEvaluaciones($responsable['id_responsable']);
                $evaluaciones = 0;
                if($num_evaluaciones === false)
                    $evaluaciones =  1;
                else if($num_evaluaciones)
                    $evaluaciones += $num_evaluaciones['evaluacion'] + 1;

                $this->_arbitro->setEvaluacion($responsable['id_responsable'], $evaluaciones, $evaluar,  $fisico['id_fisico'], $sugerencia, $comentario);

                $lastEvaluacion = $this->_arbitro->getLastEvaluacion();

                //seccion 2
                for($i = 0; $i<10; $i++){
                    $this->_arbitro->setEvaluacionDetalles($lastEvaluacion['id_evaluacion'], $this->filtrarInt($_POST['pregunta_'.$i]), $this->filtrarInt($_POST['seccion2_opcion_'.$i]));
                }

                //seccion 3
                 $this->_arbitro->setEvaluacionDetalles($lastEvaluacion['id_evaluacion'], $this->filtrarInt($_POST['preguntaSeccion3']), $this->filtrarInt($_POST['seccion3_opcion']));

                 //set permiso en 0
                 $this->_persona->setPermisoResponsable($responsable['id_responsable'], 0);
                
                
                
            } else { 
            //echo "move_uploaded_file function failed"; 
                $arreglo["msj_file"] = "Error al subir el archivo";
                $arreglo["status"] = 0;
            }
        }else{

                // $this->_manuscrito->setFisico($ruta, $fileName);

                // $fisico = $this->_manuscrito->getUltimoFisico();

                $responsable = $this->_persona->getPersonaResponsable($_SESSION['id_person'], $this->filtrarInt($_POST['manuscrito']));

                $num_evaluaciones = $this->_arbitro->getNumEvaluaciones($responsable['id_responsable']);
                $evaluaciones = 0;
                if($num_evaluaciones === false)
                    $evaluaciones =  1;
                else if($num_evaluaciones)
                    $evaluaciones += $num_evaluaciones['evaluacion'] + 1;

                $this->_arbitro->setEvaluacion($responsable['id_responsable'], $evaluaciones, $evaluar,  null, $sugerencia, $comentario);

                $lastEvaluacion = $this->_arbitro->getLastEvaluacion();

                //seccion 2
                for($i = 0; $i<10; $i++){
                    $this->_arbitro->setEvaluacionDetalles($lastEvaluacion['id_evaluacion'], $this->filtrarInt($_POST['pregunta_'.$i]), $this->filtrarInt($_POST['seccion2_opcion_'.$i]));
                }

                //seccion 3
                 $this->_arbitro->setEvaluacionDetalles($lastEvaluacion['id_evaluacion'], $this->filtrarInt($_POST['preguntaSeccion3']), $this->filtrarInt($_POST['seccion3_opcion']));

                 //set permiso en 0
                 $this->_persona->setPermisoResponsable($responsable['id_responsable'], 0);

        }
        //var_dump($arreglo);
        echo json_encode($arreglo);

    }
    
}
?>