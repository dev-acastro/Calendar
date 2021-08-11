<?php

    include('../include/database.php');
    include("../include/funciones.php");

    if(isset($_POST["id"])){
        $id = escapar($_POST["id"]);
        $date = escapar($_POST["date"]);
        $start = escapar($_POST["start"]);
        
        $evento = queryOne('SELECT * FROM '.$tabla_calendario.' WHERE id_evento = '.$id.';');
        $cita = queryOne('SELECT * FROM '.$tabla_citas.' WHERE id_cita = '.$evento["id_cita"].';');
        $num_row = cantidad('id_evento',$id,$tabla_calendario);

        $duracion = $cita['cita_duracion'];
        $hora_final = date('H:i',strtotime("$duracion", strtotime($start)));
    
            $condicion_cita = array(
                'id_cita' => $evento['id_cita']
            );

            $condicion_calendario = array(
                'id_evento' => $id
            );

            $campos_cita = array(
                'cita_fecha' => $date,
                'cita_hora' => $start
            );

            $campos_cita_estado = array(
                'estado_cita' => "Rescheduled",
                'estado_color' => "#f7b924" 
            );

            $campos_calendario = array(
                'evento_fecha' => $date,
                'evento_inicio' => $start,
                'evento_fin' => $hora_final,
                'evento_icon' => "check",
                'evento_needforms' => "no"
            );

            if(actualizar($tabla_calendario,$campos_calendario,$condicion_calendario)
            && actualizar($tabla_citas,$campos_cita,$condicion_cita)
            && actualizar($tabla_citas_estado,$campos_cita_estado,$condicion_cita)){
                    echo "Success";
                }
                else{
                    echo "Error";
                    echo $hora_final;
                }

       
    }
