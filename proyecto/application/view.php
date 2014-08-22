<?php

class View{
    private $_controlador;
    private $_js;
    private $_jsPublic;
    private $_cssPublic;
    
    public function __construct(Request $peticion){
        $this->_controlador = $peticion->getControlador();
        $this->_js = array();
        $this->_jsPublic = array();
        $this->_cssPublic = array();
    }
    
    public function renderizar($vista, $item = false){
        
        $menu_right = array();
        
        $menu_horizontal = array(
            array(
                'id' => 'index',
                'titulo' => 'Inicio',
                'enlace' => BASE_URL
            ),
            /*revistas*/
            array(
                'id' => 'revistas',
                'titulo' => 'Revistas',
                'enlace' => BASE_URL . 'revistas'
            ),
            
            array(
                'id' => 'buscar',
                'titulo' => 'Buscar',
                'enlace' => BASE_URL
            ),
            
            array(
                'id' => 'actual',
                'titulo' => 'Actual',
                'enlace' => BASE_URL
            ),
            
            array(
                'id' => 'archivos',
                'titulo' => 'Archivos',
                'enlace' => BASE_URL
            ),
            
            array(
                'id' => 'contacto',
                'titulo' => 'Contacto',
                'enlace' => BASE_URL
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
            'menu_horizontal' => $menu_horizontal,
            'menu_top' => $menu_top,
            'menu_left' => $menu_left,
            'menu_right' => $menu_right,
            'cssPublic' => $cssPublic,
            'js' => $js,
            'jsPublic' => $jsPublic,
            'root' => BASE_URL
        );

       // var_dump(ROOT);
        
        
        $rutaView = ROOT . 'views' . DS . $this->_controlador . DS . $vista . '.php';
        
        if(is_readable($rutaView)){
            include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'header.php';
            include_once $rutaView;
            include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'footer.php';
        }else{
            throw new Exception('Error de Vista');
        }
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