<?php


class Database extends PDO{
    //private $_dnsPostgre = ''

   private $DB_HOST = 'localhost';
   private $DB_USER = 'postgres';
   private $DB_PASS =  '1234';
   private $DB_PORT = '5432';
   private $DB_NAME = 'revistasFec';

    public function __construct(){

        // postgres
        parent::__construct(
                'pgsql:host=' . $this->DB_HOST . 
                ';dbname=' . $this->DB_NAME . 
                ';port=' . $this->DB_PORT .
                ';user=' . $this->DB_USER .
                ';password=' . $this->DB_PASS
                );

    }

}


class Model{

	protected $_db;
    
    public function __construct(){
        $this->_db = new Database();
        $this->_db->query('SET NAMES UTF8');
    }

}


class recordatorioModel extends Model{
    
    public function __construct(){
    	parent::__construct();
    }

    public function getRecordatorio(){
    	$result = $this->_db->query("SELECT distinct r.id_responsable, r.id_persona, r.id_manuscrito, r.id_rol, r.fecha_inicio, r.fecha_fin, record.id_recordatorio, record.fecha  FROM  responsable r LEFT OUTER JOIN recordatorio record ON r.id_responsable = record.id_responsable where r.permiso = 1");
        return $result->fetchAll();
    }

    public function getConfRecordatorio($id_rol){
    	$result = $this->_db->query("SELECT * from config_recordatorio where id_rol = $id_rol");
        return $result->fetch();
    }


    public function setRecordatorio($id_responsable, $id_config_recordatorio, $fecha){
    	$this->_db->prepare("INSERT INTO recordatorio(id_responsable, id_config_recordatorio, fecha) VALUES(:id_responsable, :id_config_recordatorio, :fecha)")
                ->execute(
                        array(
                            ":id_responsable" => $id_responsable,
                            ":id_config_recordatorio" => $id_config_recordatorio,
                            ":fecha" => $fecha
                        )
                 );
    }

    public function editarFechaRecordatorio($id_responsable){
    	$this->_db->query("UPDATE recordatorio SET fecha=current_date WHERE id_responsable = $id_responsable;");
    }

    public function getIdRol($rol){

        $id = $this->_db->query(
                "SELECT id_rol from rol WHERE rol = '$rol'"
                );
        return $id->fetch();
    }


    public function getDatos($id_persona){
        $persona = $this->_db->query(
                "select * from persona where id_persona=$id_persona"
                );
        
        $arreglo = $persona->fetch();

        return $arreglo;
    }


}

?>