<?php

class manuscritoController extends Controller{
    
    private $_manuscrito;
    private $_registro;
    private $_persona;
    
    public function __construct(){
        parent::__construct();
        $this->_manuscrito = $this->loadModel('manuscrito');
        $this->_registro = $this->loadModel('registro');
        $this->_persona = $this->loadModel("persona");
        $this->_rol = $this->loadModel("rol");
    }
    
    public function index($pagina = false){
        
        Session::accesoEstricto(array('Autor'));
        
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
        
        $misManuscritos = $this->_manuscrito->getManuscritosPersona($id_persona);
        //var_dump($misManuscritos);
        if($misManuscritos)
            $this->_view->manuscritos = $paginador->paginar($misManuscritos, $pagina);
        else
            $this->_view->sin_manuscritos = "No se encontraron manuscritos";
        
        //$this->_view->pagina = $pagina;
        
        $this->_view->paginacion = $paginador->getView('prueba', 'manuscrito/index');
        
        $this->_view->titulo = 'Manuscritos';
        $this->_view->renderizar('index', 'manuscrito');
    }
    
    //Modificar este metodo para que solo muestre el historial de un manuscrit en particular
    
    public function misManuscritos($id_manuscrito = false, $pagina = false){
        
        Session::accesoEstricto(array('Autor'));
        if($id_manuscrito != false && $this->filtrarInt($id_manuscrito) != 0){
            $responsable = $this->_persona->getAutorCorrespondencia($id_manuscrito);
            if($responsable){ //validacion para saber si la persona que inicio sesion es responsable del manuscrito solicitado 
            
                if(!$this->filtrarInt($pagina)){
                    $pagina = false;
                }else{
                    $pagina = (int) $pagina;
                }

                $this->getLibrary('paginador');
                $paginador = new Paginador();


                // $id_persona = Session::get('id_persona');

                //$obras = $this->_manuscrito->getObraManuscrito($id_persona);

                //$autores = array();
                $this->_view->autores = array();

                
                    
                $manusc = $this->_manuscrito->getManuscrito($id_manuscrito); // obtenemos el manuscrito
                if($manusc){     
                    $autorManuscrito = $this->_manuscrito->getAutorManuscrito($manusc['id_manuscrito']); //obtenemos el id_persona
                    $iter = 0;
                    while($fila = $autorManuscrito->fetch()){//obtenemos el id_persona
                        $id = $fila['id_persona']; //lo asignamos
                        $datos_persona = $this->_manuscrito->getPersona($id);
                        $array_autor[]['persona_'.$iter] = $datos_persona['primerNombre'] . " " . $datos_persona['apellido']; //almaceno los autores
                        // $array_autor['id_persona_'.$iter] = $datos_persona['id_persona'];

                        $iter++;
                    }
                    $this->_view->autores[$manusc['id_manuscrito']] = $array_autor;
                    $this->_view->id_manuscrito = (int)$id_manuscrito;
                    //$autores[$manusc['id_manuscrito']] = $array_autor;
                }else{
                    header('location:' . BASE_URL . 'error/access/404');
                    exit;
                }

                //echo '<pre>';
                //print_r($autores);

               // echo "contador = " . count($autores) . "<br />";


                //$this->_view->revisiones = $this->_manuscrito->getRevisiones($autor['id_autor']);

                $result = $this->_manuscrito->getRevisiones($manusc['id_manuscrito']);

                $this->_view->manuscritos = $paginador->paginar($result, $pagina);

                $this->_view->enlaceDetalles = $this->getUrl("manuscrito/detallesManuscrito/");
                //print_r($this->_view->revisiones);

                //exit;

                $this->_view->paginacion = $paginador->getView('prueba', 'manuscrito/misManuscritos/'.$id_manuscrito);

                //estatus para activar el boton de correccion
                $array_claves = array('modificacionesSustanciales', 'ligerasModificaciones', 'corregirFormato');

                //estatus actual
                $estatus_actual = $result[0]["id_estatus"];

                //detalles del estatus actual
                $detalles_estatus = $this->_manuscrito->getEstatusById($estatus_actual);

                //var_dump(trim($detalles_estatus["clave"]));


                if(in_array(trim($detalles_estatus["clave"]) , $array_claves)){
                    $this->_view->enlaceCorreccion = $this->getUrl("manuscrito/correccion/");
                    $this->_view->id_manuscrito = $manusc['id_manuscrito'];
                    $this->_view->id_estatus = $estatus_actual;
                    $this->_view->id_revision = $result[0]["id_revision"];
                }

                $this->_view->titulo = 'Mis manuscritos';
                $this->_view->renderizar('misManuscritos', 'manuscrito');
            }else{
                $this->redireccionar('manuscrito');
            }
        }
        
    }

