<?php

class Bootstrap{
    public static function run(Request $peticion){
        $controller = $peticion->getControlador(). 'Controller';
        $rutaControlador = ROOT . 'controllers' . DS . $controller . '.php';
        $metodo = $peticion->getMetodo();
        $args = $peticion->getArgs();
        
        //is_readable: verifica si el archivo de la ruta existe y es valido
        if(is_readable($rutaControlador)){
            require_once $rutaControlador;
            $controller = new $controller;
            
            if(is_callable(array($controller, $metodo))){
                $metodo = $peticion->getMetodo();
            }else{
                $metodo = 'index';
            }
            
            if(isset($args)){
                //llamada a una funcion con un metodo pasandole parametros
                call_user_func_array(array($controller, $metodo), $args);
            }else{
                call_user_func(array($controller, $metodo));
            }
        }else{
            throw  new Exception('No Encontrado');
        }
    }
}

?>