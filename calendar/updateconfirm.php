<?php

    include('../include/database.php');
    include("../include/funciones.php");

    if(isset($_POST["id"])){
        $id = escapar($_POST["id"]);
        $icon = escapar($_POST["icon"]);

        $icon_state = "";
        if ($icon == "confirmed") {
            $icon_state = "check";
        }
        else if ($icon == "notconfirmed") {
            $icon_state = "asterisk";
        }
        else if ($icon == "sendmessage") {
            $icon_state = "paper-plane";
        }
        else if ($icon == "cancelapp") {
            $icon_state = "ban";
        }

        if($icon == "cancelapp"){
            $valores_calendario = array(
                'evento_icon' => $icon_state,
                'evento_needforms' => "no"
            );
            $campos_estado_cita = array(
                'estado_cita' => "Canceled",
                'estado_color' => "#f775f0"
            );
            $id_cita = queryOne('SELECT id_cita as id FROM '.$tabla_calendario.' WHERE id_evento = '.$id.';');
            $condicion_calendario = array(
                'id_evento' => $id
            );
            $condicion_estado_cita = array(
                'id_cita' => $id_cita['id']
            );

            if(actualizar($tabla_calendario,$valores_calendario,$condicion_calendario) 
                && actualizar($tabla_citas_estado,$campos_estado_cita,$condicion_estado_cita)){
                    echo "Success";
            }
            else{
                echo "Error";
            }
        }else{
            $valores_calendario = array(
                'evento_icon' => $icon_state,
            );
            $campos_estado_cita = array(
                'estado_cita' => "Scheduled",
                'estado_color' => "#3ac47d"
            );
            $id_cita = queryOne('SELECT id_cita as id FROM '.$tabla_calendario.' WHERE id_evento = '.$id.';');
            $condicion_calendario = array(
                'id_evento' => $id
            );
            $condicion_estado_cita = array(
                'id_cita' => $id_cita['id']
            );

            if(actualizar($tabla_calendario,$valores_calendario,$condicion_calendario) 
                && actualizar($tabla_citas_estado,$campos_estado_cita,$condicion_estado_cita)){
                    echo "Success";
            }
            else{
                echo "Error";
            }
        }
    }
