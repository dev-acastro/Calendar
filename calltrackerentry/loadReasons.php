<?php 

    //Buscar Reasons
    require("../include/database.php");
    include("../include/funciones.php");

    $search = $_GET['term'];
    $result = $conexion->query('SELECT * FROM '.$tabla_citas_reason.' WHERE reason_nombre LIKE "%'.$search.'%" LIMIT 8');
    $json = array();

    while ($reason = $result->fetch_assoc()) 
    {
        $json[]=array(
            'value' => $reason['reason_nombre'],
            'label' => $reason['reason_nombre'],
            'duration' => $reason['reason_duracion'],
        );
    }

    $jsonstring = json_encode($json);
    echo $jsonstring;
    //'label' => $reason['reason_nombre'] .': '.$reason['reason_descripcion']
?>