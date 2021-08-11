<?php

    include('../include/database.php');
    include("../include/funciones.php");

    if(isset($_POST["id"])){

        #Get id from event click
        $id_paciente = escapar($_POST["id"]);
        $id_clinica = escapar($_POST["id"]);

        #MySQL
        $result = $conexion->query('SELECT * FROM '.$tabla_pacientes.' WHERE id = '.$id_paciente.' AND id_clinica = '.$id_clinica.'');
        $json = array();

        while($row=$result->fetch_assoc())
        {
            $calltracker = queryOne('SELECT * FROM '. $tabla_call_tracker . ' WHERE id_patient = "'. $row["id_paciente"] .'"');
            $referral = queryOne('SELECT * FROM '. $tabla_call_referal . ' WHERE id_referal = '. $calltracker["id_referal"] .'');

            $json[]=array(
                'chart'   => $cita["id_reason"],
                'patient'   => $row["paciente_nombres"].' '.$row["paciente_apellidos"],
                'birth'   => $row["paciente_fechanac"],
                'insurance'   => $row["paciente_tiene_seguro"],
                'age'   => $row["paciente_edad"],
                'referral'   => $referral["referal_name"],
                'contact'   => $paciente["paciente_contacto"]
            );
        }

        $jsonstring = json_encode($json);
        echo $jsonstring;

    }

?>
