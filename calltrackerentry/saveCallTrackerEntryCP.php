<?php
    require("../include/database.php");
    include("../include/funciones.php");

    if(isset($_POST['id_paciente'])){

        $id_paciente = escapar($_POST['id_paciente']);
        $id_clinica = escapar($_POST['id_clinica']);

        $campos_cita = array(
            'id_paciente' => $id_paciente,
            'id_clinica' => $id_clinica,
            'id_reason' => escapar($_POST['id_reason']),
            'cita_fecha' => escapar($_POST['cita_fecha']),
            'cita_hora' => escapar($_POST['cita_hora']),
            'cita_duracion' => escapar($_POST['cita_duracion']),
            'id_user' => escapar($_SESSION['user'])
        );

        $campos_call_tracker = array(
            'id_patient' => $id_paciente,
            'id_clinica' => $id_clinica,
            'id_channel' => escapar($_POST['id_channel']),
            'id_referal' => escapar($_POST['id_referal']),
            'id_referal_desc' => escapar($_POST['current_patient_referal_id']),
            /* 'id_campaign' => escapar($_POST['id_campaign']), */
            'tipo_paciente' => escapar($_POST['tipo_paciente']),
            'call_notas' => escapar($_POST['call_notas']),
            'call_schedule_app' => escapar($_POST['call_hizo_cita']),
            'call_user' => escapar($_SESSION['user'])
        );

        $id_cita= insertar($tabla_citas,$campos_cita);
        $id_call= insertar($tabla_call_tracker,$campos_call_tracker);

        $hora_inicial = escapar($_POST['cita_hora']);
        $duracion = escapar($_POST['cita_duracion']);
        $hora_final = date('H:i',strtotime("$duracion", strtotime($hora_inicial)));

        //$color = "#16aaff";

        $campos_calendario = array(
            'id_cita' => $id_cita,
            'id_paciente' => $id_paciente,
            'id_paciente_eagle' => str_replace($id_paciente[1], '',$id_paciente),
            'id_call' => $id_call,
            'id_clinica' => $id_clinica,
            'evento_fecha' => escapar($_POST['cita_fecha']),
            'evento_inicio' => $hora_inicial,
            'evento_fin' => $hora_final,/* ,
            'evento_color' => escapar($_POST['color']) */            
        );

        $estado_cita = "Scheduled";

        $campos_cita_estado = array(
            'id_cita' => $id_cita,
            'estado_cita' => $estado_cita,
            'estado_color' => escapar($_POST['color'])
        );

        if(insertar($tabla_calendario,$campos_calendario) && insertar($tabla_citas_estado,$campos_cita_estado)){
            echo "Success";
        }
        else{
            echo "Error";
                 print_r($campos_calendario);
                 print_r($campos_cita_estado);
                 print_r($campos_call_tracker);      
       
        }       
    }
?>