<?php

class postController extends Controller{
    
    private $_post;
    
    public function __construct(){
        parent::__construct();
        $this->_post = $this->loadModel('post');
    }
    
    public function index($pagina = false){
        
       /* for($i = 0; $i < 300; $i++){
            $model = $this->loadModel("post");
            $model->insertarPost('titulo' . $i, 'cuerpo' . $i);
        }*/
        
        if(!$this->filtrarInt($pagina)){
            $pagina = false;
        }else{
            $pagina = (int) $pagina;
        }
        
        $this->getLibrary('paginador');
        $paginador = new Paginador();
        
        $this->_view->posts = $paginador->paginar($this->_post->getPosts(), $pagina);
        $this->_view->paginacion = $paginador->getView('prueba', 'post/index');
        $this->_view->titulo = 'Post';
        $this->_view->renderizar('index', 'post');
    }
    
    public function nuevo(){
        //Session::accesoEstricto(array('usuario'));
        
        $this->_view->titulo = "Nuevo Post";
        
        $this->_view->setJs(array('nuevo'));
        
        if($this->getInt('guardar') == 1){
            
            $this->_view->datos = $_POST;
            
            if(!$this->getTexto('titulo')){
                $this->_view->_error = 'Debe introducir el titulo del post';
                $this->_view->renderizar('nuevo', 'post');
                exit;
            }
            
            if(!$this->getTexto('cuerpo')){
                $this->_view->_error = 'Debe introducir el cuerpo del post';
                $this->_view->renderizar('nuevo', 'post');
                exit;
            }
            
            $imagen = '';

            if(isset($_FILES['imagen'])){
                $this->getLibrary('upload' . DS . 'class.upload');
                $ruta = ROOT . 'public' . DS . 'img'. DS . 'post' . DS;
                $upload = new Upload($_FILES['imagen'], 'es_ES');
                $upload->allowed = array('image/*');
                $upload->file_new_name_body = 'upl_' . uniqid();
                $upload->Process($ruta);
                
                if($upload->processed){
                    $imagen = $upload->file_dst_name;
                    $thumb = new Upload($upload->file_dst_pathname);
                    $thumb->image_resize = true;
                    $thumb->image_x = 100;
                    $thumb->image_y = 70;
                    $thumb->file_name_body_pre = 'thumb_';
                    $thumb->Process($ruta . 'thumb' . DS);
                    
                }else{
                    $this->_view->_error = $upload->error;
                    $this->_view->renderizar('nuevo', 'post');
                    exit;
                }
            }
            
            $this->_post->insertarPost(
                    $this->getPostParam('titulo'),
                    $this->getPostParam('cuerpo'),
                    $imagen
                    );
            
            $this->redireccionar('post');
        }
        
        $this->_view->renderizar('nuevo', 'post');
    }
    
    public function editar($id){
        if(!$this->filtrarInt($id)){
            $this->redireccionar('post');
        }
        
        if(!$this->_post->getPost($this->filtrarInt($id))){
            $this->redireccionar('post');
        }
        
        $this->_view->titulo = "Editar Post";
        $this->_view->setJs(array('nuevo'));
        
        
        if($this->getInt('guardar') == 1){
            
            $this->_view->datos = $_POST;
            
            if(!$this->getTexto('titulo')){
                $this->_view->_error = 'Debe introducir el titulo del post';
                $this->_view->renderizar('editar', 'post');
                exit;
            }
            
            if(!$this->getTexto('cuerpo')){
                $this->_view->_error = 'Debe introducir el cuerpo del post';
                $this->_view->renderizar('editar', 'post');
                exit;
            }
            
            $this->_post->editarPost(
                    $this->filtrarInt($id),
                    $this->getPostParam('titulo'),
                    $this->getPostParam('cuerpo')
                    );
            
            $this->redireccionar('post');
        }
        
        $this->_view->datos = $this->_post->getPost($this->filtrarInt($id));
        
        $this->_view->renderizar('editar', 'post');
    }
    
    public function eliminar($id){
        if(!$this->filtrarInt($id)){
            $this->redireccionar('post');
        }
        
        if(!$this->_post->getPost($this->filtrarInt($id))){
            $this->redireccionar('post');
        }
        
        $this->_post->eliminarPost($this->filtrarInt($id));
        $this->redireccionar('post');
    }
}

?>