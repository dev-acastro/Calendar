<?php

    include('../include/database.php');
    include("../include/funciones.php");

    #MySQL
    $result = $conexion->query('SELECT * FROM '.$tabla_calendario_horas.' WHERE id_clinica = 3;');
    $json = array();

    while($row=$result->fetch_assoc())
    {
        $json[]=array(
            'start'   => $row["bh_date"].'T'.$row["bh_start"],
            'end'   => $row["bh_date"].'T'.$row["bh_end"],
            'rendering' => 'background'
        );
    }

    $jsonstring = json_encode($json);
    echo $jsonstring;

?>
