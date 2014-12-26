<?php

class perfilController extends Controller{
    
    private $_perfil;
    
    public function __construct(){
        parent::__construct();
        
        $this->_perfil = $this->loadModel('perfil');
    }
    
    public function index(){

        $this->_view->display_msj = 'hide_msj';
        
        if(!Session::get('autenticado')){
            header('location:' . BASE_URL . 'error/access/5050');
            exit;
        }else{

            $this->_view->paises = $this->_perfil->getPaises();
            $id_persona = $_SESSION['id_persona'];

            $persona = $this->_perfil->getPersona($this->filtrarInt($id_persona));
            $usuario = $this->_perfil->getUsuario($this->filtrarInt($id_persona));

            if($this->getInt('enviar') == 1){


                $this->_view->datos = $_POST;

                $validado = true;
                if(!$this->getAlphaNum('usuario')){
                    $this->_view->_error_usuario = 'Debe introducir su nombre de usuario';
                    $validado = false;

                }
                
                            
                if(!(strlen($this->getAlphaNum('usuario')) > 3)){
                    $this->_view->_error_usuario = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                    //$this->_view->renderizar('index', 'registro');
                    $validado = false;

                }

                $exp = '/^[a-zA-Z0-9-_]+$/';
                
                if(!preg_match($exp, $this->getPostParam('usuario'))){
                    $this->_view->_error_usuario = 'nombre de usuario inv&aacute;lido';
                    $validado = false;

                }

                if(trim($this->getAlphaNum('usuario')) != trim($usuario['usuario'])){

                
                    if($this->_usuario->verificarUsuario($this->getAlphaNum('usuario'))){
                        $this->_view->_error_usuario = 'El usuario '. $this->getAlphaNum('usuario') . ' ya existe';
                        $validado = false;

                    }
                }
                if(!$this->getSql('primerNombre')){
                    $this->_view->_error_primerNombre = 'Debe introducir su nombre';
                    $validado = false;

                }
                
                if(!(strlen($this->getSql('primerNombre')) > 2)){
                    $this->_view->_error_primerNombre = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                    $validado = false;

                }
                
                $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
                
                if(!preg_match($exp, $this->getPostParam('primerNombre'))){
                    $this->_view->_error_primerNombre = 'Nombre inv&aacute;lido';
                    $validado = false;

                }


                if($this->getSql('segundoNombre') != ''){
                    if(!(strlen($this->getSql('segundoNombre')) > 2)){
                        $this->_view->_error_segundoNombre = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                        $validado = false;

                    }
                    
                    $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
                    
                    if(!preg_match($exp, $this->getPostParam('segundoNombre'))){
                        $this->_view->_error_segundoNombre = 'Segundo nombre inv&aacute;lido';
                        $validado = false;

                    }
                }

                //apellido solo

                $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

                if(!$this->getSql('apellido')){
                    $this->_view->_error_apellido = 'Debe introducir su apellido';
                    $validado = false;

                }
                
                if(!(strlen($this->getSql('apellido')) > 3)){
                    $this->_view->_error_apellido = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                    $validado = false;

                }

                $pivote = 0;
                
                
                if(!preg_match($exp, $this->getPostParam('apellido'))){
                    
                    // $validado = false;
                    $pivote = 1;
                }

                // dos apellidos
                    
                $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
                
                
                if(!preg_match($exp, $this->getPostParam('apellido'))){
                    // $this->_view->_error_apellido = 'Apellido inv&aacute;lido';
                    // $validado = false;
                    $pivote = 1;
                    var_dump("entroooo");
                }else{
                    $pivote = 0;
                }

                if($pivote){
                    $this->_view->_error_apellido = 'Apellido inv&aacute;lido';
                    $validado = false;
                }

                if(!$this->getPostParam('email')){
                    $this->_view->_error_email = 'Debe introducir su cuenta de correo electr&oacute;nico';
                    $validado = false;
                }
                
                if(!$this->validarEmail($this->getPostParam('email'))){
                    $this->_view->_error_email = 'La direcci&oacute;n de email es inv&aacute;lida';
                    $validado = false;
                }


                if(trim($this->getPostParam('email')) != trim($persona['email'])){
                
                    if($this->_usuario->verificarEmail($this->getPostParam('email'))){
                        $this->_view->_error_email = 'Esta direcci&oacute;n de email ya est&aacute; registrada';
                        $validado = false;
                    }
                }

                $exp = '/^\\d{11,14}$/';

                if($this->getPostParam('telefono') != ''){
                
                    if(!preg_match($exp, $this->getPostParam('telefono'))){
                        $this->_view->_error_telefono = 'Debe proporcionar un n&uacute;mero de tel&eacute;fono v&aacute;lido';
                        $validado = false;
                    }
                }
                
                if($this->getInt('pais') == 0){
                    $this->_view->_error_pais = 'Debe seleccionar un pa&iacute;s';
                    $validado = false;
                }
                
                if(trim($this->getAlphaNum('din')) != trim($persona['din'])){

                    if($this->_usuario->verificarDin($this->getSql('din'))){
                        $this->_view->_error_din = 'El usuario con el DIN: '. $this->getSql('din') .' ya se ha registado';
                        $validado = false;
                    }
                }



                //------------------cambio de contraseña-----------------

                if($this->getSql('pass_actual') != '' && $this->getSql('pass') != '' && $this->getSql('confirmar') != ''){
                    if(Hash::getHash('md5', $this->getSql('pass_actual'), HASH_KEY) == trim($usuario['pass'])){

                        if(!$this->getSql('pass')){
                            $this->_view->_error_pass = 'Debe introducir su contrase&ntilde;a';
                            $validado = false;

                        }
                        
                        if(!(strlen($this->getSql('pass')) > 3)){
                            $this->_view->_error_pass = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                            $validado = false;

                        }

                        if($this->getSql('pass') != $this->getSql('confirmar')){
                            $this->_view->_error_pass_confir = 'Las contrase&ntilde;as no coinciden';
                            $validado = false;
                        }
                    }else{
                        $this->_view->mensaje_actualizacion = 'La contrase&ntilde;a actual no coincide.';
                        $this->_view->tipoMensaje = 'danger';
                        $this->_view->display_msj = 'show_msj';
                        $validado = false;
                    }

                }

                if($validado){
                    $this->_view->mensaje_actualizacion = 'Se han actualizado sus datos';
                    $this->_view->tipoMensaje = 'success';
                    $this->_view->display_msj = 'show_msj';

                    $this->_perfil->editarPerfil($id_persona, $_POST);
                }



            }else{

                $datos = array();

                if($persona && $usuario){
                    $datos['primerNombre'] = trim($persona['primerNombre']);
                    $datos['apellido'] = trim($persona['apellido']);
                    $datos['genero'] = $persona['genero'];
                    $datos['email'] = trim($persona['email']);
                    $datos['telefono'] = trim($persona['telefono']);
                    $datos['pais'] = $persona['pais'];
                    $datos['resumenBiografico'] = trim($persona['resumenBiografico']);
                    $datos['din'] = trim($persona['din']);
                    $datos['filiacion'] = trim($persona['filiacion']);
                    $datos['segundoNombre'] = trim($persona['segundoNombre']);

                    $datos['usuario'] = trim($usuario['usuario']);

                    $this->_view->datos = $datos;
                }

            }

            



        }

        $this->_view->titulo = "Perfil";
        $this->_view->renderizar('index', 'perfil');


    }

}

?>