<?php

class manuscritoController extends Controller{
    
    private $_manuscrito;
    private $_registro;
    private $_autor;
    
    public function __construct(){
        parent::__construct();
        $this->_manuscrito = $this->loadModel('manuscrito');
        $this->_registro = $this->loadModel('registro');
        $this->_autor = $this->loadModel("autor");
    }
    
    public function index($pagina = false){
        
        Session::accesoEstricto(array('autor'));
        
        if(!$this->filtrarInt($pagina)){
           $pagina = false;
           $this->_view->pagina = 1;
       }else{
           $pagina = (int) $pagina;
           $this->_view->pagina = $pagina;
       }
        
        $this->getLibrary('paginador');
        $paginador = new Paginador();
        
        $id_persona = Session::get('id_persona');
        
        $autor = $this->_autor->getIdAutor($id_persona);
        
        
        $this->_view->manuscritos = $paginador->paginar($this->_manuscrito->getManuscritosAutor($autor['id_autor']), $pagina);
        
        //$this->_view->pagina = $pagina;
        
        $this->_view->paginacion = $paginador->getView('prueba', 'manuscrito/index');
        
        $this->_view->titulo = 'Manuscritos';
        $this->_view->renderizar('index', 'manuscrito');
    }
    
    //Modificar este metodo para que solo muestre el historial de un manuscrit en particulas
    
    public function misManuscritos($pagina = false, $id_manuscrito = false){
        
        Session::accesoEstricto(array('autor'));
        if($id_manuscrito != false && $this->filtrarInt($id_manuscrito) != 0){
            
            if(!$this->filtrarInt($pagina)){
                $pagina = false;
            }else{
                $pagina = (int) $pagina;
            }

            $this->getLibrary('paginador');
            $paginador = new Paginador();


            $id_persona = Session::get('id_persona');

            $autor = $this->_autor->getIdAutor($id_persona);

            $obras = $this->_manuscrito->getObraManuscrito($autor['id_autor']);

            //$autores = array();
            $this->_view->autores = array();

            
                
            $manusc = $this->_manuscrito->getManuscrito($id_manuscrito); // obtenemos el manuscrito
            if($manusc){     
                $id_autor = $this->_manuscrito->getObraAutor($manusc['id_obra']); //obtenemos el id_autor
                $iter = 0;
                while($fila = $id_autor->fetch()){
                    $temp = $this->_manuscrito->getAutor($fila['id_autor']); //obtenemos el id_persona
                    $persona = $temp['id_persona']; //lo asignamos
                    $datos_persona = $this->_manuscrito->getPersona($persona);
                    $array_autor['persona_'.$iter] = $datos_persona['nombre'] . " " . $datos_persona['apellido']; //almaceno los autores
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

            $this->_view->titulo = 'Mis manuscritos';
            $this->_view->renderizar('misManuscritos', 'manuscrito');
        }
        
    }
    
    public function insertar(){
        
        //validar
        Session::accesoEstricto(array('autor'));
        $arreglo = array();
        $arreglo["status"] = 1;
        
        for($i = 0; $i < $_POST["iter"]; $i++){
            
            if((int)$_POST["idx_autor"] != $i){
            
                $datos["nombre"] = "";
                $datos["apellido"] = "";
                $datos["pais"] = "";
                $datos["telefono"] = "";
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
                
                //materia
                
                if($this->getInt('materia') == 0){
                    $datos["materia"] = 'Debe seleccionar una materia';
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
                $arreglo["obra"] = $this->getInt('idioma') . " - " . $this->getSql('revista') ." - ". $this->getInt('materia');
                $this->_manuscrito->setObra($this->getInt('idioma'), $this->getSql('revista'), $this->getInt('materia'));
                $obra = $this->_manuscrito->getUltimaObra();
                
                //set manuscrito
                $this->_manuscrito->setManuscrito($this->getSql('titulo'), $this->getSql('resumen'), $obra['id_obra']);
                $manuscrito = $this->_manuscrito->getManuscritoObra($obra['id_obra']);
                
                $responsable = 0;
                $persona = 0;
                
                for($i = 0; $i < $_POST["iter"]; $i++){
            
                    if((int)$_POST["idx_autor"] != $i){
                        $this->_registro->setPersona($this->getSql('nombre'.$i), 
                                $this->getSql('apellido'.$i), 
                                $this->getPostParam('genero'.$i), 
                                $this->getPostParam('email'.$i), 
                                $this->getPostParam('telefono'.$i), 
                                $this->getInt('pais'.$i), 
                                $this->getSql('resumenBiografico'.$i));
                        
                        $persona = $this->_registro->getUltimaPersona();
                        
                        $this->_autor->setAutor($persona['id_persona']);
                        
                        $autor = $this->_autor->getIdAutor($persona['id_persona']);
                        
                        $this->_autor->setAutorObra($autor['id_autor'], $obra['id_obra']);
                        
                        $this->_registro->setPersonaRol($persona['id_persona'], 3);// 3 es el autor
                        
                        
                        if((int)$_POST["responsable"] == $i){
                                $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$persona['id_persona'], 3);
                                $responsable = $this->_manuscrito->getUltimoResponsable();
                        }
                        
                    }
                    
                    
                        
                    if((int)$_POST["idx_autor"] == $i){
                        
                        if(isset($_SESSION["id_persona"])){
                        
                            $autor = $this->_autor->getIdAutor($_SESSION["id_persona"]);

                            $this->_autor->setAutorObra($autor['id_autor'], $obra['id_obra']);
                        }
                        
                        if((int)$_POST["responsable"] == $i){
                        
                            if(isset($_SESSION["id_persona"])){
                                $this->_manuscrito->setResponsable($manuscrito['id_manuscrito'],$_SESSION["id_persona"], 3);
                                $responsable = $this->_manuscrito->getUltimoResponsable();
                            }
                        }
                            
                    }

                }
                
             
            
            
            //---------------------------------------
            
            $ruta = "manuscritos";
            
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777);
            }
            if(file_exists($ruta)){
                $ruta.= "/". $manuscrito["id_manuscrito"];
                mkdir($ruta, 0777);
            }
            
            $arreglo["id_manuscrito"] = $manuscrito["id_manuscrito"];
            
            $arreglo["ruta"] = $ruta;
            
            $count_fisico = $this->_manuscrito->getCountFisico();
            //$count_fisico = 0;
            $control_arch = 0;
            
            if($count_fisico == 0){
                $control_arch = 1;
            }else{
                $control_arch = $count_fisico + 1;
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

                $this->_manuscrito->setRevision($responsable['id_responsable'], 1, $fisico['id_fisico']);
                
                
            } else { 
            //echo "move_uploaded_file function failed"; 
                $arreglo["msj_file"] = "Error al subir el archivo";
                $arreglo["status"] = 0;
            }
            
            
            
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
        
        Session::accesoEstricto(array('autor'));
        
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