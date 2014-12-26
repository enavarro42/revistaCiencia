<?php
//define('BASE_URL', 'http://localhost/revistaciencia/');
define('BASE_URL', '/revista/proyecto/admin/');
//define('BASE_URL', 'http://e-navarro.com.ve/');

//controlador por defecto
define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_LAYOUT', 'default');

define('APP_NAME', 'Revistas Arbitradas');
define('APP_SLOGAN', 'Revistas Arbitradas');
define('APP_COMPANY', 'Universidad del Zulia');
define('SESSION_TIME', 60);
define('HASH_KEY', '52c1bf355efed');

define('ROOT_IMAGE', BASE_URL. 'image/');

//servidor local mysql
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_PORT', '5432');
// define('DB_NAME', 'revistasFec');
// define('DB_CHAR', 'UTF8');

//servidor local postgres
define('DB_HOST', 'localhost');
define('DB_USER', 'postgres');
define('DB_PASS', '1234');
define('DB_PORT', '5432');
define('DB_NAME', 'revistasFec');
define('DB_CHAR', 'UTF8');


 // Servidor externo
// define('DB_HOST', 'localhost');
// define('DB_USER', 'enavarro_fec');
// define('DB_PASS', 'fecluz');
// define('DB_PORT', '5432');
// define('DB_NAME', 'enavarro_revistas_fec');
// define('DB_CHAR', 'UTF8');



//DESABILITAR EL error_reporting(0);
?>