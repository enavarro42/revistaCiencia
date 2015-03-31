<?php
class indexController extends Controller{

	private $_registro;
	private $_persona;

    
    public function __construct(){
        parent::__construct();
        $this->_registro = $this->loadModel('registro');
        $this->_persona = $this->loadModel('persona');
    }
    
    public function index(){
        $post = $this->loadModel('post');

        $this->_view->setJs(array('controlCarousel'));
        
        $this->_view->titulo = 'Revistas Arbitradas';
        $this->_view->renderizar('index', 'inicio');
    }

    public function recuperarPassword(){

    	$validado = true;

    	$this->_view->setJs(array('recuperarPass'));

    	if(isset($_POST["enviado"])){

    			$persona = '';

	    		if(!$this->getPostParam('correo')){
	                $this->_view->_error_email = 'Debe introducir su cuenta de correo electr&oacute;nico';
	                $validado = false;
	            }
	            
	            else if(!$this->validarEmail($this->getPostParam('correo'))){
	                $this->_view->_error_email = 'La direcci&oacute;n de correo es inv&aacute;lida';
	                $validado = false;
	            }

	            else if(!$persona = $this->_persona->getPersonaByEmail(trim($this->getPostParam('correo')))){
	            	$validado = false;
	            	$this->_view->_error_email = 'La direcci&oacute;n de correo no esta registrada en el sistema.';
	            }

	            if($validado){

	            	$psswd = substr( md5(microtime()), 1, 8);

	            	$this->_view->correo = trim($this->getPostParam('correo'));

	            	$this->_registro->updatePass($persona['id_persona'], $psswd);

	    			$this->getLibrary('class.phpmailer');
	                $mail = new PHPMailer();

					$mail->From = 'www.fecRevistasCientificas.com';
	                $mail->FromName = 'Revistas Cientificas';
	                $mail->Subject = 'Revistas FEC';
	                $mail->Body = 'Saludos, la contrase&ntilde;a, para la cuenta '.$this->getPostParam("correo").', ha sido cambiada con &eacute;xito.'. 
	                '<br />La nueva contrase&ntilde;a es: <strong>'.$psswd.'</strong> ';
	                $mail->AltBody = "Su servidor de correo no soporta html";
	                $mail->addAddress($this->getPostParam('correo'));
	                $mail->Send();

	                $this->_view->renderizar('envioPassword', 'index');
	            }
    	}

    	$this->_view->titulo = 'Recuperar contraseña';
        $this->_view->renderizar('recuperarPassword', 'inicio');
    }
}
?>