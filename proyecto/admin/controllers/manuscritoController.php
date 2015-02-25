<?php

class manuscritoController extends Controller{
    
    private $_manuscrito;
    private $_usuario;
    private $_ajax;
    private $_rol;
    private $_persona;

    public function __construct(){
        parent::__construct();
        $this->_manuscrito = $this->loadModel('manuscrito');
        $this->_usuario = $this->loadModel('usuario');
        $this->_ajax = $this->loadModel('ajax');
        $this->_rol = $this->loadModel("rol");
        $this->_persona = $this->loadModel("persona");
    }
    
    public function index($pagina = false){

        $view_key = "ver_manuscrito";

        if(!Session::get('autenticado_admin')){
            $this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();


        if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
            $this->redireccionar('acceso');
        }

        //verificar si tiene permisos para eliminar

        $eliminar = 1;

        $view_key = "eliminar_manuscrito";

        if(array_key_exists($view_key, $this->_view->acl) == false){
            $eliminar = 0;
        }

        $this->_view->eliminar = $eliminar;


        if(!isset($_SESSION['id_user'])){
            $this->redireccionar();
        }
        
        if(!$this->filtrarInt($pagina)){
           $pagina = false;
           $this->_view->pagina = 1;
       }else{
           $pagina = (int) $pagina;
           $this->_view->pagina = $pagina;
       }
        
        $this->getLibrary('paginador');
        $paginador = new Paginador();
        
        
        $manuscritos = $this->_manuscrito->getManuscritosParaAdmin();


         // var_dump($manuscritos);

        if($manuscritos){

            for($i=0; $i<count($manuscritos); $i++){
                $responsable =  $this->_manuscrito->getResponsable($manuscritos[$i]['id_manuscrito']);
                // var_dump($responsable);
                //para saber el estatus actual del manuscrito
                $manuscritoActual = $this->_manuscrito->getManuscritoActual($responsable['id_responsable']);

                $id_estatus = $manuscritoActual['id_estatus'];

                $estatus = $this->_manuscrito->getEstatus($id_estatus);

                // var_dump($estatus);

                $manuscritos[$i]['estatus'] = $estatus['estatus'];

                $autores = $this->_manuscrito->getAutoresByManuscrito($manuscritos[$i]['id_manuscrito']);

                // var_dump($autores);

                $lista_autores = array();

                 for($j = 0; $j < count($autores); $j++){
                    $lista_autores[] = $autores[$j]['nombrecompleto'];
                 }
                $manuscritos[$i]['autores'] = $lista_autores;
            }

             // exit;

            $this->_view->manuscritos = $paginador->paginar($manuscritos, false, $pagina);
        }
            
        else
            $this->_view->sin_manuscritos = "No se encontraron manuscritos";
        
        
        $this->_view->paginacion = $paginador->getView('prueba', 'manuscrito/index');
        
        $this->_view->titulo = 'Manuscritos';
        $this->_view->renderizar('index', 'manuscrito');
    }

    public function crear(){

        $view_key = "crear_manuscrito";

        if(!Session::get('autenticado_admin')){
            $this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();

        if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
            $this->redireccionar('acceso');
        }

        // $this->_view->setCssPublic(array('jquery-ui'));

        // $this->_view->setJsPublic(array('jquery-ui'));
        $this->_view->setJs(array('controllerCrear'));

        $this->_view->titulo = 'Crear Manuscrito';
        $this->_view->renderizar('crear', 'manuscrito');

    }

    public function editar($id_manuscrito = false){

        $view_key = "editar_manuscrito";

        if(!Session::get('autenticado_admin')){
            $this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();

        if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
            $this->redireccionar('acceso');
        }

        $this->_view->setJs(array('editarController'));

        if($this->filtrarInt($id_manuscrito)){


            $manuscrito = $this->_manuscrito->getInfoManuscrito((int)$id_manuscrito);

            if($manuscrito){
                $this->_view->id_manuscrito = $id_manuscrito;
            }else{
                //redirecccionar
            }

            $revista = $this->_ajax->getRevistas();
            $this->_view->revista = $revista;

            $areas = $this->_ajax->getAreas();
            $this->_view->areas = $areas;

            $idiomas = $this->_ajax->getIdiomas();
            $this->_view->idiomas = $idiomas;

            $this->_view->manuscrito = $manuscrito;

            if($this->getInt('enviado')){

                $valido = true;

                $datos['id_manuscrito'] = $this->getInt('id_manuscrito');

                $datos["titulo"] = $this->getSql('titulo');
                if(!$this->getSql('titulo')){
                    $this->_view->error_titulo = 'Debe introducir el titulo';
                    $valido = false;
                }
                
                //resumen
                
                $datos["resumen"] = $this->getSql('resumen');
                if(!$this->getSql('resumen')){
                    $this->_view->error_resumen = 'Debe introducir el resumen';
                    $valido = false;
                }
                
                //revista
                $datos["revista"] = $this->getPostParam('revista');
                if($this->getPostParam('revista') == 0){
                    $this->_view->error_revista = 'Debe seleccionar una revista';
                    $valido = false;
                }
                
                //area
                $datos["area"] = $this->getInt('area');
                if($this->getInt('area') == 0){
                    $this->_view->error_area = 'Debe seleccionar un &aacute;rea';
                    $valido = false;
                }
                
                //idioma
                $datos["idioma"] = $this->getInt('idioma');
                if($this->getInt('idioma') == 0){
                    $this->_view->error_idioma = 'Debe seleccionar un idioma';
                    $valido = false;
                }

                $this->_view->manuscrito = $datos;
                
                //palabrasClave
                
                // if(!$this->getSql('palabrasClave')){
                //     $datos["palabrasClave"] = 'Debe introducir palabras claves';
                //     $valido = false; 
                // }


                if($valido){
                    // editar
                    $this->_manuscrito->editarManuscrito($datos);
                    $this->redireccionar('manuscrito');
                }

            }
        }

        


        $this->_view->titulo = 'Editar Manuscrito';
        $this->_view->renderizar('editar', 'manuscrito');

    }

    // ajax respuesta del autor...
    public function ajaxVerRespuesta(){
         $json = array();

        if($this->getInt("id_evaluacion")){
            

            $result = $this->_manuscrito->getRespuestaAutor($this->getInt("id_evaluacion"));

             $json['result'] = $result;
        }

        echo json_encode($json);
    }



