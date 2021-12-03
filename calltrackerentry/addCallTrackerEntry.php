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
        if($id_clinica == 1){
            $result = $conexion->query('SELECT SUBSTR(id_paciente,3)+1 as cod FROM '.$tabla_pacientes.' WHERE id != "PROCESSING" AND id_clinica='.$id_clinica.' AND SUBSTR(id_paciente,3) BETWEEN 2000 and 2500 AND SUBSTR(id_paciente,3)+1 NOT IN (SELECT SUBSTR(id_paciente,3) FROM '.$tabla_pacientes.' WHERE id_clinica= '.$id_clinica.') ORDER BY id DESC LIMIT 1 FOR UPDATE;');
            if ($id_paciente = $result->fetch_assoc()) {
                $clinicName = queryOne('SELECT clinica_nombre FROM '.$tabla_clinicas.' WHERE id_clinica = '.$id_clinica.';');
                $letraI = strtoupper($clinicName['clinica_nombre'][0]);
                $letraF = strtoupper(substr($clinicName['clinica_nombre'],-1));
                $id = trim($letraI.$letraF.$id_paciente['cod']);
                //echo $id;
            }
            
        }else if($id_clinica == 3){
            $result = $conexion->query('SELECT SUBSTR(id_paciente,3)+1 as cod FROM '.$tabla_pacientes.' WHERE id != "PROCESSING" AND id_clinica='.$id_clinica.' AND SUBSTR(id_paciente,3)<2000 AND SUBSTR(id_paciente,3)+1 NOT IN (SELECT SUBSTR(id_paciente,3) FROM '.$tabla_pacientes.' WHERE id_clinica= '.$id_clinica.') ORDER BY id DESC LIMIT 1 FOR UPDATE;');
            if ($id_paciente = $result->fetch_assoc()) {
                $clinicName = queryOne('SELECT clinica_nombre FROM '.$tabla_clinicas.' WHERE id_clinica = '.$id_clinica.';');
                $letraI = strtoupper($clinicName['clinica_nombre'][0]);
                $letraF = strtoupper(substr($clinicName['clinica_nombre'],-1));
                $id = trim($letraI.$letraF.$id_paciente['cod']);
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

        /* $result = $conexion->query('SELECT max(cast(SUBSTR(id_paciente,3) as unsigned))+1 as id FROM '.$tabla_pacientes.' WHERE id_clinica='.$id_clinica.'');
        if ($id_paciente = $result->fetch_assoc()) {
            $clinicName = queryOne('SELECT clinica_nombre FROM '.$tabla_clinicas.' WHERE id_clinica = '.$id_clinica.';');
            $letraI = strtoupper($clinicName['clinica_nombre'][0]);
            $letraF = strtoupper(substr($clinicName['clinica_nombre'],-1));
            $id = trim($letraI.$letraF.$id_paciente['id']);
            //echo $id;
        } */

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
            'paciente_genero'	=> escapar($_POST['paciente_genero']),
            'paciente_fechanac'	=> date("Y-m-d", strtotime($fn)),
            'paciente_edad'	=> calculaedad($fn),
            'paciente_idioma'	=> escapar($_POST['paciente_idioma']),
            'paciente_contacto'	=> formatTelefono(escapar($_POST['paciente_contacto'])),
            'paciente_tiene_seguro'	=> escapar($_POST['paciente_tiene_seguro']),
            'paciente_ciudad'	=> escapar($_POST['paciente_ciudad']),
            'paciente_estado'	=> escapar($_POST['paciente_estado']),
            //'paciente_user'	=> escapar($_SESSION['user'])
        );


        $conexion->autocommit(false);

        $paciente = insertar($tabla_pacientes,$campos_paciente);
        if($paciente){
            $id_paciente = queryOne('SELECT id_paciente as idpa FROM '.$tabla_pacientes.' WHERE id = '.$paciente.';');
            
            $tipo_paciente = escapar($_POST['tipo_paciente']). " Patient";
            $campos_call_tracker = array(
                'id_patient' => $id_paciente['idpa'],
                'id_clinica' => $id_clinica,
                'id_channel' => escapar($_POST['id_channel']),
                'id_referal' => escapar($_POST['id_referal']),
                'id_referal_desc' => escapar($_POST['current_patient_referal_id']),
                'tipo_paciente' => $tipo_paciente,
                'call_schedule_app' => escapar($_POST['call_hizo_cita']),
                //'call_user' => escapar($_SESSION['user'])
            );
            $id_call= insertar($tabla_call_tracker,$campos_call_tracker);
            if($id_call){
                
                $campos_cita = array(
                    'id_paciente' => $id_paciente['idpa'],
                    'id_clinica' => $id_clinica,
                    //'id_user' => escapar($_SESSION['user']),
                    'id_reason' => escapar($_POST['id_reason']),
                    'cita_fecha' => escapar(date('Y-m-d',strtotime($_POST['cita_fecha']))),
                    'cita_hora' => $hora,
                    'cita_duracion' => escapar($_POST['cita_duracion']),
                    'cita_notas' => escapar($_POST['cita_notas']),
                    'cita_provider' => escapar($_POST['cita_provider']),
                    'cita_chat' => escapar($_POST['cita_chat']),
                    'cita_campaign' => escapar($_POST['cita_campaign']),
                    'cita_estado' => 'UNCONFIRMED',
                    'cita_seat' => escapar($_POST['operatory']),
                    'api_id' => escapar($_POST['api_id'])
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

        $json = array();

        if($paciente && $id_call && $id_cita && $e_cita && $calendario){
            $conexion->commit();
            $json[] = array(
                'response' => "Success",
                'id_generado' => $id_paciente['idpa'],
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