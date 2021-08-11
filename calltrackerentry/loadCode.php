<?php 

    //Buscar Paciente Registrado
    require("../include/database.php");
    include("../include/funciones.php");

    $search = $_GET['term'];
    $result = $conexion->query('SELECT * FROM '.$tabla_pacientes.' WHERE id_paciente LIKE "'.$search.'%" OR paciente_nombres LIKE "'.$search.'%" OR paciente_apellidos LIKE "'.$search.'%" LIMIT 4');
    $json = array();

    while ($paciente = $result->fetch_assoc()) 
    {
        $json[]=array(
            'value' => $paciente['id_paciente'],
            'label' => $paciente['id_paciente'] .': '.$paciente['paciente_apellidos'].', '.$paciente['paciente_nombres'],
            'id_paciente' => $paciente['id_paciente'],
            'paciente_nombres' => $paciente['paciente_nombres'], 
            'paciente_apellidos' => $paciente['paciente_apellidos'],
            'paciente_contacto' => $paciente['paciente_contacto']
        );
    }

    $jsonstring = json_encode($json);
    echo $jsonstring;   
?>