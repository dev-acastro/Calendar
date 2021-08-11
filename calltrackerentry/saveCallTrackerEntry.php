<?php
    require("../include/database.php");
    include("../include/funciones.php");

    if(isset($_POST['id_paciente'])){

        $id_pac = escapar($_POST['id_paciente']);
        $id_clinica = escapar($_POST['id_clinica']);
        $tipo_seguro = escapar($_POST['tipo_seguro']);
        $fn = escapar($_POST['paciente_fechanac']);
        $num_row = cantidad('id_paciente',$id_pac,$tabla_pacientes);

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
                'id_paciente' => $id_pac,
                'id_paciente_eagle' => str_replace($id_pac[1], '',$id_pac),
                'id_clinica' => $id_clinica,
                'paciente_nombres' => escapar($_POST['paciente_nombres']),
                'paciente_apellidos' => escapar($_POST['paciente_apellidos']),	
                'paciente_fechanac'	=> escapar($_POST['paciente_fechanac']),
                'paciente_edad'	=> calculaedad($fn),
                'paciente_contacto'	=> escapar($_POST['paciente_contacto']),
                'paciente_tiene_seguro'	=> escapar($_POST['paciente_tiene_seguro']),
            );

            $conexion->autocommit(false);

            $paciente = insertar($tabla_pacientes,$campos_paciente);
            
            if($paciente){
                $id_paciente = queryOne('SELECT id_paciente as idpa FROM '.$tabla_pacientes.' WHERE id = '.$paciente.';');
                $campos_call_tracker = array(
                    'id_patient' => $id_paciente['idpa'],
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
                $id_call= insertar($tabla_call_tracker,$campos_call_tracker);
                if($id_call){
                    $campos_cita = array(
                        'id_paciente' => $id_paciente['idpa'],
                        'id_clinica' => $id_clinica,
                        'id_reason' => escapar($_POST['id_reason']),
                        'cita_fecha' => escapar(date('Y-m-d',strtotime($_POST['cita_fecha']))),
                        'cita_hora' => escapar($_POST['cita_hora']),
                        'cita_duracion' => escapar($_POST['cita_duracion']),
                        'id_user' => escapar($_SESSION['user'])
                    );
                    $id_cita= insertar($tabla_citas,$campos_cita);

                    if($id_cita){
                        $estado_cita = "Scheduled";
                        $campos_cita_estado = array(
                            'id_cita' => $id_cita,
                            'estado_cita' => $estado_cita,
                            'estado_color' => escapar($_POST['color'])
                        );
                        $e_cita = insertar($tabla_citas_estado,$campos_cita_estado);
                        if($e_cita){
                            $hora_inicial = escapar($_POST['cita_hora']);
                            $duracion = escapar($_POST['cita_duracion']);
                            $hora_final = date('H:i',strtotime("$duracion", strtotime($hora_inicial)));
                    
                            if($_POST['needform'] == "yes"){
                                $nf = "yes";
                            }else {
                                $nf = "no";
                            }

                            $pat = $id_paciente['idpa'];
                    
                            $campos_calendario = array(
                                'id_cita' => $id_cita,
                                'id_paciente' => $pat,
                                'id_paciente_eagle' => str_replace($pat[1], '',$pat),
                                'id_call' => $id_call,
                                'id_clinica' => $id_clinica,
                                'evento_fecha' => escapar(date('Y-m-d',strtotime($_POST['cita_fecha']))),
                                'evento_inicio' => $hora_inicial,
                                'evento_fin' => $hora_final,
                                'evento_needforms' => $nf
                            );
                            $calendario = insertar($tabla_calendario,$campos_calendario);
                        }
                    }
                }
            }

            if($paciente && $id_call && $id_cita && $e_cita && $calendario){
                $conexion->commit();
                echo "Success";
            }else{
                $conexion->rollback();
                echo "Error";
            }

            
        }
    }
?>