    public function ajaxEvaluacionDetalles(){
         $json = array();

        if($this->getInt("id_evaluacion")){
            

            $result = $this->_manuscrito->getEvaluacionById($this->getInt("id_evaluacion"));

             $json['evaluacion'] = $result;

            $result2 = $this->_manuscrito->getEvaluacionResult($this->getInt("id_evaluacion"));

            $json['detalles'] = $result2;
        }

        echo json_encode($json);
    }


    public function getEstatus(){

        $estatus = $this->_manuscrito->getEstatusByTipo("evaluacionArbitro");

        $data = '';
        for($i = 0; $i<count($estatus); $i++){
            $data .= "<option value='".$estatus[$i]["id_estatus"]."'>".$estatus[$i]["estatus"]."</option>";
        }

        echo $data;
    }

    public function evaluaciones($pagina = false){

        if(!$this->filtrarInt($pagina)){
           $pagina = false;
           $this->_view->pagina = 1;
        }else{
           $pagina = (int) $pagina;
           $this->_view->pagina = $pagina;
        }
        
        $this->getLibrary('paginador');
        $paginador = new Paginador();

        $manuscritos = $this->_manuscrito->getManuscritos();

        if($manuscritos){
            $this->_view->manuscritos = $paginador->paginar($manuscritos, false, $pagina);
            $this->_view->paginacion = $paginador->getView('prueba', 'manuscrito/index');
            $this->_view->enlace = $this->getUrl('manuscrito/detallesEvaluacionArbitro');
        }else{
            $this->_view->manuscritosVacio = 'No hay Manuscritos';
        }


        $this->_view->enlaceCrearEvaluacionEditor = $this->getUrl() . 'manuscrito/crearEvaluacionEditor/';
        $this->_view->enlaceDetalleEvaluacionEditor = $this->getUrl() . 'manuscrito/detallesEvaluacionEditor/';


        $this->_view->titulo = 'Evaluaci&oacute;n del Manuscrito';
        $this->_view->renderizar('evaluaciones', 'manuscrito');
    }

    //enviar evaluacion de los arbitros
    public function enviarEvaluacion(){
        $id_persona = Session::get('id_persona');
        //esto no creo que este bien, porq si esa persona tiene mas roles
        $rol = $this->_rol->getRolByIdPersona($id_persona);

        $id_manuscrito = $this->getInt('id_manuscrito');
        //verificar si existe el responsable con el rol del usuario conectado
        $resp = $this->_manuscrito->getResponsableByFiltro($id_persona, $id_manuscrito, $rol['id_rol']);
        //var_dump($resp);

        if(empty($resp)){
            $this->_manuscrito->setResponsable($id_manuscrito, $id_persona, $rol['id_rol'], 0, 0);

            $responsable = $this->_manuscrito->getUltimoResponsable();
            $responsable = $responsable["id_responsable"];
        }else{
            $responsable = $resp["id_responsable"];
        }

        //get id_estatus pamatro clave
        $estatus = $this->getInt('id_estatus');

        $this->_manuscrito->setRevision($responsable, $estatus, null);

        //obtenemos el responsable del manuscrito, el autor de correspondencia
        $resp_manuscrito = $this->_manuscrito->getResponsable($id_manuscrito);

        //actualiza el permiso del autor responsable del manuscrito
        $this->_manuscrito->updatePermisoResponsable($resp_manuscrito["id_responsable"], 1);

        $revision = $this->_manuscrito->getUltimaRevisionByResponsable($responsable);

        $ids = $this->getPostParam('ids');

        $evaluacion = explode(",", $ids);

        for($i = 0; $i<count($evaluacion); $i++){
            $this->_manuscrito->editarEvaluacionById($evaluacion[$i], $revision['id_revision']);
        }

        // $this->getLibrary('class.phpmailer');
        // $mail = new PHPMailer();
        // $mail->From = 'www.fecRevistasCientificas.com';
        // $mail->FromName = 'Revistas Arbitradas FEC';
        // $mail->Subject = 'Revistas FEC';
        // $url = $this->getUrlPagina('arbitro/solicitud/' . $this->getInt('id_persona') . "/" . $this->getInt('id_manuscrito') . "/" . $postulado['codigo']);
        // $mail->Body = '<p>Ciudadano (a) <strong>' . $persona['primerNombre'] . " " . $persona['apellido'] . '</strong></p>'.
        //         '<p>La Revista CIENCIA adscrita a la Facultad Experimental de Ciencias de la Universidad del Zulia, '.
        //         'Maracaibo Venezuela se complace en invitarle a participar como árbitro de nuestra revista '.
        //         'y de ser su gusto, solicitarle la revisión del manuscrito titulado: </p>'.
        //         '<p><strong>'. $manuscrito['titulo'] .'</strong></p>'.
        //         '<p>Para responder sobre su decisi&oacute;n de arbitraje pulsar en el enlace:</p> '.
        //         '<a href="'. $url .'">'.$url.'</a>'.
        //         '<br /><p>Puede ingresar con su usuario y contrase&ntilde;a:</p>'.
        //         '<p>Usuario: '.$usuario['usuario'].'</p>'.
        //         '<p>Clave: '.$pass.'</p>';
        // $mail->AltBody = "Su servidor de correo no soporta html";
        // $mail->addAddress($persona['email']);
        // $mail->Send();

        //recordar ocultar el checkbox y cambiar el estatus de pendiente a enviado
    }

    public function detallesEvaluacionEditor($id_manuscrito = false, $pagina = false){

        if(!$this->filtrarInt($pagina)){
           $pagina = false;
           $this->_view->pagina = 1;
        }else{
           $pagina = (int) $pagina;
           $this->_view->pagina = $pagina;
        }
        
        $this->getLibrary('paginador');
        $paginador = new Paginador();

        //metodo para validar la existencia del id
        if($id_manuscrito){

            $id_editor = $this->_rol->getIdRol('Editor');

            $responsables = $this->_manuscrito->getResponsableManuscrito($id_manuscrito, $id_editor[0]);

            if($responsables){
                $ids = '';
                for($i = 0; $i<count($responsables); $i++){
                    if($i > 0){
                        $ids .= ', ' . $responsables[$i]['id_responsable'] ;
                    }else{
                        $ids = $responsables[$i]['id_responsable']  . '';
                    }
                }

                $detalleEvaluacion = $this->_manuscrito->getDetallesEvaluacionEditor($ids);
                $this->_view->detalleEvaluacion = $paginador->paginar($detalleEvaluacion, false, $pagina);

            }else{
                $detalleEvaluacion = false;
            }            

            
            $this->_view->paginacion = $paginador->getView('prueba', 'manuscrito/detallesEvaluacionEditor/'. $id_manuscrito);

            $this->_view->responsable = $responsables;
            $this->_view->id_manuscrito = $id_manuscrito;

            $this->_view->titulo = 'Evaluaciones del Editor';
            $this->_view->renderizar('detallesEvaluacionEditor', 'manuscrito');
        }
    }