    public function detallesManuscrito($id_revision = false, $id_manuscrito = false){

        $revision = $this->_manuscrito->getRevisionById($id_revision);

        if($revision && $id_revision){

            $this->_view->revision = $revision;

            $manuscrito = $this->_manuscrito->getManuscrito($id_manuscrito);

            $estatusPorEditor = $this->_manuscrito->getEstatusByClave('corregirFormato');

            $estatusPorArbitro = $this->_manuscrito->getEstatusByTipo('evaluacionArbitro');

            $estatus = $this->_manuscrito->getEstatus($revision['id_estatus']);

            if($revision['id_estatus'] == $estatusPorEditor['id_estatus']){
                $this->_view->estatusEvaluacionEditor = true;
            }else{
                $this->_view->estatusEvaluacionEditor = false;
            }

            $this->_view->estatusEvaluacionArbitro = false;

            $detallesEvaluacion = null;

            for($i = 0; $i<count($estatusPorArbitro); $i++){
                if($estatusPorArbitro[$i]['id_estatus'] == $revision['id_estatus']){
                    $this->_view->estatusEvaluacionArbitro = true;
                    $detallesEvaluacion = $this->_manuscrito->getEvaluacionesByRevision($id_revision);
                    break;
                }
            }

            $data = array();
            $data['titulo'] = $manuscrito['titulo'];
            $data['estatus'] = $estatus['estatus'];
            $data['fecha'] = $revision['fecha'];

            if($this->getInt($revision['id_fisico'])){
                $fisico = $this->_manuscrito->getFisico($this->getInt($revision['id_fisico']));
                $data['link_archivo'] = $fisico['carpeta'] . '/' . $fisico['nombre'];
            }

            for($i = 0; $i<count($detallesEvaluacion); $i++){

                $id_fisico = $detallesEvaluacion[$i]['id_fisico'];
                $fisico = $this->_manuscrito->getFisico($id_fisico);
                $data['evaluacion'][] = array('sugerencia' => $detallesEvaluacion[$i]['sugerencia'],
                    'link_archivo' => $fisico['carpeta'] . '/' . $fisico['nombre']);
            }

            $this->_view->data = $data;

            $this->_view->titulo = 'Detalles del Manuscrito';
            $this->_view->renderizar('detallesManuscrito', 'manuscrito');
        }else{
            $this->redireccionar('manuscrito');
        }
    }
    
