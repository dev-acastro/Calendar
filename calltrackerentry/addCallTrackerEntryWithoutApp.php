<?php
    require("../include/database.php");
    include("../include/funciones.php");

    if(isset($_POST['id_clinica'])){

        $id_clinica = escapar($_POST['id_clinica']);
        $id_pac = escapar($_POST['id_paciente']);

        $campos_call = array(
            'call_id_paciente' => $id_pac,
            'call_clinica' => $id_clinica,
            'call_referral_desc' => escapar($_POST['current_patient_referal_id']),
            'call_notas'	=> escapar($_POST['call_notes']),
            'call_channel'	=> escapar($_POST['id_channel']),
            'call_referral'	=> escapar($_POST['id_referal']),
            'call_user' =>  escapar($_SESSION['user'])
        );

        $campos_paciente = array(
            'np_nombre' => escapar($_POST['paciente_nombres']),
            'np_apellido' =>  escapar($_POST['paciente_apellidos']),
            'np_contacto' =>  escapar($_POST['paciente_contacto']),
            'np_user' =>  escapar($_SESSION['user'])
        );

        $conexion->autocommit(false);

        $no_paciente = insertar($tabla_no_paciente,$campos_paciente);
        if($no_paciente){
            $call = insertar($tabla_calls,$campos);
        }

        if($no_paciente && $call){
            $conexion->commit();
            echo "Success";
        }else{
            $conexion->rollback();
            echo "Error";
        }
    }
?>