    public function detallesEvaluacionArbitro($id_manuscrito = false){

        //metodo para validar la existencia del id
        if($id_manuscrito){

            $this->_view->setJs(array('controllerEvaluacion'));

            $id_arbitro = $this->_rol->getIdRol('Arbitro');

            $responsables = $this->_manuscrito->getResponsableManuscrito($id_manuscrito, $id_arbitro[0]);

            if($responsables){
                $ids = '';
                for($i = 0; $i<count($responsables); $i++){
                    if($i > 0){
                        $ids .= ', ' . $responsables[$i]['id_responsable'] ;
                    }else{
                        $ids = $responsables[$i]['id_responsable']  . '';
                    }
                }

                $detalleEvaluacion = $this->_manuscrito->getDetallesEvaluacionArbitro($ids);

            }else{
                $detalleEvaluacion = false;
            }

            

            $this->_view->detalleEvaluacion = $detalleEvaluacion;
            $this->_view->responsable = $responsables;
            $this->_view->id_manuscrito = $id_manuscrito;

            $this->_view->titulo = 'Evaluaciones del &Aacute;rbitro';
            $this->_view->renderizar('detallesEvaluacionArbitro', 'manuscrito');
        }
    }

    public function arbitros($id_manuscrito = false, $pagina = false){

        $view_key = "arbitro_manuscrito";

        if(!Session::get('autenticado_admin')){
            $this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();

        if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
            $this->redireccionar('acceso');
        }

        $this->_view->setJs(array('controllerArbitro'));

        if($this->filtrarInt($id_manuscrito)){

            $this->_view->id_manuscrito = $this->filtrarInt($id_manuscrito);

            if(!$this->filtrarInt($pagina)){
               $pagina = false;
               $this->_view->pagina = 1;
            }else{
               $pagina = (int) $pagina;
               $this->_view->pagina = $pagina;
            }
            
            $this->getLibrary('paginador');
            $paginador = new Paginador();

            $id_arbitro = $this->_rol->getIdRol('Arbitro');

            $arbitrosPostulados = $this->_manuscrito->getArbitrosPostulados($this->filtrarInt($id_manuscrito));

            $ids = '';

            for($i = 0; $i < count($arbitrosPostulados); $i++){
                if($i > 0) $ids .= ", "; 
                $ids .= $arbitrosPostulados[$i]['id_persona']; 
            }

            $autores = $this->_manuscrito->getAutoresManuscrito($id_manuscrito);
            $this->_view->autores = $autores;


            for($i = 0; $i<count($autores); $i++){
                if($ids == "")
                    $ids = $autores[$i]['id_persona'];
                else
                    $ids .= ", " . $autores[$i]['id_persona'];
            }


            $this->_view->arbitrosPostulados = $arbitrosPostulados;

            $arbitros = $this->_manuscrito->getArbitros($id_arbitro[0], $ids);

            $this->_view->arbitros = $paginador->paginar($arbitros, false, $pagina);

        }

         
        $this->_view->paginacion = $paginador->getView('prueba', 'manuscrito/index');

        $this->_view->titulo = 'Arbitros';
        $this->_view->renderizar('arbitros', 'manuscrito');
    }

    public function asignarArbitroManuscrito(){
        $resp['resp'] = 0;

        $id_arbitro = $this->_rol->getIdRol('Arbitro');

        if($this->getInt('id_persona') && $this->getInt('id_manuscrito')){
            $this->_manuscrito->editarEstatusArbitro($this->getInt('id_persona'), $this->getInt('id_manuscrito'), 2);
            $this->_manuscrito->asignarArbitroManuscrito($this->getInt('id_persona'), $this->getInt('id_manuscrito'), $id_arbitro[0]);

            //asignamos en la latabla revision que ya se esta comenzando a evaluar
            $rol = $this->_rol->getRolByIdPersona($this->getInt('id_persona'));
            $r = $this->_manuscrito->getResponsableByFiltro($this->getInt('id_persona'), $this->getInt('id_manuscrito'), $rol['id_rol']); 
            
            $estatus = $this->_manuscrito->getEstatusByClave("manuscritoEnArbitraje");
            $this->_manuscrito->setRevision($r['id_responsable'], $estatus['id_estatus'], null);

            $resp['resp'] = 1;
        }

        echo $resp['resp'];
    }

    public function quitarArbitro(){
        $resp['resp'] = 0;

        if($this->getInt('id_persona') && $this->getInt('id_manuscrito')){
            $this->_manuscrito->quitarArbitro($this->getInt('id_persona'), $this->getInt('id_manuscrito'));
            $resp['resp'] = 1;
        }

        echo $resp['resp'];
    }

    public function setArbitroPostulado(){
        $resp['resp'] = 0;

        if($this->getInt('id_persona') && $this->getInt('id_manuscrito')){
            $this->_manuscrito->setArbitroPostulado($this->getInt('id_persona'), $this->getInt('id_manuscrito'));
            $resp['resp'] = 1;
        }

        echo $resp['resp'];
    }

