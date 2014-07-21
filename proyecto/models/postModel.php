<?php
class postModel extends Model{
    public function __construct(){
        parent::__construct();
    }
    
    public function getPosts(){
        $post = $this->_db->query("select * from post");
        return $post->fetchall();
    }
    
    public function getPost($id){
        $id = (int) $id;
        $post = $this->_db->query("select * from post where id = $id");
        return $post->fetch();
    }
    
    public function insertarPost($titulo, $cuerpo, $imagen){
        $this->_db->prepare("INSERT INTO post(titulo, cuerpo, imagen) VALUES (:titulo, :cuerpo, :imagen)")
                ->execute(
                            array(
                                ':titulo' => $titulo,
                                ':cuerpo' => $cuerpo,
                                ':imagen' => $imagen
                            )
                        );
    }
    
    public function editarPost($id, $titulo, $cuerpo){
        
        $id = (int) $id;
        
        $this->_db->prepare("UPDATE post SET titulo = :titulo, cuerpo = :cuerpo WHERE id = :id")
                ->execute(
                            array(
                                ':id' => $id,
                                ':titulo' => $titulo,
                                ':cuerpo' => $cuerpo
                            )
                        );
        
    }
    
    public function eliminarPost($id){
        $id = (int) $id;
        $this->_db->query("DELETE FROM post WHERE id = $id");
    }
}
?>