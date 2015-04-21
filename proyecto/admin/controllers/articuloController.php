<?php

class articuloController extends Controller{

	private $_persona;
	private $_manuscrito;
	private $_articulo;
	private $_ajax;
	private $_rol;

    public function __construct() {
        parent::__construct();
        $this->_persona = $this->loadModel('persona');
        $this->_manuscrito = $this->loadModel('manuscrito');
        $this->_articulo = $this->loadModel('articulo');
        $this->_ajax = $this->loadModel('ajax');
        $this->_rol = $this->loadModel('rol');
    }
    

    // Metodo para mostrar los articulos
    public function index(){

    }

    public function nuevoArticulo($id_articulo = false){

    	$this->_view->setJs(array('js_crear_articulo'));

    	$manuscrito = $this->_manuscrito->getManuscrito((int)$id_articulo);

	    $revista = $this->_ajax->getRevistas();
        $this->_view->revista = $revista;

        $areas = $this->_ajax->getAreas();
        $this->_view->areas = $areas;

        $idiomas = $this->_ajax->getIdiomas();
        $this->_view->idiomas = $idiomas;

    	if($id_articulo && $manuscrito){

    		$obra = $this->_manuscrito->getObra($manuscrito["id_obra"]);

    		$obra = $obra->fetch();

    		$this->_view->obra = $obra;



    		$this->_view->titulo_articulo = $manuscrito["titulo"];
    		$this->_view->resumen = $manuscrito["resumen"];
    		$this->_view->palabras_claves = $manuscrito["palabras_claves"];

    	}

    	$this->_view->id_articulo = (int)$id_articulo;

    	$this->_view->titulo = 'Nuevo Art&iacute;culo';
       	$this->_view->renderizar('nuevo', 'articulo');
    }

    public function crear(){



    	$arreglo = array();
        $arreglo["status"] = 1;


        if($this->getInt('id_articulo') == 0){

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
            //$this->_manuscrito->setObra($this->getInt('idioma'), $this->getSql('revista'), $this->getInt('area'));
            //$obra = $this->_manuscrito->getUltimaObra();
            
            //set manuscrito
            //$this->_manuscrito->setManuscrito($this->getSql('titulo'), $this->getSql('resumen'), $obra['id_obra'], $this->getSql('palabrasClave'));
            $manuscrito = null;


            if($this->getInt('id_articulo') > 0){

            	$manuscrito = $this->_manuscrito->getManuscrito($this->getInt('id_articulo'));

            	$this->_articulo->setArticulo($this->getSql('titulo'), $this->getSql('resumen'), $manuscrito["id_obra"], $this->getSql('palabrasClave'));

            	$ultimo = $this->_articulo->getUltimoArticulo();

            	//asignar autor

            	$rol = $this->_rol->getIdRol("Autor");

            	$arreglo["rol"] = $rol["id_rol"];


            	$autor_responsable = $this->_manuscrito->getResponsableManuscrito($this->getInt('id_articulo'), $rol["id_rol"]);

            	$this->_articulo->setArticuloPersona($ultimo["id_articulo"], $autor_responsable[0]["id_persona"], 1);

            	$arreglo["ide_persona"] = $autor_responsable[0]["id_persona"];
            	$arreglo["ide_art"] = $ultimo["id_articulo"];

            	$id_persona_responsable = $autor_responsable[0]["id_persona"];

            	//asignar co-autor

            	$rol = $this->_rol->getIdRol("Co-Autor");

            	$autor_responsable = $this->_manuscrito->getResponsableManuscrito($this->getInt('id_articulo'), $rol["id_rol"]);

            	for($i = 0; $i<count($autor_responsable); $i++){
            		$this->_articulo->setArticuloPersona($ultimo["id_articulo"], $autor_responsable[$i]["id_persona"], 0);
            	}


            }else{//crearArticulo


            	$this->_articulo->crearArticulo($this->getSql('titulo'), $this->getSql('resumen'), $this->getSql('revista'), $this->getInt('area'), $this->getInt('idioma'), $this->getSql('palabrasClave'));

            	$ultimo = $this->_articulo->getUltimoArticulo();

            	//crear los autores y co-autores y relacionarlos con la tabla articulo_autor

	            	// si la persona ya estaba registrada asignar el manuscrito
	            if($id_persona_responsable != ""){
	                $arreglo["test_idpersona"] = $id_persona_responsable;

            		$this->_articulo->setArticuloPersona($ultimo["id_articulo"], $id_persona_responsable, 1);



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

	                $rol = $this->_rol->getIdRol("Autor");

	                $this->_persona->setPersonaRol($persona['id_persona'], $rol["id_rol"]);

	                $this->_articulo->setArticuloPersona($ultimo["id_articulo"], $persona['id_persona'], 1);

	            }


		                        // agregar los co-autores
	            for($i = 0; $i < $_POST["iter"]; $i++){

	                
	                $temp = $this->_usuario->getEmail($this->getPostParam($this->getPostParam('email'.$i)));

	                if($temp){

	                    $this->_articulo->setArticuloPersona($ultimo["id_articulo"], $temp["id_persona"], 0);
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

	                    $rol = $this->_rol->getIdRol("Co-Autor");

	                    $this->_persona->setPersonaRol($persona['id_persona'], $rol["id_rol"]);
	                    

	                    $this->_articulo->setArticuloPersona($ultimo["id_articulo"], $persona['id_persona'], 0);

	                }

	            }

            }    

            $datos_persona = $this->_persona->getDatos($id_persona_responsable);

            $apellido = explode(" ", $datos_persona['apellido']);
            
            $ruta = "articulos";
            
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777);
            }
            $ruta .= '/' . $apellido[0] . "_" .$id_persona_responsable;

            if(!file_exists($ruta)){
                mkdir($ruta, 0777);
            }

            if(file_exists($ruta)){
                $ruta.= "/articulo_". $ultimo["id_articulo"];
                mkdir($ruta, 0777);
            }
            
            $arreglo["id_articulo"] = $ultimo["id_articulo"];
            
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

                $this->_articulo->setFisicoArticulo($ultimo["id_articulo"], $fisico["id_fisico"]);

                // $estatus = $this->_manuscrito->getEstatusPorNombre("Enviado");

                // $this->_manuscrito->setRevision($responsable['id_responsable'], $estatus['id_estatus'], $fisico['id_fisico']);
                
                
            } else { 
            //echo "move_uploaded_file function failed"; 
                $arreglo["msj_file"] = "Error al subir el archivo";
                $arreglo["status"] = 0;
            }
            
            
            
        }
        
        echo json_encode($arreglo);
    }

    public function editarArticulo(){
    	
    }

    public function eliminarArticulo(){
    	
    }

}

?>