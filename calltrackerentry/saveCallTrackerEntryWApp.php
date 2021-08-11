<?php
    require("../include/database.php");
    include("../include/funciones.php");

    if(isset($_POST['paciente_nombres'])){

        $id_clinica = escapar($_POST['id_clinica']);

        $campos = array(
            'call_nombre' => escapar($_POST['paciente_nombres']),
            'call_apellido' =>  escapar($_POST['paciente_apellidos']),
            'call_contacto' =>  escapar($_POST['paciente_contacto']),            
            'call_clinica' => $id_clinica,	
            'call_referral_desc' => escapar($_POST['current_patient_referal_id']),	
            'call_notas'	=> escapar($_POST['call_notes']),
            'call_channel'	=> escapar($_POST['id_channel']),
            'call_referral'	=> escapar($_POST['id_referal']),
            'call_fn' =>  escapar($_POST['paciente_fechanac']),
            'call_user' =>  escapar($_SESSION['user'])
        );

        if(insertar($tabla_calls,$campos)){
            echo "Success";
        }
        else{
            echo "Error";
        }
       
    }
?>