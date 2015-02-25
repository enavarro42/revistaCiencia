<?php

class View{
    private $_controlador;
    private $_js;
    private $_jsPublic;
    private $_cssPublic;
    private $_acl;
    
    public function __construct(Request $peticion, Acl $acl){
        $this->_controlador = $peticion->getControlador();
        $this->_js = array();
        $this->_jsPublic = array();
        $this->_cssPublic = array();
        $this->_acl = $acl;
    }
    
    public function renderizar($vista, $item = false){
        
        $menu_right = array();
        
        $menu_principal = array(
            array(
                'id' => 'index',
                'titulo' => 'Inicio',
                'enlace' => BASE_URL,
                'flecha' => false,
                'class' => "fa fa-file-text fa-fw"
            ),
            array(
                'id' => 'manuscrito',
                'titulo' => 'Manuscrito',
                'enlace' => '',
                'flecha' => true,
                'class' => "fa fa-file-text fa-fw"
            ),
            array(
                'id' => 'acl',
                'titulo' => 'Usuarios',
                'enlace' => '',
                'flecha' => true,
                'class' => "fa fa-users fa-fw"
            ),
            array(
                'id' => 'reportes',
                'titulo' => 'Reportes',
                'enlace' => '',
                'flecha' => true,
                'class' => "fa fa-line-chart fa-fw"
                )
        );

        $sub_menu_manuscrito = array(
            array(
                'id' => 'manuscrito',
                'titulo' => 'Manuscrito',
                'enlace' => BASE_URL . 'manuscrito'
            ),
            array(
                'id' => 'evaluacionArbitro',
                'titulo' => 'Evaluaci&oacute;nes del Manuscrito',
                'enlace' => BASE_URL . 'manuscrito/evaluaciones'
            )
        );

        $sub_menu_acl = array(
            array(
                'id' => 'usuario',
                'titulo' => 'Usuarios',
                'enlace' => BASE_URL . 'usuario'
            ),
            array(
                'id' => 'acl',
                'titulo' => 'Grupo de Usuario',
                'enlace' => BASE_URL . 'acl'
            )
        );

        $sub_menu_reportes = array(
            array(
                'id' => 'reporteUsuario',
                'titulo' => 'Reportes de usuarios',
                'enlace' => BASE_URL . 'reportes/usuario'
            ),
            array(
                'id' => 'reporteManuscrito',
                'titulo' => 'Reportes de manuscrito',
                'enlace' => BASE_URL . 'reportes/Manuscritos'
            )
        );
        
        if(Session::get('autenticado')){
            $menu_top = array(
                array(
                    'id' => 'perfil',
                    'titulo' => Session::get('usuario'),
                    'enlace' => BASE_URL . 'perfil',
                    'icon' => 'glyphicon glyphicon-user'
                ),
                
                array(
                    'id' => 'login',
                    'titulo' => 'Cerrar Sesi&oacute;n',
                    'enlace' => BASE_URL . 'login/cerrar',
                    'icon' => 'glyphicon glyphicon-log-out'
                )
             
            );
            
            
            $menu_right = array(

                    array(
                        'id' => 'usuario',
                        'titulo' => 'Usuario',
                        'enlace' => BASE_URL . 'usuario'
                    ),

                    array(
                        'id' => 'perfil',
                        'titulo' => 'Perfil',
                        'enlace' => BASE_URL . 'perfil'
                    )

                );
            
            if(isset($_SESSION['level'])){
                if(Session::get('level') == 'Autor'){
                    $menu_right = array(
                        array(
                            'id' => 'manuscrito',
                            'titulo' => 'Mis Manuscritos',
                            'enlace' => BASE_URL . 'manuscrito'
                        ),
                        array(
                            'id' => 'nuevo',
                            'titulo' => 'Nuevo Manuscritos',
                            'enlace' => BASE_URL . 'manuscrito/nuevo'
                        ),

                        array(
                            'id' => 'articulos',
                            'titulo' => 'Mis Art&iacute;culos',
                            'enlace' => BASE_URL . 'articulo'
                        ),

                        array(
                            'id' => 'usuario',
                            'titulo' => 'Usuario',
                            'enlace' => BASE_URL . 'usuario'
                        ),
                        
                        array(
                            'id' => 'perfil',
                            'titulo' => 'Perfil',
                            'enlace' => BASE_URL . 'perfil'
                        )

                    );
                }

                if(Session::get('level') == 'Arbitro'){
                    $menu_right = array(
                        array(
                            'id' => 'arbitro',
                            'titulo' => 'Arbitrar Manuscritos',
                            'enlace' => BASE_URL . 'arbitro'
                        ),

                        array(
                            'id' => 'usuario',
                            'titulo' => 'Usuario',
                            'enlace' => BASE_URL . 'usuario'
                        ),
                        
                        array(
                            'id' => 'perfil',
                            'titulo' => 'Perfil',
                            'enlace' => BASE_URL . 'perfil'
                        )

                    );
                }
            }
            
        }else{
            $menu_top = array(
                array(
                    'id' => 'login',
                    'titulo' => 'Iniciar Sesi&oacute;n',
                    'enlace' => BASE_URL . 'login',
                    'icon' => 'glyphicon glyphicon-log-in'
                ),
                array(
                    'id' => 'registro',
                    'titulo' => 'Registro',
                    'enlace' => BASE_URL . 'registro',
                    'icon' => 'glyphicon glyphicon-plus-sign'
                )
            );
        }
        
        $menu_left = array(
                array(
                    'id' => 'index',
                    'titulo' => 'Inicio',
                    'enlace' => BASE_URL
                ),                
                array(
                    'id' => 'oficina',
                    'titulo' => 'Oficina de Publicaciones',
                    'enlace' => BASE_URL . 'oficina'
                ),            
                array(
                    'id' => 'contacto',
                    'titulo' => 'Contacto',
                    'enlace' => BASE_URL
                )
             
            );
        
        
        
        Session::set('vista_actual', $this->_controlador);
        
        $js = array();
        $jsPublic = array();
        $cssPublic = array();
        
        if(count($this->_js)){
            $js = $this->_js;
        }
        
        if(count($this->_jsPublic)){
            $jsPublic = $this->_jsPublic;
        }
        
        if(count($this->_cssPublic)){
            $cssPublic = $this->_cssPublic;
        }
        
        $_layoutParams = array(
            'ruta_css' => BASE_URL . 'views/layout/'. DEFAULT_LAYOUT . '/css/',
            'ruta_img' => BASE_URL . 'views/layout/'. DEFAULT_LAYOUT . '/img/',
            'ruta_js' => BASE_URL . 'views/layout/'. DEFAULT_LAYOUT . '/js/',
            'menu_principal' => $menu_principal,
            'sub_menu_acl' => $sub_menu_acl,
            'sub_menu_manuscrito' => $sub_menu_manuscrito,
            'sub_menu_reportes' => $sub_menu_reportes,
            'menu_top' => $menu_top,
            'menu_left' => $menu_left,
            'menu_right' => $menu_right,
            'cssPublic' => $cssPublic,
            'js' => $js,
            'jsPublic' => $jsPublic,
            'root' => BASE_URL
        );

       // var_dump(ROOT);
        
        // var_dump(ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'header.php');
        
        $rutaView = ROOT . 'views' . DS . $this->_controlador . DS . $vista . '.php';

        if($this->_controlador == 'login' || $this->_controlador == 'registro'){
            include_once $rutaView;
        }else{
        
            if(is_readable($rutaView)){
                include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'header.php';
                include_once $rutaView;
                include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'footer.php';
            }else{
                throw new Exception('Error de Vista');
            }
        }
    }

    public function getAcl(){
        return $this->_acl;
    }
    
    public function setJs(array $js){
        if(is_array($js) && count($js)){
            for($i=0; $i<count($js); $i++){
                $this->_js[] = BASE_URL . 'views/' . $this->_controlador . '/js/' . $js[$i] . '.js';
            }
        }else{
            throw new Exception('Error de js');
        }
    }
    
    public function setJsPublic(array $js){
        if(is_array($js) && count($js)){
            for($i=0; $i<count($js); $i++){
                $this->_jsPublic[] = BASE_URL . 'public/js/' . $js[$i] . '.js';
            }
        }else{
            throw new Exception('Error de js');
        }
    }
    
    
    public function setCssPublic(array $css){
        if(is_array($css) && count($css)){
            for($i=0; $i<count($css); $i++){
                $this->_cssPublic[] = BASE_URL . 'public/css/' . $css[$i] . '.css';
            }
        }else{
            throw new Exception('Error de css');
        }
    }
}

?>