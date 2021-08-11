<?php

    include('../include/database.php');
    include("../include/funciones.php");

    if(isset($_POST["id"])){
        $id = escapar($_POST["id"]);
        $date = escapar($_POST["date"]);
        $time_start = escapar($_POST["time"]);
        
        $resultado = $conexion->query('SELECT * FROM '.$tabla_citas.' WHERE id_cita = '.$id.';');

        while($row=$resultado->fetch_assoc()){

            $hora_inicial = $time_start;
            $duracion = $row['cita_duracion'];
            $hora_final = date('H:i',strtotime("$duracion", strtotime($hora_inicial)));

            $valores_app = array(
                'cita_fecha' => $date,
                'cita_hora' => $time_start
            );

            $valores_app_estado = array(
                'estado_cita' => "Rescheduled",
                'estado_color' => "#f7b924"
            );
    
            $valores_cal = array(
                'evento_fecha' => $date,
                'evento_inicio' => $time_start,
                'evento_fin' => $hora_final
            );
    
            $condicion = array(
                'id_cita' => $id
            );

            if(actualizar($tabla_citas,$valores_app,$condicion) &&
                actualizar($tabla_citas_estado,$valores_app_estado,$condicion) &&
                actualizar($tabla_calendario,$valores_cal,$condicion)){
                    echo "Success";
                }
                else{
                    echo "Error";
                }
            
        }

       
    }


?>
