<?php

    //INSERTAR CALL TRACKER ENTRY CUANDO EL PACIENTE TIENE POLICY HOLDER
    require("../include/database.php");
    include("../include/funciones.php");

    if(isset($_POST['id_paciente'])){

        $id_paciente = escapar($_POST['id_paciente']);
        $id_clinica = escapar($_POST['id_clinica']);
        $tipo_seguro = escapar($_POST['tipo_seguro']);

        $fn = escapar($_POST['paciente_fechanac']);

        function calculaedad($fechanacimiento){
            list($ano,$mes,$dia) = explode("-",$fechanacimiento);
            $ano_diferencia  = date("Y") - $ano;
            $mes_diferencia = date("m") - $mes;
            $dia_diferencia   = date("d") - $dia;
            if ($dia_diferencia < 0 || $mes_diferencia < 0)
              $ano_diferencia--;
            return $ano_diferencia;
        }

        $campos_paciente = array(
            'id_paciente' => $id_paciente,
            'id_paciente_eagle' => str_replace($id_paciente[1], '',$id_paciente),
            'id_clinica' => $id_clinica,
            'paciente_nombres' => escapar($_POST['paciente_nombres']),
            'paciente_apellidos' => escapar($_POST['paciente_apellidos']),	
            'paciente_fechanac'	=> escapar($_POST['paciente_fechanac']),
            'paciente_edad'	=> calculaedad($fn),
            'paciente_contacto'	=> escapar($_POST['paciente_contacto']),
            'paciente_tiene_seguro'	=> escapar($_POST['paciente_tiene_seguro']),
        );

        $campos_cita = array(
            'id_paciente' => $id_paciente,
            'id_clinica' => $id_clinica,
            'id_reason' => escapar($_POST['id_reason']),
            'cita_fecha' => escapar(date('Y-m-d',strtotime($_POST['cita_fecha']))),
            'cita_hora' => escapar($_POST['cita_hora']),
            'cita_duracion' => escapar($_POST['cita_duracion']),
            'id_user' => escapar($_SESSION['user'])
        );

        $campos_call_tracker = array(
            'id_patient' => $id_paciente,
            'id_clinica' => $id_clinica,
            'id_channel' => escapar($_POST['id_channel']),
            'id_referal' => escapar($_POST['id_referal']),
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
        $color = "#16aaff";

        $campos_calendario = array(
            'id_cita' => $id_cita,
            'id_paciente' => $id_paciente,
            'id_paciente_eagle' => escapar($_POST['id_paciente_eagle']),
            'id_call' => $id_call,
            'id_clinica' => $id_clinica,
            'evento_fecha' => escapar(date('Y-m-d',strtotime($_POST['cita_fecha']))),
            'evento_inicio' => $hora_inicial,
            'evento_fin' => $hora_final
            /* 'evento_color' => $color */
        );
        $estado_cita = "Scheduled";
        $campos_cita_estado = array(
            'id_cita' => $id_cita,
            'estado_cita' => $estado_cita,
            'estado_color' => escapar($_POST['color'])
        );


            $campos_paciente_policy_holder = array(
                'id_paciente' => escapar($_POST['id_paciente_ph']),
                'id_paciente_eagle' => escapar($_POST['id_paciente_eagle_ph']),
                'id_clinica' => $id_clinica,
                'paciente_nombres' => escapar($_POST['paciente_nombres_ph']),
                'paciente_apellidos' => escapar($_POST['paciente_apellidos_ph']),
                'paciente_contacto'	=> escapar($_POST['paciente_contacto_ph']),
                'paciente_fechanac'	=> escapar($_POST['paciente_birth_ph'])
            );

            $campos_policy_holder_table = array(
                'id_paciente' => $id_paciente,
                'id_paciente_ph' => escapar($_POST['id_paciente_ph']),
                'ph_relationship' => escapar($_POST['paciente_relacion_ph'])
            );    

            if(insertar($tabla_pacientes,$campos_paciente) 
                && insertar($tabla_pacientes,$campos_paciente_policy_holder) 
                && insertar($tabla_pacientes_policy_holder,$campos_policy_holder_table)
                && insertar($tabla_calendario,$campos_calendario)
                && insertar($tabla_citas_estado,$campos_cita_estado)){
                    echo "Success";
               }
            else{
                echo "Error";
            }
    }
?>