<?php 
    require("../include/database.php");
    include("../include/funciones.php");

    #Colores
    $azul = "#16aaff";
    $verde = "#3ac47d";
    $naranja = "#f7b924";
    $gris = "#dcdcdc";
    $negro = "#343a40";
    $rosado = "#f775f0";
    $rojo = "#d92550";

    $conexion->query('UPDATE '.$tabla_citas_estado.' SET estado_cita = "Finished", estado_color = "'.$gris.'" WHERE estado_color NOT in ("'.$azul.'","'.$naranja.'","'.$rosado.'","'.$rojo.'") AND id_cita in (SELECT id_cita FROM '.$tabla_calendario.' WHERE id_clinica=3 AND NOW() > STR_TO_DATE(concat_ws(" ", evento_fecha,evento_inicio),"%Y-%m-%d %H:%i:%s") AND (evento_inicio >= STR_TO_DATE("09:00:00", "%H:%i:%s") AND evento_inicio <= STR_TO_DATE("19:00:00", "%H:%i:%s")))');

    $conexion->query('UPDATE '.$tabla_citas_estado.' SET estado_cita = "No Show Up", estado_color = "'.$negro.'" WHERE estado_color NOT in ("'.$azul.'","'.$naranja.'","'.$rosado.'","'.$rojo.'") AND id_cita in (SELECT id_cita FROM '.$tabla_calendario.' WHERE id_clinica=3 AND NOW() > STR_TO_DATE(concat_ws(" ", evento_fecha,evento_inicio),"%Y-%m-%d %H:%i:%s") AND (evento_inicio < STR_TO_DATE("08:30:00", "%H:%i:%s") OR evento_inicio > STR_TO_DATE("19:00:00", "%H:%i:%s")))');

    //update event icon and confirm app when event color is black
    $conexion->query('UPDATE '.$tabla_calendario.' SET evento_icon = "check", evento_needforms = "no" WHERE id_cita in (SELECT id_cita FROM '.$tabla_citas_estado.' WHERE estado_color in ("'.$gris.'","'.$negro.'"))');

    $events = $conexion->query('SELECT * FROM '.$tabla_calendario.' WHERE id_clinica=3 ORDER BY id_evento');
    while($row_fill=$events->fetch_assoc())
    {
        $color = queryOne('SELECT * FROM '. $tabla_citas_estado . ' WHERE id_cita = '. $row_fill['id_cita'] . '');
        $json[]=array(
            'id'   => $row_fill["id_evento"],
            'title'   => $row_fill["id_paciente"],
            'start'   => $row_fill["evento_fecha"].'T'.$row_fill["evento_inicio"],
            'end'   => $row_fill["evento_fecha"].'T'.$row_fill["evento_fin"],
            'color'   => $color["estado_color"],
            'icon' => $row_fill["evento_icon"],
            'needform' => $row_fill["evento_needforms"]
        );
    }

    #Available hours background
    $horario = $conexion->query('SELECT * FROM '.$tabla_calendario_horas.' WHERE id_clinica=3;');
    while($business=$horario->fetch_assoc())
    {
        $bh[]=array(
            'start'   => $business["bh_date"].'T'.$business["bh_start"],
            'end'   => $business["bh_date"].'T'.$business["bh_end"],
            'rendering' => 'background',
            'overlap' => true,
            'color' => '#ff9f89',
            'groupId' => 'availableForAppointment'
        );
    }
    
    $jsonstring = json_encode(array_merge($json,$bh));
    echo $jsonstring;   
?>