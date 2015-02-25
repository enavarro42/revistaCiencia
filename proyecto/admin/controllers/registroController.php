<?php

class registroController extends Controller{
    
	private $_usuario;
	private $acl;

	public function __construct(){
        parent::__construct();
        $this->_usuario = $this->loadModel('usuario');
        $this->acl = $this->loadModel('acl');
    }
    
    public function index(){

        if(isset($_SESSION['autenticado_admin'])){
             $this->redireccionar();
        }else if($this->_usuario->getUsuarios()){
            $this->redireccionar("login");
        }

        // $view_key = "insertar_usuario";

        // if(!Session::get('autenticado_admin')){
        //     $this->redireccionar('login');
        // }

        // $_acl = $this->_view->getAcl();

        // $this->_view->acl = $_acl->getPermisoRol();


        // if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
        //     $this->redireccionar('acceso');
        // }

        $this->_view->tipoAccion = 'insertar';

        $this->_view->cajaPass = 'block';
        $this->_view->linkCambiarPass = 'none';

        $this->_view->setJs(array('validarForm'));

        $this->_view->roles = $this->acl->getRoles();

        $this->_view->paises = $this->acl->getPaises();

        $this->_view->areas = $this->_usuario->getAllAreas();

        if($this->getInt("enviar") == 1){

            if(isset($_POST['check_rol'])){
                $roles_seleccionados = $_POST['check_rol'];
                $this->_view->roles_seleccionados = $roles_seleccionados;
            }

            if(isset($_POST['check_areas'])){
                $areas_seleccionados = $_POST['check_areas'];
                $this->_view->areas_seleccionados = $areas_seleccionados;
            }


            $this->_view->datos = $_POST;

            //var_dump($_POST);

            $validado = true;

            //si esta vacio el rol

            if(empty($_POST['check_rol'])){
                $this->_view->_error_rol = 'Debe seleccionar por lo menos un rol';
                $validado = false;
            }
            
            
            if(!$this->getAlphaNum('usuario')){
                $this->_view->_error_usuario = 'Debe introducir su nombre de usuario';
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
                $validado = false;

            }

            
            if($this->_usuario->verificarUsuario($this->getAlphaNum('usuario'))){
                $this->_view->_error_usuario = 'El usuario '. $this->getAlphaNum('usuario') . ' ya existe';
                $validado = false;

            }

            // contraseña aleatoria
            $arreglo = $_POST;

            if($_POST['optionsRadios'] == 'option1'){
                $psswd = substr( md5(microtime()), 1, 8);

                $arreglo['pass'] = $psswd;
                $arreglo['confirmar'] = $psswd;
            }


            

            // asignar contraseña

            if($_POST['optionsRadios'] == 'option2'){
            
                if(!$this->getSql('pass')){
                    $this->_view->_error_pass = 'Debe introducir su password';
                    $validado = false;

                }
                
                if(!(strlen($this->getSql('pass')) > 3)){
                    $this->_view->_error_pass = 'Por favor introduzca como m&iacute;nimo 4 car&aacute;cteres';
                    $validado = false;

                }
                
                if($this->getPostParam('pass') != $this->getPostParam('confirmar')){
                    $this->_view->_error_pass_confir = 'Los password no coinciden';
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

            //--------------------------Segundo Nombre----------------------------
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

                    //Apellido 
        
        if(!$this->getSql('apellido')){
            $this->_view->_error_apellido = 'Debe introducir su apellido';
            $validado = false;
        }

        if(!(strlen($this->getSql('apellido')) > 2)){
            $this->_view->_error_apellido = 'Por favor introduzca como m&iacute;nimo 3 car&aacute;cteres';
            $validado = false;
        }

        $test_apellido1 = true;
        $test_apellido2 = true;

        $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?((|\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';

        if(!preg_match($exp, $this->getPostParam('apellido'))){
            $test_apellido1 = false;
        }


        $exp = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*$/';
        
        
        if(!preg_match($exp, $this->getPostParam('apellido'))){
            // $this->_view->_error_apellido = 'Apellido inv&aacute;lido';
            //$validado = false;
            $test_apellido2 = false;
        }

        if($test_apellido1 == false && $test_apellido2 == false){
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
            
            if($this->_usuario->verificarEmail($this->getPostParam('email'))){
                $this->_view->_error_email = 'Esta direcci&oacute;n de email ya est&aacute; registrada';
                $validado = false;
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

            if(empty($_POST['check_areas'])){
                $this->_view->_error_area = 'Debe seleccionar por lo menos un &aacute;rea';
                $validado = false;
            }

            // if(!$this->getSql('din')){
            //     $this->_view->_error_din = 'Debe introducir su DIN';
            //     $validado = false;
            // }
            if($this->getSql('din') != ''){
                if($this->_usuario->verificarDin($this->getSql('din'))){
                    $this->_view->_error_din = 'El usuario con el DIN: '. $this->getSql('din') .' ya se ha registado';
                    $validado = false;
                }
            }

            if(!isset($_POST['genero'])){
                $this->_view->_error_genero = 'Debe seleccionar un g&eacute;nero.';
                $validado = false;
            }


             // var_dump($arreglo);

            if($validado){
                //var_dump("es valido...! :)");
                $this->_usuario->setUsuario($arreglo);

                $this->getLibrary('class.phpmailer');
                $mail = new PHPMailer();

                $usuario = $this->_usuario->verificarUsuario($this->getAlphaNum('usuario'));

                 if(!$usuario){
                    $this->_view->_error = 'Error al registrar el usuario';
                    $this->_view->renderizar('index', 'registro');
                    //exit;
                }



                $mail->From = 'www.fecRevistasCientificas.com';
                $mail->FromName = 'Revistas Cient&iacute;ficas';
                $mail->Subject = 'Revistas FEC';
                $mail->Body = 'Hola, <strong>'.$this->getSql('primerNombre').' ' .$this->getSql('apellido'). '</strong>' .
                        '<p><strong>Usuario: </strong>'.$this->getSql('usuario').'</p>'.
                        '<p><strong>Clave: </strong>'.$this->getSql('pass').'</p>';
                $mail->AltBody = "Su servidor de correo no soporta html";
                $mail->addAddress($this->getPostParam('email'));
                $mail->Send();

                $this->redireccionar('usuario');
            }

        }
        

        $this->_view->titulo = 'Registro';
        $this->_view->renderizar('registro');
    }

}

?>