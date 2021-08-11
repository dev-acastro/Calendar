<?php 
    require("../include/database.php");
    include("../include/funciones.php");

    $clinica = $_POST['id_clinica'];

    #MySQL
    $result = $conexion->query('SELECT date_format(bh_date,"%m-%d-%Y") as bh_date FROM '.$tabla_calendario_horas.' WHERE id_clinica = '.$clinica.';');
    $json = array();
    $count = 0;

    while($row=$result->fetch_assoc())
    {
        /* array_push($json,$row["bh_date"]); */
        array_push($json,$row["bh_date"]);
        /* $json .= "15-6-2020";
        $json .= "16-6-2020"; */
        $count++;
    }

    $jsonstring = json_encode($json);
    echo $jsonstring;

?>