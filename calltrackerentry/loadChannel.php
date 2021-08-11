<?php 
    require("../include/database.php");
    include("../include/funciones.php");

    $term = $_GET['term'];
    
    #MySQL
    $result = $conexion->query('SELECT * FROM '.$tabla_call_channel.' WHERE channel LIKE "'.$term.'%"');
    $json = array();

    while($channel=$result->fetch_assoc())
    {
        $json[]=array(
            'value' => $channel["id_channel"],
            'label' => $channel["channel"],
            'channel' => $channel["channel"]
        );
    }

    $jsonstring = json_encode($json);
    echo $jsonstring;
    
?>