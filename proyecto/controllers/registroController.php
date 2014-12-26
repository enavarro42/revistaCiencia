<?php

class registroController extends Controller{
    
    private $_registro;
    
    public function __construct(){
        parent::__construct();
        
        $this->_registro = $this->loadModel('registro');
        $this->_rol = $this->loadModel('rol');
    }
    
    public function index(){
        if(Session::get('autenticado')){
            $this->redireccionar();
        }
        
        $this->_view->setJs(array('validarRegistro'));
        
        $this->_view->titulo = "Registro";
        $this->_view->paises = $this->_registro->getPaises();

        $arregloCuentas = array();


        // $rol= $this->_rol->getIdRol('Arbitro');
        // $arregloCuentas[] = $rol[0];

        $rol = $this->_rol->getIdRol('Autor');
        $arregloCuentas[] = $rol[0];

        $this->_view->areas = $this->_registro->getAllAreas();

        $this->_view->cuentas = $arregloCuentas;
            
        if($this->getInt('enviar') == 1){
            $this->_view->datos = false;
            $this->_view->datos = $_POST;
            
            $validado = true;
            
            
            if(!$this->getAlphaNum('usuario')){
                $this->_view->_error_usuario = 'Debe introducir su nombre de usuario';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            
                        
            if(!(strlen($this->getAlphaNum('usuario')) > 3)){
                $this->_view->_error_usuario = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            //^[a-zA-Z]((\.|_|-)?[a-zA-Z0-9]+){3}$
            $exp = '/^[a-zA-Z0-9-_]+$/';
            
            if(!preg_match($exp, $this->getPostParam('usuario'))){
                $this->_view->_error_usuario = 'nombre de usuario inv&aacute;lido';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;

            }

            
            if($this->_registro->verificarUsuario($this->getAlphaNum('usuario'))){
                $this->_view->_error_usuario = 'El usuario '. $this->getAlphaNum('usuario') . ' ya existe';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            
            
            if(!$this->getSql('pass')){
                $this->_view->_error_pass = 'Debe introducir su password';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            
            if(!(strlen($this->getSql('pass')) > 3)){
                $this->_view->_error_pass = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            
            if($this->getPostParam('pass') != $this->getPostParam('confirmar')){
                $this->_view->_error_pass_confir = 'Los password no coinciden';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;
                var_dump("Error 7");
            }
      
            if(!$this->getSql('primerNombre')){
                $this->_view->_error_primerNombre = 'Debe introducir su nombre';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            
            if(!(strlen($this->getSql('primerNombre')) > 2)){
                $this->_view->_error_primerNombre = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            
            $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
            
            if(!preg_match($exp, $this->getPostParam('primerNombre'))){
                $this->_view->_error_primerNombre = 'Nombre inv&aacute;lido';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;

            }
                
            $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

            if(!$this->getSql('apellido')){
                $this->_view->_error_apellido = 'Debe introducir su apellido';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            
            if(!(strlen($this->getSql('apellido')) > 3)){
                $this->_view->_error_apellido = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            
            
            if(!preg_match($exp, $this->getPostParam('apellido'))){
                $this->_view->_error_apellido = 'Apellido inv&aacute;lido';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;
            }

            if(!$this->getPostParam('email')){
                $this->_view->_error_email = 'Debe introducir su cuenta de correo electr&oacute;nico';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;
            }
            
            if(!$this->validarEmail($this->getPostParam('email'))){
                $this->_view->_error_email = 'La direcci&oacute;n de email es inv&aacute;lida';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;
            }
            
            if($this->_registro->verificarEmail($this->getPostParam('email'))){
                $this->_view->_error_email = 'Esta direcci&oacute;n de email ya est&aacute; registrada';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;
            }

            $exp = '/^\\d{11,14}$/';
            
            if(!preg_match($exp, $this->getPostParam('telefono'))){
                $this->_view->_error_telefono = 'Debe proporcionar un n&uacute;mero de tel&eacute;fono v&aacute;lido';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;
            }
            
            if($this->getInt('pais') == 0){
                $this->_view->_error_pais = 'Debe seleccionar un pa&iacute;s';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;
            }
            
            
            if(!$this->validarParam('cuenta')){
                $this->_view->_error_cuenta = 'Debe seleccionar un tipo de cuenta';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;

            }

            //areas_seleccionados
            if(!$this->validarParam('check_areas')){
                $this->_view->_error_areas = "Debe seleccionar al menos un &aacute;rea.";
                $validado = false; 
            }

            if(!$this->getSql('din')){
                $this->_view->_error_din = 'Debe introducir su DIN';
                $validado = false;
            }

            if($this->_registro->verificarDin($this->getSql('din'))){
                $this->_view->_error_din = 'El usuario con el DIN: '. $this->getSql('din') .' ya se ha registado';
                $validado = false;
            }

            if($this->validarParam('check_areas')){
                $areas_seleccionados = $_POST['check_areas'];
                $this->_view->areas_seleccionados = $areas_seleccionados;
            }
            
            if($validado){
            
                $this->getLibrary('class.phpmailer');
                $mail = new PHPMailer();

                $this->_registro->registrarUsuario(
                        $this->getSql('primerNombre'),
                        $this->getSql('apellido'),
                        $this->getSql('din'),
                        $this->getPostParam('genero'),
                        $this->getPostParam('telefono'),
                        $this->getInt('pais'),
                        $this->getSql('resumenBiografico'),
                        $this->getSql('filiacion'),
                        $this->getSql('segundoNombre'),
                        $this->getPostParam('check_areas'),
                        $this->getAlphaNum('usuario'),
                        $this->getSql('pass'),
                        $this->getPostParam('email'),
                        $this->getPostParam('cuenta')
                        );


                $usuario = $this->_registro->verificarUsuario($this->getAlphaNum('usuario'));

                 if(!$usuario){
                    $this->_view->_error = 'Error al registrar el usuario';
                    $this->_view->renderizar('index', 'registro');
                    //exit;
                }



                $mail->From = 'www.fecRevistasCientificas.com';
                $mail->FromName = 'Revistas Cient&iacute;ficas';
                $mail->Subject = 'Revistas FEC';
                $mail->Body = 'Hola, <strong>'.$this->getSql('primerNombre').' ' .$this->getSql('apellido'). '</strong>' .
                        '<p> Se ha registrado en www.fecRevistasCientificas.com para activar '.
                        'su cuenta haga clic sobre el siguiente enlace:<br />'.
                        '<a href="' . BASE_URL . 'registro/activar/'.
                        $usuario['id_usuario'] . '/' . $usuario['codigo'] . '">' .
                        BASE_URL . 'registro/activar/'.
                        $usuario['id_usuario'] . '/' . $usuario['codigo'] .'</a>';
                $mail->AltBody = "Su servidor de correo no soporta html";
                $mail->addAddress($this->getPostParam('email'));
                $mail->Send();

                $this->_view->datos = false;
                //$this->_view->_mensaje = 'Registro Completado, revise su email para activar su cuenta';
                $this->_view->renderizar('activacion', 'registro');
            }//fin if validado
            else{
                $this->_view->renderizar('index', 'registro');
            }
        }
        $this->_view->renderizar('index', 'registro');
    }
    
    public function comprobarUsuario(){
        $resp['resp'] = "<span style='color:green;'>Disponible...!</span>";

        $exp = '/^[a-zA-Z0-9-_]+$/';
            
        if(!preg_match($exp, $_POST['usuario'])){
            $resp['resp'] = 'nombre de usuario inv&aacute;lido';
        }
        
        if($_POST['usuario'] == "") $resp['resp'] = "";
        
        if($this->_registro->verificarUsuario($this->getAlphaNum('usuario'))){
            $resp['resp'] = 'El usuario '. $this->getAlphaNum('usuario') . ' ya existe';
        }

        if(!(strlen($this->getAlphaNum('usuario')) > 3)){
            $resp['resp'] = 'Introduzca como m&iacute;nimo 4 car&aacute;cteres';
        }


        echo $resp['resp'];
    }
    
    public function comprobarEmail(){
        
        
        $resp['resp'] = "";

        if(!$this->validarEmail($this->getPostParam('email'))){
            $resp['resp'] = 'La direcci&oacute;n de correo es inv&aacute;lida';
        }

        if($_POST['email'] == "") $resp['resp'] = "";
        
            
        if($this->_registro->verificarEmail($this->getPostParam('email'))){
            $resp['resp'] = 'Esta direcci&oacute;n de correo ya est&aacute; registrada';
        }
            
    
        echo $resp['resp'];
    }
    
    public function activar($id, $codigo){
        if(!$this->filtrarInt($id) || !$this->filtrarInt($codigo)){
            $this->_view->_error = "Esta cuenta no existe";
            $this->_view->_mensaje_class = 'class="alert alert-danger"';
            $this->_view->renderizar('activar', 'registro');
        }
        $row = $this->_registro->getUsuario(
                $this->filtrarInt($id),
                $this->filtrarInt($codigo)
                );
        
        if(!$row){
            $this->_view->_error = "Esta cuenta no existe";
            $this->_view->_mensaje_class = 'class="alert alert-danger"';
            $this->_view->renderizar('activar', 'registro');
        }
        
        if($row['estado'] == 1){
            $this->_view->_mensaje_activacion = "Esta cuenta ya ha sido activada";
            $this->_view->_mensaje_class = 'class="alert alert-info"';
            $this->_view->renderizar('activar', 'registro');
        }
        
        $this->_registro->activarUsuario(
                $this->filtrarInt($id),
                $this->filtrarInt($codigo)
                );
        
        $row = $this->_registro->getUsuario(
                $this->filtrarInt($id),
                $this->filtrarInt($codigo)
                );
        
        if($row['estado'] == 0){
            $this->_view->_error = "Error al activar tu cuenta, por favor intentar m&aacute;s tarde";
            $this->_view->_mensaje_class = 'class="alert alert-danger"';
            $this->_view->renderizar('activar', 'registro');
        }
        
         $this->_view->_mensaje_activacion = "Su cuenta ha sido activada";
         $this->_view->_mensaje_class = 'class="alert alert-success"';
         $this->_view->renderizar('activar' , 'registro');
    }
    
}

?>