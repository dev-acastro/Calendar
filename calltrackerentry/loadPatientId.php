<?php
    require("../include/database.php");
    include("../include/funciones.php");

    /* $clinica = escapar($_POST["id_clinica"]);
    $ultimo_paciente = $conexion->query('SELECT COUNT(id_paciente) AS id FROM '.$tabla_paciente_por_clinica.' WHERE id_clinica='.$clinica.'');
    if ($id_paciente_por_clinica = $ultimo_paciente->fetch_assoc()) {
        $id = $id_paciente_por_clinica['id']+1;
        echo $id;
    } */

    $clinica = escapar($_POST["id_clinica"]);


    if($clinica == 1){
        $result = $conexion->query('SELECT SUBSTR(id_paciente,3)+1 as cod FROM '.$tabla_pacientes.' WHERE id != "PROCESSING" AND id_clinica='.$clinica.' AND SUBSTR(id_paciente,3) BETWEEN 2000 and 2500 AND SUBSTR(id_paciente,3)+1 NOT IN (SELECT SUBSTR(id_paciente,3) FROM '.$tabla_pacientes.' WHERE id_clinica= '.$clinica.') ORDER BY id DESC LIMIT 1 FOR UPDATE;');
        if ($id_paciente = $result->fetch_assoc()) {
            $id = $id_paciente['cod'];
            echo $id;
        }
        
    }else if($clinica == 3){
        $result = $conexion->query('SELECT SUBSTR(id_paciente,3)+1 as cod FROM '.$tabla_pacientes.' WHERE id != "PROCESSING" AND id_clinica='.$clinica.' AND SUBSTR(id_paciente,3)<2000 AND SUBSTR(id_paciente,3)+1 NOT IN (SELECT SUBSTR(id_paciente,3) FROM '.$tabla_pacientes.' WHERE id_clinica= '.$clinica.') ORDER BY id DESC LIMIT 1 FOR UPDATE;');
        if ($id_paciente = $result->fetch_assoc()) {
            $id = $id_paciente['cod'];
            echo $id;
        }
    }else{
        $result = $conexion->query('SELECT max(cast(SUBSTR(id_paciente,3) as unsigned))+1 as id FROM '.$tabla_pacientes.' WHERE id_clinica='.$clinica.'');
        if ($id_paciente = $result->fetch_assoc()) {
            $id = $id_paciente['id'];
            echo $id;
        }
    }

    
?> 