    public function enviarSolicitud(){

            // VALIDAR

        if($this->_manuscrito->getArbitroPostulado($this->getInt('id_persona'), $this->getInt('id_manuscrito'))){

            $persona = $this->_persona->getDatos($this->getInt('id_persona'));
            $manuscrito = $this->_manuscrito->getInfoManuscrito($this->getInt('id_manuscrito'));
            $postulado = $this->_manuscrito->getArbitroPostulado($this->getInt('id_persona'), $this->getInt('id_manuscrito'));
            $this->_manuscrito->editarEstatusArbitro($this->getInt('id_persona'), $this->getInt('id_manuscrito'), -1);
            $estatus = $this->_manuscrito->getEstatusPostulado($this->getInt('id_persona'), $this->getInt('id_manuscrito'));

            $usuario = $this->_usuario->getUsuario($this->getInt('id_persona'));

            $pass = substr( md5(microtime()), 1, 8);

            $this->_usuario->setClave($this->getInt('id_persona'), $pass);

            $arreglo['estatus'] = $estatus['estatus'];

            $this->getLibrary('class.phpmailer');
            $mail = new PHPMailer();
            $mail->From = 'www.fecRevistasCientificas.com';
            $mail->FromName = 'Revistas Arbitradas FEC';
            $mail->Subject = 'Revistas FEC';
            $url = $this->getUrlPagina('arbitro/solicitud/' . $this->getInt('id_persona') . "/" . $this->getInt('id_manuscrito') . "/" . $postulado['codigo']);
            $mail->Body = '<p>Ciudadano (a) <strong>' . $persona['primerNombre'] . " " . $persona['apellido'] . '</strong></p>'.
                    '<p>La Revista CIENCIA adscrita a la Facultad Experimental de Ciencias de la Universidad del Zulia, '.
                    'Maracaibo Venezuela se complace en invitarle a participar como árbitro de nuestra revista '.
                    'y de ser su gusto, solicitarle la revisión del manuscrito titulado: </p>'.
                    '<p><strong>'. $manuscrito['titulo'] .'</strong></p>'.
                    '<p>Para responder sobre su decisi&oacute;n de arbitraje pulsar en el enlace:</p> '.
                    '<a href="'. $url .'">'.$url.'</a>'.
                    '<br /><p>Puede ingresar con su usuario y contrase&ntilde;a:</p>'.
                    '<p>Usuario: '.$usuario['usuario'].'</p>'.
                    '<p>Clave: '.$pass.'</p>';
            $mail->AltBody = "Su servidor de correo no soporta html";
            $mail->addAddress($persona['email']);
            $mail->Send();

            echo json_encode($arreglo);
        }
    }

    public function getArbitrosPostulados(){

        echo $this->_manuscrito->getArbitrosPostulados();

    }


    public function getResponsable(){
        $responsable = false;
        $resp['resp'] = 0;
        if($this->getInt('id_manuscrito'))
            $responsable = $this->_manuscrito->getResponsable($this->getInt('id_manuscrito'));

        if($responsable)
             $resp['resp'] = 1;

         echo $resp['resp'];
    }


    public function setAutor(){
        $resp = array();

        if($this->getInt('id_persona')){

            //verificar si existe y si es de tipo autor o co-autor

            $id_autor = $this->_rol->getIdRol($this->getPostParam('rol'));

            $personaRol =  $this->_rol->getPersonaRol($this->getInt('id_persona'), $id_autor[0]);

            if($personaRol){
                if($this->getPostParam('rol') == 'Autor'){
                    $this->_manuscrito->setResponsable($this->getInt('id_manuscrito'), $this->getInt('id_persona'), $id_autor[0], 1, 1);
                }else{
                    $this->_manuscrito->setResponsable($this->getInt('id_manuscrito'), $this->getInt('id_persona'), $id_autor[0], 0, 0);
                }

                $resp['prueba1'] = $this->getInt('id_manuscrito');
                $resp['prueba2'] = $this->getInt('id_persona');
                $resp['prueba3'] = $id_autor[0];
                
                $resp['result'] = 1;
                $resp['response'] = 'El autor fu&eacute; agregado con &Eacute;xito';
            }else{
                $resp['result'] = 0;
                $resp['response'] = 'No se pudo agregar el Autor, compruebe que el Id sea v&aacute;lido';
            }
            
        }else{
            $resp['result'] = 0;
            $resp['response'] = 'No se pudo agregar el Autor, compruebe que el Id sea v&aacute;lido';
        }



        echo json_encode($resp);
    }

    public function eliminarAutores(){
        if($this->getInt('id_manuscrito') && $this->getPostParam('ids'))
            $this->_manuscrito->eliminarAutores($this->getInt('id_manuscrito'), $this->getPostParam('ids'));
    }

    public function getAutoresManuscrito(){
        if($this->getInt('id_manuscrito'))
            echo json_encode($this->_manuscrito->getAutoresManuscrito($this->getInt('id_manuscrito')));
    }

    public function comprobarEmail(){
        
        
        $resp['resp'] = "";

        if(!$this->validarEmail($this->getPostParam('email'))){
            $resp['resp'] = 'La direcci&oacute;n de correo es inv&aacute;lida';
        }

        if($_POST['email'] == "") $resp['resp'] = "";
        
            
        if($this->_usuario->verificarEmail($this->getPostParam('email'))){
            $resp['resp'] = 'Esta direcci&oacute;n de correo ya est&aacute; registrada';
        }
            
    
        echo $resp['resp'];
    }

    public function getEmail(){
        $data = array();
        // $data['email'] = $this->getPostParam('email');
        // $data['test'] = $this->validarEmail($this->getPostParam('email'));


        $exp = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.([a-zA-Z]{2,4})+$/';
        if(isset($_POST['email']) && !empty($_POST['email'])){
            $data['preg'] = preg_match($exp, $_POST['email']);
            if(!preg_match($exp, $_POST['email'])){
                $data['status'] = 1;
                $data['error'] = 'La direcci&oacute;n de correo es inv&aacute;lida';
            }
        }

        if($result = $this->_usuario->getEmail($this->getPostParam('email'))){
            $data['status'] = 2;
            $data['datos'] = $result;
            $data['error'] = 'La direcci&oacute;n de correo ya esta en uso';
        }

        echo json_encode($data);
    }


    public function crearEvaluacionEditor($id_manuscrito = false){
        
        $this->_view->setJs(array('controllerEvaluacionEditorCrear'));

        if($this->filtrarInt($id_manuscrito)){
            $this->_view->manuscrito = $this->_manuscrito->getManuscrito($this->filtrarInt($id_manuscrito));
        }


        $this->_view->titulo = 'Crear Evaluacion';
        $this->_view->renderizar('crearEvaluacionEditor', 'manuscrito');
    }


