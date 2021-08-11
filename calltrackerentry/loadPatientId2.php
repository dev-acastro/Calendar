<?php
    require("../include/database.php");
    include("../include/funciones.php");

    $clinica = escapar($_POST["id_clinica"]);

    if($clinica == 1){
        $result = $conexion->query('SELECT SUBSTR(id_paciente,3)+2 as cod FROM '.$tabla_pacientes.' WHERE id != "PROCESSING" AND id_clinica='.$clinica.' AND SUBSTR(id_paciente,3)>1099 AND SUBSTR(id_paciente,3)+1 NOT IN (SELECT SUBSTR(id_paciente,3) FROM '.$tabla_pacientes.' WHERE id_clinica= '.$clinica.') ORDER BY id DESC LIMIT 1 FOR UPDATE;');
        if ($id_paciente = $result->fetch_assoc()) {
            $id = $id_paciente['cod'];
            echo $id;
        }
        
    }else{
        $result = $conexion->query('SELECT max(cast(SUBSTR(id_paciente,3) as unsigned))+2 as id FROM '.$tabla_pacientes.' WHERE id_clinica='.$clinica.'');
        if ($id_paciente = $result->fetch_assoc()) {
            $id = $id_paciente['id'];
            echo $id;
        }
    }
?> 