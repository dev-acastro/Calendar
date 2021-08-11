<?php
    require("../include/database.php");
    include("../include/funciones.php");

    if(isset($_POST['id_clinica'])){

        //$id_pac = escapar($_POST['id_paciente']);
        $id_clinica = escapar($_POST['id_clinica']);
        $tipo_seguro = escapar($_POST['tipo_seguro']);
        $fn = escapar($_POST['paciente_fechanac']);

        $cita_hora = escapar($_POST['cita_hora']);
        $hora = date("H:i:s", strtotime($cita_hora));

        //Recalcular el id del paciente
        /* $result = $conexion->query('SELECT max(cast(SUBSTR(id_paciente,3) as unsigned))+1 as id FROM '.$tabla_pacientes.' WHERE id_clinica='.$id_clinica.'');
        if ($id_paciente = $result->fetch_assoc()) {
            $clinicName = queryOne('SELECT clinica_nombre FROM '.$tabla_clinicas.' WHERE id_clinica = '.$id_clinica.';');
            $letraI = strtoupper($clinicName['clinica_nombre'][0]);
            $letraF = strtoupper(substr($clinicName['clinica_nombre'],-1));
            $id = trim($letraI.$letraF.$id_paciente['id']);
            //echo $id;
        } */
        //Recalcular el id del paciente
        if($id_clinica == 1){
            $result = $conexion->query('SELECT min(cast(SUBSTR(id_paciente,3) as unsigned))+1 as id FROM '.$tabla_pacientes.' WHERE id_clinica='.$id_clinica.' AND SUBSTR(id_paciente,3) BETWEEN 1099 AND (SELECT max(cast(SUBSTR(id_paciente,3) as unsigned))+1 FROM '.$tabla_pacientes.' WHERE id_clinica='.$id_clinica.');');
            if ($id_paciente = $result->fetch_assoc()) {
                $clinicName = queryOne('SELECT clinica_nombre FROM '.$tabla_clinicas.' WHERE id_clinica = '.$id_clinica.';');
                $letraI = strtoupper($clinicName['clinica_nombre'][0]);
                $letraF = strtoupper(substr($clinicName['clinica_nombre'],-1));
                $id = trim($letraI.$letraF.$id_paciente['id']);
                //echo $id;
            }
            
        }else{
            $result = $conexion->query('SELECT max(cast(SUBSTR(id_paciente,3) as unsigned))+1 as id FROM '.$tabla_pacientes.' WHERE id_clinica='.$id_clinica.'');
            if ($id_paciente = $result->fetch_assoc()) {
                $clinicName = queryOne('SELECT clinica_nombre FROM '.$tabla_clinicas.' WHERE id_clinica = '.$id_clinica.';');
                $letraI = strtoupper($clinicName['clinica_nombre'][0]);
                $letraF = strtoupper(substr($clinicName['clinica_nombre'],-1));
                $id = trim($letraI.$letraF.$id_paciente['id']);
                //echo $id;
            }
        }

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
            'id_paciente' => $id,
            'id_paciente_eagle' => str_replace($id[1], '',$id),
            'id_clinica' => $id_clinica,
            'paciente_nombres' => escapar($_POST['paciente_nombres']),
            'paciente_apellidos' => escapar($_POST['paciente_apellidos']),	
            'paciente_fechanac'	=> date("Y-m-d", strtotime($fn)),
            'paciente_edad'	=> calculaedad($fn),
            'paciente_contacto'	=> escapar($_POST['paciente_contacto']),
            'paciente_tiene_seguro'	=> escapar($_POST['paciente_tiene_seguro']),
        );

        $conexion->autocommit(false);

        $paciente = insertar($tabla_pacientes,$campos_paciente);
        
        if($paciente){
            //ID para Policy Holder
            if($id_clinica == 1){
                $result_ph = $conexion->query('SELECT min(cast(SUBSTR(id_paciente,3) as unsigned))+1 as id FROM '.$tabla_pacientes.' WHERE id_clinica='.$id_clinica.' AND SUBSTR(id_paciente,3) BETWEEN 1099 AND (SELECT max(cast(SUBSTR(id_paciente,3) as unsigned))+1 FROM '.$tabla_pacientes.' WHERE id_clinica='.$id_clinica.');');
                if ($id_paciente_ph = $result_ph->fetch_assoc()) {
                    $clinicName = queryOne('SELECT clinica_nombre FROM '.$tabla_clinicas.' WHERE id_clinica = '.$id_clinica.';');
                    $letraI = strtoupper($clinicName['clinica_nombre'][0]);
                    $letraF = strtoupper(substr($clinicName['clinica_nombre'],-1));
                    $id_ph = trim($letraI.$letraF.$id_paciente_ph['id_ph']);
                    //echo "Pat_ph ".$id_ph;
                }
            }else{
                $result_ph = $conexion->query('SELECT max(cast(SUBSTR(id_paciente,3) as unsigned))+1 as id_ph FROM '.$tabla_pacientes.' WHERE id_clinica='.$id_clinica.'');
                if ($id_paciente_ph = $result_ph->fetch_assoc()) {
                    $clinicName = queryOne('SELECT clinica_nombre FROM '.$tabla_clinicas.' WHERE id_clinica = '.$id_clinica.';');
                    $letraI = strtoupper($clinicName['clinica_nombre'][0]);
                    $letraF = strtoupper(substr($clinicName['clinica_nombre'],-1));
                    $id_ph = trim($letraI.$letraF.$id_paciente_ph['id_ph']);
                    //echo "Pat_ph ".$id_ph;
                }
            }

            $campos_paciente_ph = array(
                'id_paciente' => $id_ph,
                'id_paciente_eagle' => str_replace($id_ph[1], '',$id_ph),
                'id_clinica' => $id_clinica,
                'paciente_nombres' => escapar($_POST['paciente_nombres_ph']),
                'paciente_apellidos' => escapar($_POST['paciente_apellidos_ph']),	
                'paciente_fechanac'	=> date("Y-m-d", strtotime(escapar($_POST['paciente_birth_ph']))),
                'paciente_edad'	=> calculaedad(escapar($_POST['paciente_birth_ph'])),
                'paciente_contacto'	=> escapar($_POST['paciente_contacto_ph'])
            );

            $paciente_ph = insertar($tabla_pacientes,$campos_paciente_ph);
            if($paciente_ph){

                $id_paciente = queryOne('SELECT id_paciente as idpa FROM '.$tabla_pacientes.' WHERE id = '.$paciente.';');
                $id_paciente_ph = queryOne('SELECT id_paciente as idpa_ph FROM '.$tabla_pacientes.' WHERE id = '.$paciente_ph.';');

                $campos_policy_holder_table = array(
                    'id_paciente' => $id_paciente['idpa'],
                    'id_paciente_ph' => $id_paciente_ph['idpa_ph'],
                    'ph_relationship' => escapar($_POST['paciente_relacion_ph'])
                );

                $policy_holder = insertar($tabla_pacientes_policy_holder,$campos_policy_holder_table);

                if($policy_holder){

                    $tipo_paciente = escapar($_POST['tipo_paciente']). " Patient";
                    $campos_call_tracker = array(
                        'id_patient' => $id_paciente['idpa'],
                        'id_clinica' => $id_clinica,
                        'id_channel' => escapar($_POST['id_channel']),
                        'id_referal' => escapar($_POST['id_referal']),
                        'id_referal_desc' => escapar($_POST['current_patient_referal_id']),
                        'tipo_paciente' => $tipo_paciente,
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
                            'cita_hora' => $hora,
                            'cita_duracion' => escapar($_POST['cita_duracion']),
                            'cita_notas' => escapar($_POST['cita_notas']),
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
                                $hora_inicial = $hora;
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
            }
        }

        $json = array();

        if($paciente && $paciente_ph && $policy_holder && $id_call && $id_cita && $e_cita && $calendario){
            $conexion->commit();
            $json[] = array(
                'response' => "Success",
                'id_generado' => $id_paciente['idpa'],
                'id_generado_ph' => $id_paciente_ph['idpa_ph'],
            );
        }else{
            $conexion->rollback();
            $json[] = array(
                'response' => "Error"
            );
        }

        $jsonstring = json_encode($json);
        echo $jsonstring;   

    }
?>