    public function enviarEvaluacionEditor(){
        $observaciones = $this->getSql('observaciones');
        $id_manuscrito = $this->getInt('manuscrito');

        $arreglo["status"] = 1;

        $url = ROOT;

        $url = explode("admin", $url);

        if(isset($_FILES["archivo"])){

            $responsable = $this->_manuscrito->getResponsable($id_manuscrito);

            $datos_persona = $this->_persona->getDatos($responsable['id_persona']);

            $apellido = explode(" ", $datos_persona['apellido']);
            
            $ruta = $url[0] . "manuscritos";

            $ruta .= '/' . $apellido[0] . "_" .$responsable['id_persona'];

            $ruta .= "/manuscrito_". $_POST['manuscrito'];

            $count_fisico = $this->_manuscrito->getCountFisico();

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

                $arreglo["msj_file"] = "Error: Debe seleccionar un archivo";
                $arreglo["status"] = 0;
                exit(); 

            } 
            if(move_uploaded_file($fileTmpLoc, $ruta."/".$fileName)){ 
                
                $arreglo["msj_file"] = "$fileName Subida completada";
                
                $this->_manuscrito->setFisico($ruta, $fileName);

                $fisico = $this->_manuscrito->getUltimoFisico();

                $rol = $this->_rol->getRolByIdPersona($_SESSION['id_persona']);

                $responsable = $this->_manuscrito->getResponsableByFiltro($_SESSION['id_persona'], $_POST['manuscrito'], $rol['id_rol']);

                if($responsable == false){

                    $this->_manuscrito->setResponsable($id_manuscrito, $_SESSION['id_persona'], $rol['id_rol'], 0, 0);

                    $responsable = $this->_manuscrito->getUltimoResponsable();

                }

                 //obtenemos el responsable del manuscrito, el autor de correspondencia
                $resp_manuscrito = $this->_manuscrito->getResponsable($id_manuscrito);

                //actualiza el permiso del autor responsable del manuscrito
                $this->_manuscrito->updatePermisoResponsable($resp_manuscrito["id_responsable"], 1);

                
                
                $estatus = $this->_manuscrito->getEstatusByClave($_POST['estatus']);
                $this->_manuscrito->setRevision($responsable['id_responsable'], $estatus['id_estatus'], $fisico['id_fisico']);

                $ultima_revision = $this->_manuscrito->getUltimaRevisionByResponsable($responsable['id_responsable']);

                $this->_manuscrito->setObservaciones($ultima_revision['id_revision'], $id_manuscrito, $observaciones);
                
                
            } else { 

                $arreglo["msj_file"] = "Error al subir el archivo";
                $arreglo["status"] = 0;
            }
        }else{
                $rol = $this->_rol->getRolByIdPersona($_SESSION['id_persona']);

                $responsable = $this->_manuscrito->getResponsableByFiltro($_SESSION['id_persona'], $_POST['manuscrito'], $rol['id_rol']);

                if($responsable == false){

                    $this->_manuscrito->setResponsable($id_manuscrito, $_SESSION['id_persona'], $rol['id_rol'], 0, 0);

                    $responsable = $this->_manuscrito->getUltimoResponsable();

                }

                //obtenemos el responsable del manuscrito, el autor de correspondencia
                $resp_manuscrito = $this->_manuscrito->getResponsable($id_manuscrito);

                //actualiza el permiso del autor responsable del manuscrito
                $this->_manuscrito->updatePermisoResponsable($resp_manuscrito["id_responsable"], 1);

                
                
                $estatus = $this->_manuscrito->getEstatusByClave($_POST['estatus']);
                $this->_manuscrito->setRevision($responsable['id_responsable'], $estatus['id_estatus'], null);

                $ultima_revision = $this->_manuscrito->getUltimaRevisionByResponsable($responsable['id_responsable']);

                $this->_manuscrito->setObservaciones($ultima_revision['id_revision'], $id_manuscrito, $observaciones);
        }

