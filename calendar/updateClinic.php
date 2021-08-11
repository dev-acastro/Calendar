<?php

    include('../include/database.php');
    include("../include/funciones.php");

    if(isset($_POST["id"])){
        $id = escapar($_POST["id"]);
        $clinica = escapar($_POST["clinic"]);
        
        $id_cita = queryOne('SELECT id_cita as id FROM '.$tabla_calendario.' WHERE id_evento = '.$id.';');
        $campos = array(
            'id_clinica' => $clinica
        );
        $condicion = array(
            'id_cita' => $id_cita['id']
        );

        if(actualizar($tabla_calendario,$campos,$condicion) && actualizar($tabla_citas,$campos,$condicion)){
                echo "Success";
        }
        else{
            echo "Error";
        }
    }
