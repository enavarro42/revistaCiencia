<?php

class registroController extends Controller{
    
    private $_registro;
    
    public function __construct(){
        parent::__construct();
        
        $this->_registro = $this->loadModel('registro');
    }
    
    public function index(){
        if(Session::get('autenticado')){
            $this->redireccionar();
        }
        
        $this->_view->setJs(array('validarRegistro'));
        
        $this->_view->titulo = "Registro";
        $this->_view->paises = $this->_registro->getPaises();
            
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
            $exp = '/^[a-zA-Z0-9áéíóúÁÉÍÓÚ_]+$/';
            
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
      
            if(!$this->getSql('nombre')){
                $this->_view->_error_nombre = 'Debe introducir su nombre';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            
            if(!(strlen($this->getSql('nombre')) > 2)){
                $this->_view->_error_nombre = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            
            $exp = '/^[a-zA-ZáéíóúÁÉÍÓÚ]+$/';
            
            if(!preg_match($exp, $this->getPostParam('nombre'))){
                $this->_view->_error_nombre = 'Nombre inv&aacute;lido';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;

            }
                
            
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
            
            $exp = '/^[a-zA-ZáéíóúÁÉÍÓ]+$/';
            
            if(!preg_match($exp, $this->getPostParam('apellido'))){
                $this->_view->_error_apellido = 'Apellido inv&aacute;lido';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;
            }
            
            if(!$this->validarEmail($this->getPostParam('email'))){
                $this->_view->_error_email = 'La direccion de email es inv&aacute;lida';
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
                $this->_view->_error_pais = 'Debe seleccionar un pais';
                //$this->_view->renderizar('index', 'registro');
                $validado = false;
            }
            
            
            if(!$this->validarParam('cuenta')){
                $this->_view->_error_cuenta = 'Debe seleccionar por lo menos un tipo de cuenta';
               // $this->_view->renderizar('index', 'registro');
                $validado = false;

            }
            
            if($validado){
            
                $this->getLibrary('class.phpmailer');
                $mail = new PHPMailer();

                $this->_registro->registrarUsuario(
                        $this->getSql('nombre'),
                        $this->getSql('apellido'),
                        $this->getPostParam('genero'),
                        $this->getPostParam('telefono'),
                        $this->getInt('pais'),
                        $this->getSql('resumenBiografico'),
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
                $mail->FromName = 'Revistas Cientificas';
                $mail->Subject = 'Revistas FEC';
                $mail->Body = 'Hola <strong>'.$this->getSql('nombre'). '</strong>' .
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
        
        if($_POST['usuario'] == "") $resp['resp'] = "";
        
        if($this->_registro->verificarUsuario($this->getAlphaNum('usuario'))){
            $resp['resp'] = 'El usuario '. $this->getAlphaNum('usuario') . ' ya existe';
        }
        echo $resp['resp'];
    }
    
    public function comprobarEmail(){
        
        
        $resp['resp'] = "";
            
        if($this->_registro->verificarEmail($this->getPostParam('email'))){
            $resp['resp'] = 'Esta direcci&oacute;n de email ya est&aacute; registrada';
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
            $this->_view->_error = "Error al activar tu cuenta, por favor intentar mas tarde";
            $this->_view->_mensaje_class = 'class="alert alert-danger"';
            $this->_view->renderizar('activar', 'registro');
        }
        
         $this->_view->_mensaje_activacion = "Su cuenta ha sido activada";
         $this->_view->_mensaje_class = 'class="alert alert-success"';
         $this->_view->renderizar('activar/' , 'registro');
    }
    
}

?>