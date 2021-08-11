<?php
    require("../include/database.php");
    include("../include/funciones.php");

    if(isset($_POST['id_paciente'])){

        $id_paciente = escapar($_POST['id_paciente']);
        $id_clinica = escapar($_POST['id_clinica']);
        $tipo_seguro = escapar($_POST['tipo_seguro']);
        $fn = escapar($_POST['paciente_fechanac']);
        $num_row = cantidad('id_paciente',$id_paciente,$tabla_pacientes);

        $rec_id_paciente = $conexion->query('SELECT max(cast(SUBSTR(id_paciente,3) as unsigned))+1 as id FROM '.$tabla_pacientes.' WHERE id_clinica='.$id_clinica.'');
        if ($id_paciente = $result->fetch_assoc()) {
            $id = $id_paciente['id'];
            echo $id;
        }

        if($num_row > 0){
            echo "This chart already exists, please try again.";
        }
        else if($num_row == 0){
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
    
            if($_POST['needform'] == "yes"){
                $nf = "yes";
            }else {
                $nf = "no";
            }
    
            $campos_calendario = array(
                'id_cita' => $id_cita,
                'id_paciente' => $id_paciente,
                'id_paciente_eagle' => str_replace($id_paciente[1], '',$id_paciente),
                'id_call' => $id_call,
                'id_clinica' => $id_clinica,
                'evento_fecha' => escapar(date('Y-m-d',strtotime($_POST['cita_fecha']))),
                'evento_inicio' => $hora_inicial,
                'evento_fin' => $hora_final,
                'evento_needforms' => $nf/* ,
                'evento_color' => escapar($_POST['color']) */            
            );
    
            $estado_cita = "Scheduled";
    
            $campos_cita_estado = array(
                'id_cita' => $id_cita,
                'estado_cita' => $estado_cita,
                'estado_color' => escapar($_POST['color'])
            );
    
            if(insertar($tabla_pacientes,$campos_paciente) 
                && insertar($tabla_calendario,$campos_calendario)
                && insertar($tabla_citas_estado,$campos_cita_estado)){
                echo "Success";
            }
            else{
                echo "Error";
            }
        }
    }
?>