        echo json_encode($arreglo);
             // echo $url[0];
    }


    public function historico($id_manuscrito = false,  $pagina = false){

        $view_key = "manuscrito_historico";

        if(!Session::get('autenticado_admin')){
            $this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();

        if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
            $this->redireccionar('acceso');
        }

        if($id_manuscrito != false && $this->filtrarInt($id_manuscrito) != 0){
            
                if(!$this->filtrarInt($pagina)){
                    $pagina = false;
                }else{
                    $pagina = (int) $pagina;
                }

                $this->getLibrary('paginador');
                $paginador = new Paginador();

                $this->_view->autores = array();
                    
                $manusc = $this->_manuscrito->getManuscrito($id_manuscrito); // obtenemos el manuscrito
                if($manusc){     
                    $autorManuscrito = $this->_manuscrito->getAutorManuscrito($manusc['id_manuscrito']); //obtenemos el id_persona
                    $iter = 0;
                    while($fila = $autorManuscrito->fetch()){//obtenemos el id_persona
                        $id = $fila['id_persona']; //lo asignamos
                        $datos_persona = $this->_manuscrito->getPersona($id);
                        $array_autor['persona_'.$iter] = $datos_persona['primerNombre'] . " " . $datos_persona['apellido']; //almaceno los autores
                        $array_autor['id_persona_'.$iter] = $datos_persona['id_persona'];

                        $iter++;
                    }
                    $this->_view->autores[$manusc['id_manuscrito']] = $array_autor;
                    $this->_view->id_manuscrito = (int)$id_manuscrito;
                    
                }else{
                    header('location:' . BASE_URL . 'error/access/404');
                    exit;
                }


                $this->_view->manuscritos = $paginador->paginar($this->_manuscrito->getRevisiones($manusc['id_manuscrito']), false, $pagina);

                $this->_view->enlaceDetalles = $this->getUrl("manuscrito/detallesManuscrito");


                $this->_view->paginacion = $paginador->getView('prueba', 'manuscrito/historico/'.$id_manuscrito);

                $this->_view->enlaceCorreccion = $this->getUrl("manuscrito/correccion/". $manusc['id_manuscrito']);
                $this->_view->titulo = 'Hist&oacute;rico';
                $this->_view->renderizar('historico', 'manuscrito');
            }
        
    }


    //insert manuscrito admin
    //--------------------------------arreglar este metodo-----------enero 06/15---------------------

    public function insertarManuscrito(){
        
        //validar
        $arreglo = array();
        $arreglo["status"] = 1;

        $rolAutor = $this->_rol->getIdRol("Autor");
        $rolCoAutor = $this->_rol->getIdRol("Co-Autor");

        //validar datos del autor responsable

        if(!$this->getSql('responsableNombre')){
            $datos["responsableNombre"] = 'Debe introducir su nombre';
            $arreglo["status"] = 0;
        }

        if(!(strlen($this->getSql('responsableNombre')) > 2)){
            $datos["responsableNombre"] = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
            $arreglo["status"] = 0;
        }

        $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

        if(!preg_match($exp, $this->getPostParam('responsableNombre'))){
            $datos["responsableNombre"] = 'Nombre inv&aacute;lido';
            $arreglo["status"] = 0;
        }
        
        //Apellido 
        
        if(!$this->getSql('responsableApellido')){
            $datos["responsableApellido"] = 'Debe introducir su apellido';
            $arreglo["status"] = 0;
        }

        if(!(strlen($this->getSql('responsableApellido')) > 2)){
            $datos["responsableApellido"] = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
            $arreglo["status"] = 0;
        }

        $test_apellido1 = true;
        $test_apellido2 = true;

        $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

        if(!preg_match($exp, $this->getPostParam('responsableApellido'))){
            // $datos["apellido"] = 'Apellido inv&aacute;lido';
            $test_apellido1 = false;
        }


        $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
        
        
        if(!preg_match($exp, $this->getPostParam('responsableApellido'))){
            // $this->_view->_error_apellido = 'Apellido inv&aacute;lido';
            // $validado = false;
            $test_apellido2 = false;
        }

        if($test_apellido1 == false && $test_apellido2 == false){
            $datos["responsableApellido"] = 'Apellido inv&aacute;lido';
            $validado = false;
            $arreglo["status"] = 0;
        }


        $id_persona_responsable = "";
        //email 
        if(!$this->validarEmail($this->getPostParam('responsableEmail'))){
            $datos["email"] = 'La direccion de email es inv&aacute;lida';
            $arreglo["status"] = 0;
        }
//ver*ificar si ya esta registrado si es asi obtener su id_persona y establecer el reponsable y todo

        else if($temp = $this->_usuario->getEmail($this->getPostParam('responsableEmail'))){
            $id_persona_responsable = $temp['id_persona'];
        }




        // if($this->_registro->verificarEmail($this->getPostParam('email'))){
        //     $datos["email"] = 'Esta direcci&oacute;n de email ya est&aacute; registrada';
        //     $arreglo["status"] = 0;
        // }


        //validar datos del co-autor
        for($i = 0; $i < $_POST["iter"]; $i++){
            
                $datos["nombre"] = "";
                $datos["apellido"] = "";
                $datos["idx"] = $i;
                
                //Nombre
                
                if(!$this->getSql('nombre'.$i)){
                    $datos["nombre"] = 'Debe introducir su nombre';
                    $arreglo["status"] = 0;
                }

                if(!(strlen($this->getSql('nombre'.$i)) > 2)){
                    $datos["nombre"] = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                    $arreglo["status"] = 0;
                }

                $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

                if(!preg_match($exp, $this->getPostParam('nombre'.$i))){
                    $datos["nombre"] = 'Nombre inv&aacute;lido';
                    $arreglo["status"] = 0;
                }
                
                //Apellido 
                
                if(!$this->getSql('apellido'.$i)){
                    $datos["apellido"] = 'Debe introducir su apellido';
                    $arreglo["status"] = 0;
                }

                if(!(strlen($this->getSql('apellido'.$i)) > 2)){
                    $datos["apellido"] = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                    $arreglo["status"] = 0;
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
                }


                
                //email 
                if(!$this->validarEmail($this->getPostParam('email'))){
                    $datos["email"] = 'La direccion de email es inv&aacute;lida';
                    $arreglo["status"] = 0;
                }

                // if($this->_registro->verificarEmail($this->getPostParam('email'))){
                //     $datos["email"] = 'Esta direcci&oacute;n de email ya est&aacute; registrada';
                //     $arreglo["status"] = 0;
                // }

                $arreglo["data"][] = $datos;
                
        }//end for    
                
                // //pais
                // if($this->getInt('pais'.$i) == 0){
                //     $datos["pais"] = 'Debe seleccionar un pais';
                //     $arreglo["status"] = 0;
                // }
                
                // //telefono
                // $exp = '/^\\d{11,14}$/';
            
                // if(!preg_match($exp, $this->getPostParam('telefono'.$i))){
                //     $datos["telefono"] = 'Debe proporcionar un n&uacute;mero de tel&eacute;fono v&aacute;lido';
                //     $arreglo["status"] = 0;
                // }
                
                //titulo
                
                $datos["titulo"] = "";
                if(!$this->getSql('titulo')){
                    $datos["titulo"] = 'Debe introducir el titulo';
                    $arreglo["status"] = 0;
                }
                
                //resumen
                
                $datos["resumen"] = "";
                if(!$this->getSql('resumen')){
                    $datos["resumen"] = 'Debe introducir el resumen';
                    $arreglo["status"] = 0;
                }
                
                //revista
                
                if($this->getPostParam('revista') == 0){
                    $datos["revista"] = 'Debe seleccionar una revista';
                    $arreglo["status"] = 0;
                }
                
                //area
                
                if($this->getInt('area') == 0){
                    $datos["area"] = 'Debe seleccionar un &aacute;rea';
                    $arreglo["status"] = 0;
                }
                
                //idioma
                
                if($this->getInt('idioma') == 0){
                    $datos["idioma"] = 'Debe seleccionar un idioma';
                    $arreglo["status"] = 0;
                }
                
                //palabrasClave
                
                if(!$this->getSql('palabrasClave')){
                    $datos["palabrasClave"] = 'Debe introducir palabras claves';
                    $arreglo["status"] = 0; 
                }
                
                $arreglo["data"][] = $datos;

        
        
        if($arreglo['status']){ // si todo va bien entonces agregar

            $arreglo["obra"] = $this->getInt('idioma') . " - " . $this->getSql('revista') ." - ". $this->getInt('area');
            //al metodo setObra agregar campo tipo (manuscrito u obra)_
            $this->_manuscrito->setObra($this->getInt('idioma'), $this->getSql('revista'), $this->getInt('area'));
            $obra = $this->_manuscrito->getUltimaObra();
            
            //set manuscrito
            $this->_manuscrito->setManuscrito($this->getSql('titulo'), $this->getSql('resumen'), $obra['id_obra']);
            $manuscrito = $this->_manuscrito->getManuscritoObra($obra['id_obra']);


            // si la persona ya estaba registrada asignar el manuscrito
            if($id_persona_responsable != ""){
                $arreglo["test_idpersona"] = $id_persona_responsable;

                
                $responsable = 0;

                //agregar el autor responsable

                $permiso = 0;

                $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$id_persona_responsable, $rolAutor[0], $permiso, 1);
                $responsable = $this->_manuscrito->getUltimoResponsable();



            }else{// sino entonces registrarla y asiganar el manuscrito

                $this->_persona->setPersona($this->getSql('responsableNombre'), 
                                $this->getSql('responsableApellido'), 
                                "",
                                "",
                                $this->getPostParam('responsableEmail'), 
                                "", 
                                256,
                                "",
                                "",
                                "");
                        
                $persona = $this->_persona->getUltimaPersona();
                $id_persona_responsable = $persona['id_persona'];

                $this->_persona->setPersonaRol($persona['id_persona'], $rolAutor[0]);
                
                $responsable = 0;

                //agregar el autor responsable

                $permiso = 0;

                $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$persona['id_persona'], $rolAutor[0], $permiso, 1);
                $responsable = $this->_manuscrito->getUltimoResponsable();

            }


            // agregar los co-autores
            for($i = 0; $i < $_POST["iter"]; $i++){

                
                $temp = $this->_usuario->getEmail($this->getPostParam($this->getPostParam('email'.$i)));

                if($temp){

                    //agregar el autor responsable

                    $permiso = 0;

                    $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$temp['id_persona'], $rolCoAutor[0], $permiso, 0);
                    // $this->_manuscrito->getUltimoResponsable();
                }else{

                    $this->_persona->setPersona($this->getSql('nombre'.$i), 
                            $this->getSql('apellido'.$i), 
                            "", 
                            "",
                            $this->getPostParam('email'.$i), 
                            "", 
                            256,
                            "",
                            "",
                            "");

                    $persona = $this->_persona->getUltimaPersona();

                    $this->_persona->setPersonaRol($persona['id_persona'], $rolCoAutor[0]);
                    

                    $permiso = 0;

                    $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$persona['id_persona'], $rolCoAutor[0], $permiso, 0);

                }

            }
                
                // for($i = 0; $i < $_POST["iter"]; $i++){
            
                //     $permiso = 0;
                //     //si son autores nuevos
                //     if((int)$_POST["idx_autor"] != $i){
                //         $this->_registro->setPersona($this->getSql('primerNombre'.$i), 
                //                 $this->getSql('apellido'.$i), 
                //                 $this->getPostParam('genero'.$i), 
                //                 $this->getPostParam('email'.$i), 
                //                 $this->getPostParam('telefono'.$i), 
                //                 $this->getInt('pais'.$i),
                //                 "",
                //                 "",
                //                 "",
                //                 $this->getSql('segundoNombre'.$i));
                        
                //         $persona = $this->_registro->getUltimaPersona();
                        
                        
                        
                //         if((int)$_POST["autorPrincipal"] == $i){
                //             $this->_registro->setPersonaRol($persona['id_persona'], $rolAutor[0]);

                //             $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$persona['id_persona'], $rolAutor[0], $permiso, 0);

                //                 // $responsable = $this->_manuscrito->getUltimoResponsable();
                //         }else{
                //             $this->_registro->setPersonaRol($persona['id_persona'], $rolCoAutor[0]);
                //             $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$persona['id_persona'], $rolCoAutor[0], $permiso, 0);
                //         }
                        
                //     }
                    
                //     //si es el autor de la cuenta
                        
                //     if((int)$_POST["idx_autor"] == $i){
                        
                //         $permiso = 0;
                //         // $this->_persona->setCorrespondencia($_SESSION["id_persona"],$manuscrito['id_manuscrito'], $permiso);

                //         if((int)$_POST["autorPrincipal"] == $i){
                        
                //             if(isset($_SESSION["id_persona"])){
                //                 $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$_SESSION["id_persona"], $rolAutor[0], $permiso, 1);
                //                 $responsable = $this->_manuscrito->getUltimoResponsable();
                //             }
                //         }else{
                //                 $this->_registro->setPersonaRol($persona['id_persona'], $rolCoAutor[0]);
                //                 $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$_SESSION["id_persona"], $rolCoAutor[0], $permiso, 1);
                //                 //esta variabla responsable guarda el autor para la correspondencia
                //                 $responsable = $this->_manuscrito->getUltimoResponsable();

                //         }
                            
                //     }

                // }
                
             
            
            
            //---------------------------------------

            $datos_persona = $this->_persona->getDatos($id_persona_responsable);

            $apellido = explode(" ", $datos_persona['apellido']);
            
            $ruta = "manuscritos";
            
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777);
            }
            $ruta .= '/' . $apellido[0] . "_" .$id_persona_responsable;

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





