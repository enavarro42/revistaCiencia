<?php

//ini_set('display_errors', 1);

define('DS', DIRECTORY_SEPARATOR);

define('ROOT', realpath(dirname(__FILE__)) . DS);

define('APP_PATH', ROOT. 'application'. DS);


try{
    
    require_once APP_PATH . 'bootstrap.php';
    require_once APP_PATH . 'config.php';
    require_once APP_PATH . 'controller.php';
    require_once APP_PATH . 'model.php';
    require_once APP_PATH . 'request.php';
    require_once APP_PATH . 'view.php';
    require_once APP_PATH . 'Database.php';
    require_once APP_PATH . 'Session.php';
    require_once APP_PATH . 'Hash.php';
    
    Session::init();

    Bootstrap::run(new Request);
}catch(Exception $e){
    echo $e->getMessage();
}

?>