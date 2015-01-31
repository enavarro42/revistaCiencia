<?php
class indexController extends Controller{

    private $_reporte;
    
    public function __construct(){
        parent::__construct();

        $this->_reporte = $this->loadModel("reporte");
    }
    
    public function index(){
        // $post = $this->loadModel('post');

        $view_key = "index";

        if(!Session::get('autenticado_admin')){
        	$this->redireccionar('login');
        }

        $_acl = $this->_view->getAcl();

        $this->_view->acl = $_acl->getPermisoRol();

        if(array_key_exists($view_key, $this->_view->acl) == false && $this->_view->acl[$view_key]["estado"] == false){
            $this->redireccionar('acceso');
        }

        $result = $this->_reporte->getUsuarioPorArea();

        $usuario_area = "";

        for($i = 0; $i<count($result); $i++){
            if($i > 0) $usuario_area .= ",";
            $usuario_area .= "['" . trim($result[$i]["nombre"]) . "', ". $result[$i]["cantidad"]. "]";
        }

        $this->_view->usuario_area = $usuario_area;
        $this->_view->titulo_g1 = "Cantidad de Usuarios por Área";

        //Grafica 2

        $result = $this->_reporte->getRolPorPersona();

        $persona_rol = "";

        for($i = 0; $i<count($result); $i++){
            if($i > 0) $persona_rol .= ",";
            $persona_rol .= "['" . trim($result[$i]["rol"]) . "', ". $result[$i]["cantidad"]. "]";
        }

        $this->_view->persona_rol = $persona_rol;
        $this->_view->titulo_g2 = "Cantidad de Personas por Roles";


        $this->_view->setJs(array('controlCarousel'));
        
        $this->_view->titulo = 'Revistas Arbitradas';
        $this->_view->renderizar('index', 'inicio');
    }
}
?>