    public function insertar(){
        
        //validar
        Session::accesoEstricto(array('Autor'));
        $arreglo = array();
        $arreglo["status"] = 1;
        $arreglo["problema"] = "";
        $arreglo["post"] = $_POST;

        $rolAutor = $this->_rol->getIdRol("Autor");
        $rolCoAutor = $this->_rol->getIdRol("Co-Autor");
        
        for($i = 0; $i < $_POST["iter"]; $i++){
            
            if((int)$_POST["idx_autor"] != $i){
            
                $datos["primerNombre"] = "";
                $datos["segundoNombre"] = "";
                $datos["apellido"] = "";
                $datos["pais"] = "";
                $datos["telefono"] = "";
                $datos["idx"] = $i;
                
                //Nombre
                
                if(!$this->getSql('primerNombre'.$i)){
                    $datos["nombre"] = 'Debe introducir su nombre';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 1 " ;
                }

                if(!(strlen($this->getSql('primerNombre'.$i)) > 2)){
                    $datos["nombre"] = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 2 " ;
                }

                $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

                if(!preg_match($exp, $this->getPostParam('primerNombre'.$i))){
                    $datos["nombre"] = 'Nombre inv&aacute;lido';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 3 " ;
                }
                
                //Apellido 

                if(!$this->getSql('apellido'.$i)){
                    $datos["apellido"] = 'Debe introducir su apellido';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 4 " ;
                }

                if(!(strlen($this->getSql('apellido'.$i)) > 2)){
                    $datos["apellido"] = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 5 " ;
                }

                $test_apellido1 = true;
                $test_apellido2 = true;

                $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

                if(!preg_match($exp, $this->getPostParam('apellido'.$i))){
                    // $datos["apellido"] = 'Apellido inv&aacute;lido';
                    $test_apellido1 = false;
                }


                $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
                
                
                if(!preg_match($exp, $this->getPostParam('apellido'.$i))){
                    // $this->_view->_error_apellido = 'Apellido inv&aacute;lido';
                    // $validado = false;
                    $test_apellido2 = false;
                }

                if($test_apellido1 == false && $test_apellido2 == false){
                    $datos["apellido"] = 'Apellido inv&aacute;lido';
                    $validado = false;
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 6 " ;
                }
                
                //email 
                if(!$this->validarEmail($this->getPostParam('email'.$i))){
                    $datos["email"] = 'La direccion de email es inv&aacute;lida';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 7 " ;
                }

                if($this->_registro->verificarEmail($this->getPostParam('email'.$i))){
                    $datos["email"] = 'Esta direcci&oacute;n de email ya est&aacute; registrada';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 8 " ;
                }
                
                
                
                //pais
                if($this->getInt('pais'.$i) == 0){
                    $datos["pais"] = 'Debe seleccionar un pais';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 8 " ;
                }
                
                //telefono
                $exp = '/^\\d{11,14}$/';
            
                if(!preg_match($exp, $this->getPostParam('telefono'.$i))){
                    $datos["telefono"] = 'Debe proporcionar un n&uacute;mero de tel&eacute;fono v&aacute;lido';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 9 " ;
                }
                
                //titulo
                
                $datos["titulo"] = "";
                if(!$this->getSql('titulo')){
                    $datos["titulo"] = 'Debe introducir el titulo';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 10 " ;
                }
                
                //resumen
                
                $datos["resumen"] = "";
                if(!$this->getSql('resumen')){
                    $datos["resumen"] = 'Debe introducir el resumen';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 11 " ;
                }
                
                //revista
                
                if($this->getPostParam('revista') == 0){
                    $datos["revista"] = 'Debe seleccionar una revista';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 12 " ;
                }
                
                //area
                
                if($this->getInt('area') == 0){
                    $datos["area"] = 'Debe seleccionar un &aacute;rea';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 13 " ;
                }
                
                //idioma
                
                if($this->getInt('idioma') == 0){
                    $datos["idioma"] = 'Debe seleccionar un idioma';
                    $arreglo["status"] = 0;
                    $arreglo["problema"] += $arreglo["problema"] . " 14 " ;
                }
                
                //palabrasClave
                
                if(!$this->getSql('palabrasClave')){
                    $datos["palabrasClave"] = 'Debe introducir palabras claves';
                    $arreglo["status"] = 0; 
                    $arreglo["problema"] += $arreglo["problema"] . " 15 " ;
                }
                
                $arreglo["data"][] = $datos;

            }

        }
        
        if($arreglo['status']){
            
            //insertar autores en bd
                $arreglo["obra"] = $this->getInt('idioma') . " - " . $this->getSql('revista') ." - ". $this->getInt('area');
                $this->_manuscrito->setObra($this->getInt('idioma'), $this->getSql('revista'), $this->getInt('area'));
                $obra = $this->_manuscrito->getUltimaObra();
                
                //set manuscrito
                $this->_manuscrito->setManuscrito($this->getSql('titulo'), $this->getSql('resumen'), $obra['id_obra'], $this->getSql('palabrasClave'));
                $manuscrito = $this->_manuscrito->getManuscritoObra($obra['id_obra']);
                
                $responsable = 0;
                $persona = 0;
                
                for($i = 0; $i < $_POST["iter"]; $i++){

                    $arreglo["datos".$i] = $this->getSql('primerNombre'.$i) . " - ".
                        $this->getSql('apellido'.$i). " - ".
                        $this->getPostParam('genero'.$i). " - ".
                        $this->getPostParam('email'.$i). " - ".
                        $this->getPostParam('telefono'.$i). " - ".
                        $this->getInt('pais'.$i). " - " .
                        $this->getSql('segundoNombre'.$i);

                    $permiso = 0;
                    //si son autores nuevos
                    if((int)$_POST["idx_autor"] != $i){
                        $this->_registro->setPersona($this->getSql('primerNombre'.$i), 
                                $this->getSql('apellido'.$i),
                                "", 
                                $this->getPostParam('genero'.$i), 
                                $this->getPostParam('email'.$i), 
                                $this->getPostParam('telefono'.$i), 
                                $this->getInt('pais'.$i),
                                "",
                                "",
                                $this->getSql('segundoNombre'.$i));
                        
                        $persona = $this->_registro->getUltimaPersona();
                        
                        
                        
                        if((int)$_POST["autorPrincipal"] == $i){
                            $this->_registro->setPersonaRol($persona['id_persona'], $rolAutor[0]);

                            $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$persona['id_persona'], $rolAutor[0], $permiso, 0);

                                // $responsable = $this->_manuscrito->getUltimoResponsable();
                        }else{
                            $this->_registro->setPersonaRol($persona['id_persona'], $rolCoAutor[0]);
                            $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$persona['id_persona'], $rolCoAutor[0], $permiso, 0);
                        }
                        
                    }
                    
                    //si es el autor de la cuenta
                        
                    if((int)$_POST["idx_autor"] == $i){
                        
                        $permiso = 0;
                        // $this->_persona->setCorrespondencia($_SESSION["id_persona"],$manuscrito['id_manuscrito'], $permiso);

                        if((int)$_POST["autorPrincipal"] == $i){
                        
                            if(isset($_SESSION["id_person"])){
                                $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$_SESSION["id_person"], $rolAutor[0], $permiso, 1);
                                $responsable = $this->_manuscrito->getUltimoResponsable();
                            }
                        }else{
                                $this->_registro->setPersonaRol($persona['id_persona'], $rolCoAutor[0]);
                                $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$_SESSION["id_person"], $rolCoAutor[0], $permiso, 1);
                                //esta variabla responsable guarda el autor para la correspondencia
                                $responsable = $this->_manuscrito->getUltimoResponsable();

                        }
                            
                    }

                }
                
             
            
            
            //---------------------------------------

            $datos_persona = $this->_persona->getDatos($_SESSION["id_person"]);

            $apellido = explode(" ", $datos_persona['apellido']);
            
            $ruta = "manuscritos";
            
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777);
            }
            $ruta .= '/' . $apellido[0] . "_" .$_SESSION["id_person"];

            if(!file_exists($ruta)){
                mkdir($ruta, 0777);
            }

            if(file_exists($ruta)){
                $ruta.= "/manuscrito_". $manuscrito["id_manuscrito"];
                mkdir($ruta, 0777);
            }
            
            $arreglo["id_manuscrito"] = $manuscrito["id_manuscrito"];
            
            $arreglo["ruta"] = $ruta;
            
            $count_fisico = $this->_manuscrito->getCountFisico();
            //$count_fisico = 0;
            $control_arch = 0;
            
            if((int)$count_fisico['count_fisico'] == 0){
                $control_arch = 1;
            }else{
                $control_arch = (int)$count_fisico['count_fisico'] + 1;
            }

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
                $estatus = $this->_manuscrito->getEstatusPorNombre("Enviado");

                $this->_manuscrito->setRevision($responsable['id_responsable'], $estatus['id_estatus'], $fisico['id_fisico']);
                
                
            } else { 
            //echo "move_uploaded_file function failed"; 
                $arreglo["msj_file"] = "Error al subir el archivo";
                $arreglo["status"] = 0;
            }
            
            
            
        }
        
        echo json_encode($arreglo);
        
    }

    public function correccion(){

        // if(isset($_POST["enviado"])){
        //     $ids = $_POST["ids"];
        //     $texto = $_POST["texto"];
        //     for($i = 0; $i<count($ids); $i++){
        //         //var_dump("id -> " . $ids[$i]);
        //         //var_dump("texto-> " . $texto[$i]);
        //     }
        // }

        $id_manuscrito = $this->getInt("id_manuscrito");
        $id_revision = $this->getInt("id_revision");
        $id_estatus = $this->getInt("id_estatus");

        //var_dump($id_revision);

        if($id_manuscrito && $id_estatus && $id_estatus){

            Session::accesoEstricto(array('Autor'));
            $this->_view->setJs(array('js_correccion'));

            $rolAutor = $this->_rol->getIdRol("Autor");
            if($id_manuscrito != false){

                $persona = null;
                $persona = $this->_manuscrito->validarResponsable($this->filtrarInt($id_manuscrito), $_SESSION['id_person'], $rolAutor[0]);

                //comprobamos q ese manuscrito es de ese usuario, y q tiene permisos para corregir
                if($persona && $persona['permiso'] != 0){ 

                    $this->_view->id_manuscrito = $id_manuscrito;


                    $estatus = $this->_manuscrito->getEstatusById($id_estatus);

                    $tipo = 0;
                    if(trim($estatus["clave"]) == "corregirFormato"){
                        $tipo = 1;

                        // si es este tipo seguir sin hacer mas nada

                    }else if(trim($estatus["clave"]) == "ligerasModificaciones" || trim($estatus["clave"]) == "modificacionesSustanciales"){
                        $tipo = 2;

                        $evaluaciones = $this->_manuscrito->getEvaluacionesByRevision($id_revision);

                        $this->_view->evaluaciones = $evaluaciones;

                        //var_dump($evaluaciones);
                    }else{
                        $this->redireccionar('manuscrito');
                    }

                    $this->_view->tipo = $tipo;


                    $this->_view->titulo = 'Correcci&oacute;n';
                    $this->_view->renderizar('correccion', 'manuscrito');



                }else{
                    $this->redireccionar('manuscrito');
                }

            }else{
                $this->redireccionar('manuscrito');
            }
        }

    }

    public function enviarCorreccion(){

            $arreglo["status"] = 1;

            $datos_persona = $this->_persona->getDatos($_SESSION["id_person"]);
            $apellido = explode(" ", $datos_persona['apellido']);
            $ruta = "manuscritos";
            $ruta .= '/' . $apellido[0] . "_" .$_SESSION["id_person"];
            $ruta .= "/manuscrito_". $_POST['manuscrito'];

            $count_fisico = $this->_manuscrito->getCountFisico();
            //$count_fisico = 0;
            $control_arch = 0;
            
            if((int)$count_fisico['count_fisico'] == 0){
                $control_arch = 1;
            }else{
                $control_arch = (int)$count_fisico['count_fisico'] + 1;
            }

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

                $arreglo["post"] = $_POST;

                //si es la correccion del arbitro establecer las respuestas a cada carreccion
                if((int)$_POST["tipo"] == 2){

                    $id_manuscrito = $_POST["manuscrito"];
                    $cont = (int) $_POST["contador"];

                    $arreglo["cont"] = $cont;

                    $resp = $this->_manuscrito->getResponsable($id_manuscrito);

                    for($i=0; $i<$cont; $i++){
                        $this->_manuscrito->setRespuestaEvaluacion($_POST["id_".$i], $resp["id_responsable"], $_POST["resp_".$i]);
                    }

                }

                    //echo "$fileName upload is complete";
                $arreglo["msj_file"] = "$fileName Subida completada";
                
                $this->_manuscrito->setFisico($ruta, $fileName);

                $fisico = $this->_manuscrito->getUltimoFisico();

                $responsable = $this->_persona->getAutorCorrespondencia($_POST['manuscrito']);
                
                $estatus = $this->_manuscrito->getEstatusByClave("correccionManuscrito");
                $this->_manuscrito->setRevision($responsable['id_responsable'], $estatus['id_estatus'], $fisico['id_fisico']);
                
                $this->_persona->setPermisoResponsable($responsable['id_responsable'], 0);

                //--------------------------------------------
                $rolArbitro = $this->_rol->getIdRol("Arbitro");

                $resp = $this->_manuscrito->getArbitrosByManuscrito($_POST['manuscrito'], $rolArbitro[0]);

                $this->getLibrary('class.phpmailer');
                $mail = new PHPMailer();

                $manuscrito = $this->_manuscrito->getManuscrito($_POST['manuscrito']);

                for($i = 0; $i<count($resp); $i++){
                     $this->_persona->setPermisoResponsable($resp[$i]['id_responsable'], 1);

                    // get persona by responsable
                    // obtener el correo y enviar

                    $emails = $this->_persona->getEmailByResponsableId($resp[$i]['id_responsable']);

                    $mail->From = 'www.fecRevistasCientificas.com';
                    $mail->FromName = 'Revistas Cientificas';
                    $mail->Subject = 'Revistas FEC';
                    $mail->Body = "El autor ha realizado la corrección para el manuscrito titulado: <strong>". $manuscrito["titulo"] . "</strong>";
                    $mail->AltBody = "Su servidor de correo no soporta html";
                    $mail->addAddress($emails[$i]["email"]);
                    $mail->Send();

                }
                    
                
            } else { 
            //echo "move_uploaded_file function failed"; 
                $arreglo["msj_file"] = "Error al subir el archivo";
                $arreglo["status"] = 0;
            }

            echo json_encode($arreglo);

    }
    
    
    public function uploadFile(){
        
        $this->getLibrary("upload/class.upload");
        
        $dir_dest = "test";
        $dir_pics = "test";
            // ---------- XMLHttpRequest UPLOAD ----------

        // we first check if it is a XMLHttpRequest call
        if (isset($_SERVER['HTTP_X_FILE_NAME']) && isset($_SERVER['CONTENT_LENGTH'])) {

            // we create an instance of the class, feeding in the name of the file
            // sent via a XMLHttpRequest request, prefixed with 'php:'
            $handle = new Upload('php:'.$_SERVER['HTTP_X_FILE_NAME']);  

        } else {  
            // we create an instance of the class, giving as argument the PHP object
            // corresponding to the file field from the form
            // This is the fallback, using the standard way
            $handle = new Upload($_FILES['archivo']);       
        }

        // then we check if the file has been uploaded properly
        // in its *temporary* location in the server (often, it is /tmp)
        if ($handle->uploaded) {

            // yes, the file is on the server
            // now, we start the upload 'process'. That is, to copy the uploaded file
            // from its temporary location to the wanted location
            // It could be something like $handle->Process('/home/www/my_uploads/');
            $handle->Process($dir_dest);

            // we check if everything went OK
            if ($handle->processed) {
                // everything was fine !
                echo '<p class="result">';
                echo '  <b>File uploaded with success</b><br />';
                echo '  File: <a href="'.$dir_pics.'/' . $handle->file_dst_name . '">' . $handle->file_dst_name . '</a>';
                echo '   (' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB)';
                echo '</p>';
            } else {
                // one error occured
                echo '<p class="result">';
                echo '  <b>File not uploaded to the wanted location</b><br />';
                echo '  Error: ' . $handle->error . '';
                echo '</p>';
            }

            // we delete the temporary files
            $handle-> Clean();

        } else {
            // if we're here, the upload file failed for some reasons
            // i.e. the server didn't receive the file
            echo '<p class="result">';
            echo '  <b>File not uploaded on the server</b><br />';
            echo '  Error: ' . $handle->error . '';
            echo '</p>';
        }
        
    }
    
    public function nuevo(){
        
        Session::accesoEstricto(array('Autor'));
        
        $this->_view->setCssPublic(array('jquery-ui'));
        
        
        //$this->_view->setJsPublic(array('jquery.ui.core'));
        //$this->_view->setJsPublic(array('jquery.ui.tabs'));
       // $this->_view->setJsPublic(array('jquery.ui.widget'));
        $this->_view->setJsPublic(array('jquery-ui'));
        
        //$this->_view->setJsPublic(array('jquery.form.min'));
        
        $this->_view->setJs(array('js_tabs'));
        
        
        
        $this->_view->titulo = 'Nuevo Manuscrito';
        $this->_view->renderizar('nuevo', 'manuscrito');
    }

    
}

?>