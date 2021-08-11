<?php 
    require("../include/database.php");
    include("../include/funciones.php");

    $clinica = $_POST['id_clinica'];
    $selected_date = date('Y-m-d',strtotime($_POST['date']));

    #MySQL
    $result = $conexion->query('SELECT bh_start,bh_end FROM '.$tabla_calendario_horas.' WHERE id_clinica = '.$clinica.' AND bh_date = "'.$selected_date.'";');
    $json = array();

    while($row=$result->fetch_assoc())
    {
        $json[]=array(
            'start' => date('g:i a',strtotime($row['bh_start'])),
            'end' => date('g:i a',strtotime($row['bh_end']))
        );
    }

    $jsonstring = json_encode($json);
    echo $jsonstring;

?>