// -----------------------------------------------------borrar lo de abajo












    
    //Modificar este metodo para que solo muestre el historial de un manuscrit en particular
    
    public function misManuscritos($pagina = false, $id_manuscrito = false){
        
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


                $id_persona = Session::get('id_persona');

                $obras = $this->_manuscrito->getObraManuscrito($id_persona);

                //$autores = array();
                $this->_view->autores = array();

                
                    
                $manusc = $this->_manuscrito->getManuscrito($id_manuscrito); // obtenemos el manuscrito
                if($manusc){     
                    $autorManuscrito = $this->_manuscrito->getAutorManuscrito($manusc['id_manuscrito']); //obtenemos el id_persona
                    $iter = 0;
                    while($fila = $autorManuscrito->fetch()){//obtenemos el id_persona
                        $id = $fila['id_persona']; //lo asignamos
                        $datos_persona = $this->_manuscrito->getPersona($id);
                        $array_autor['persona_'.$iter] = $datos_persona['primerNombre'] . " " . $datos_persona['apellido']; //almaceno los autores
                        $array_autor['id_persona_'.$iter] = $datos_persona['id_persona'];

                        $iter++;
                    }
                    $this->_view->autores[$manusc['id_manuscrito']] = $array_autor;
                    //$autores[$manusc['id_manuscrito']] = $array_autor;
                }else{
                    header('location:' . BASE_URL . 'error/access/404');
                    exit;
                }

                //echo '<pre>';
                //print_r($autores);

               // echo "contador = " . count($autores) . "<br />";


                //$this->_view->revisiones = $this->_manuscrito->getRevisiones($autor['id_autor']);

                $this->_view->manuscritos = $paginador->paginar($this->_manuscrito->getRevisiones($manusc['id_manuscrito']), $pagina);


                //print_r($this->_view->revisiones);

                //exit;

                $this->_view->paginacion = $paginador->getView('prueba', 'manuscrito/misManuscritos');

                $this->_view->enlaceCorreccion = $this->getUrl("manuscrito/correccion/". $manusc['id_manuscrito']);
                $this->_view->titulo = 'Mis manuscritos';
                $this->_view->renderizar('misManuscritos', 'manuscrito');
            }else{
                $this->redireccionar('manuscrito');
            }
        }
        
    }
    
    public function insertar(){
        
        //validar
        Session::accesoEstricto(array('Autor'));
        $arreglo = array();
        $arreglo["status"] = 1;

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
                }

                if(!(strlen($this->getSql('primerNombre'.$i)) > 2)){
                    $datos["nombre"] = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                    $arreglo["status"] = 0;
                }

                $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

                if(!preg_match($exp, $this->getPostParam('primerNombre'.$i))){
                    $datos["nombre"] = 'Nombre inv&aacute;lido';
                    $arreglo["status"] = 0;
                }
                
                //Apellido 
                
                if(!$this->getSql('apellido'.$i)){
                    $datos[$i]["apellido"] = 'Debe introducir su apellido';
                    $arreglo["status"] = 0;
                }

                if(!(strlen($this->getSql('apellido'.$i)) > 2)){
                    $datos["apellido"] = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                    $arreglo["status"] = 0;
                }

                $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

                if(!preg_match($exp, $this->getPostParam('apellido'.$i))){
                    $datos["apellido"] = 'Apellido inv&aacute;lido';
                    $arreglo["status"] = 0;
                }
                
                //email 
                if(!$this->validarEmail($this->getPostParam('email'))){
                    $datos["email"] = 'La direccion de email es inv&aacute;lida';
                    $arreglo["status"] = 0;
                }

                if($this->_registro->verificarEmail($this->getPostParam('email'))){
                    $datos["email"] = 'Esta direcci&oacute;n de email ya est&aacute; registrada';
                    $arreglo["status"] = 0;
                }
                
                
                
                //pais
                if($this->getInt('pais'.$i) == 0){
                    $datos["pais"] = 'Debe seleccionar un pais';
                    $arreglo["status"] = 0;
                }
                
                //telefono
                $exp = '/^\\d{11,14}$/';
            
                if(!preg_match($exp, $this->getPostParam('telefono'.$i))){
                    $datos["telefono"] = 'Debe proporcionar un n&uacute;mero de tel&eacute;fono v&aacute;lido';
                    $arreglo["status"] = 0;
                }
                
                //titulo
                
                $datos["titulo"] = "";
                if(!$this->getSql('titulo')){
                    $datos["titulo"] = 'Debe introducir el titulo';
                    $arreglo["status"] = 0;
                }
                
                //resumen
                
                $datos["resumen"] = "";
                if(!$this->getSql('resumen')){
                    $datos["resumen"] = 'Debe introducir el resumen';
                    $arreglo["status"] = 0;
                }
                
                //revista
                
                if($this->getInt('revista') == 0){
                    $datos["revista"] = 'Debe seleccionar una revista';
                    $arreglo["status"] = 0;
                }
                
                //area
                
                if($this->getInt('area') == 0){
                    $datos["area"] = 'Debe seleccionar un &aacute;rea';
                    $arreglo["status"] = 0;
                }
                
                //idioma
                
                if($this->getInt('idioma') == 0){
                    $datos["idioma"] = 'Debe seleccionar un idioma';
                    $arreglo["status"] = 0;
                }
                
                //palabrasClave
                
                if(!$this->getSql('palabrasClave')){
                    $datos["palabrasClave"] = 'Debe introducir palabras claves';
                    $arreglo["status"] = 0; 
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
                $this->_manuscrito->setManuscrito($this->getSql('titulo'), $this->getSql('resumen'), $obra['id_obra']);
                $manuscrito = $this->_manuscrito->getManuscritoObra($obra['id_obra']);
                
                $responsable = 0;
                $persona = 0;
                
                for($i = 0; $i < $_POST["iter"]; $i++){
            
                    $permiso = 0;
                    //si son autores nuevos
                    if((int)$_POST["idx_autor"] != $i){
                        $this->_registro->setPersona($this->getSql('primerNombre'.$i), 
                                $this->getSql('apellido'.$i), 
                                $this->getPostParam('genero'.$i), 
                                $this->getPostParam('email'.$i), 
                                $this->getPostParam('telefono'.$i), 
                                $this->getInt('pais'.$i),
                                "",
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
                        
                            if(isset($_SESSION["id_persona"])){
                                $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$_SESSION["id_persona"], $rolAutor[0], $permiso, 1);
                                $responsable = $this->_manuscrito->getUltimoResponsable();
                            }
                        }else{
                                $this->_registro->setPersonaRol($persona['id_persona'], $rolCoAutor[0]);
                                $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$_SESSION["id_persona"], $rolCoAutor[0], $permiso, 1);
                                //esta variabla responsable guarda el autor para la correspondencia
                                $responsable = $this->_manuscrito->getUltimoResponsable();

                        }
                            
                    }

                }
                
             
            
            
            //---------------------------------------

            $datos_persona = $this->_persona->getDatos($_SESSION["id_persona"]);

            $apellido = explode(" ", $datos_persona['apellido']);
            
            $ruta = "manuscritos";
            
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777);
            }
            $ruta .= '/' . $apellido[0] . "_" .$_SESSION["id_persona"];

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

    

    public function correccion($id_manuscrito = false){

        Session::accesoEstricto(array('Autor'));
        $this->_view->setJs(array('js_correccion'));

        $rolAutor = $this->_rol->getIdRol("Autor");
        if($id_manuscrito != false){

            $persona = null;
            $persona = $this->_manuscrito->validarResponsable($this->filtrarInt($id_manuscrito), $_SESSION['id_persona'], $rolAutor[0]);

            //comprobamos q ese manuscrito es de ese usuario, y q tiene permisos para corregir
            if($persona && $persona['permiso'] != 0){ 

                $this->_view->id_manuscrito = $id_manuscrito;
                $this->_view->titulo = 'Correcci&oacute;n';
                $this->_view->renderizar('correccion', 'manuscrito');

            }else{
                $this->redireccionar('manuscrito');
            }

        }else{
            $this->redireccionar('manuscrito');
        }

    }

    public function enviarCorreccion(){

            $arreglo["status"] = 1;

            $datos_persona = $this->_persona->getDatos($_SESSION["id_persona"]);
            $apellido = explode(" ", $datos_persona['apellido']);
            $ruta = "manuscritos";
            $ruta .= '/' . $apellido[0] . "_" .$_SESSION["id_persona"];
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
                    //echo "$fileName upload is complete";
                $arreglo["msj_file"] = "$fileName Subida completada";
                
                $this->_manuscrito->setFisico($ruta, $fileName);

                $fisico = $this->_manuscrito->getUltimoFisico();

                $responsable = $this->_persona->getAutorCorrespondencia($_POST['manuscrito']);
                
                $estatus = $this->_manuscrito->getEstatusPorNombre("Correccion");
                $this->_manuscrito->setRevision($responsable['id_responsable'], $estatus['id_estatus'], $fisico['id_fisico']);
                
                $this->_persona->setPermisoResponsable($responsable['id_responsable'], 0);
                
                
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
    
}

?>