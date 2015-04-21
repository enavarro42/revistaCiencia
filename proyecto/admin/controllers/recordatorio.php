<?php

    require_once('../libs/class.phpmailer.php');
    require_once('../models/recordatorioModel.php');

    //inicializamos la clase recordatorioModel, para obtener los metodos de esa clase
    $recordatorio = new recordatorioModel();

    $id_autor = $recordatorio->getIdRol("Autor");
    $id_arbitro = $recordatorio->getIdRol("Arbitro");

    $responsable_recordatorio = array();
    $fecha_anterior = '';
    $fecha_anterior_temp = '';

    //obtener los recordatorios actuales
    $record = $recordatorio->getRecordatorio();

    //var_dump($record);

    for($i = 0; $i<count($record); $i++){
        //si el id_config_recordatario, esta en null entonces hay que hacer el primer recordatorio
        
        if($record[$i]['id_recordatorio'] == null){
            $config_record = $recordatorio->getConfRecordatorio($record[$i]['id_rol']);
            $recordatorio->setRecordatorio($record[$i]['id_responsable'], $config_record["id_config_recordatorio"], date("Y-m-d"));
            $responsable_recordatorio[] = array('id_responsable'=> $record[$i]['id_responsable'], 'id_rol'=>$record[$i]['id_rol']);
        }else{//si no, entonces modificar la fecha y hacer el recordatorio si se cumplen los dias para hacer el recordatorio

            $config_record = $recordatorio->getConfRecordatorio($record[$i]['id_rol']);

            //fecha del ultimo recordatorio

            if($record[$i]['id_rol'] == $id_autor[0]){
                $siguiente_fecha = new DateTime($record[$i]['fecha']);
                $siguiente_fecha->add(new DateInterval('P'.$config_record["recordar_cada"].'D'));
            }else if($record[$i]['id_rol'] == $id_arbitro[0]){
                $siguiente_fecha = new DateTime($record[$i]['fecha']);
                $siguiente_fecha->add(new DateInterval('P'.$config_record["recordar_cada"].'D'));
            }

            //si la fecha siguinete al recordatorio es menor igual a la fecha actual, entonces hacer recordatorio
            //var_dump(date_format($siguiente_fecha, 'Y-m-d') ." --- ". date('Y-m-d'));
            if(date_format($siguiente_fecha, 'Y-m-d') <= date('Y-m-d')){
                $recordatorio->editarFechaRecordatorio($record[$i]['id_responsable']);
                $responsable_recordatorio[] = array('id_responsable'=> $record[$i]['id_responsable'], 'id_persona'=> $record[$i]['id_persona'], 'id_rol'=>$record[$i]['id_rol']);
            }
        }
    }

    //enviar correos
    $mail_autor = new PHPMailer();
    $mail_arbitro = new PHPMailer();
    if($responsable_recordatorio){

        $i = 0; 
        while(isset($responsable_recordatorio[$i])) {
            $persona = $recordatorio->getDatos($responsable_recordatorio[$i]['id_persona']);
            if($responsable_recordatorio[$i]['id_rol'] == $id_autor[0]){

                $mail_autor->From = 'www.fecRevistasCientificas.com';
                $mail_autor->FromName = 'Revistas Cientificas';
                $mail_autor->Subject = 'Revistas FEC';
                $mail_autor->Body = "Hola, ".$persona["primerNombre"] . " " . $persona["apellido"] ." <strong>Tienes un manuscrito por corregir, en nuestro portal web Revistas Arbitradas de la UNIVERSIDAD DEL ZULIA.</strong>";
                $mail_autor->AltBody = "Su servidor de correo no soporta html";
                $mail_autor->addAddress($persona['email']);

                $mail_autor->Send();

            }else if($responsable_recordatorio[$i]['id_rol'] == $id_arbitro[0]){
                
                $mail_autor->From = 'www.fecRevistasCientificas.com';
                $mail_autor->FromName = 'Revistas Cientificas';
                $mail_autor->Subject = 'Revistas FEC';
                $mail_autor->Body = "Hola, ".$persona["primerNombre"] . " " . $persona["apellido"] ." <strong>Tienes un manuscrito por evaluar, en nuestro portal web Revistas Arbitradas de la UNIVERSIDAD DEL ZULIA.</strong>";
                $mail_autor->AltBody = "Su servidor de correo no soporta html";
                $mail_autor->addAddress($persona['email']);

                $mail_autor->Send();
            }
            
            $i++; 
        }
    }

?>