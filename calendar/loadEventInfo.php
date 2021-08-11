<?php

    include('../include/database.php');
    include("../include/funciones.php");

    #Get id from event click
    $id_evento = escapar($_POST["id"]);

    #MySQL
    $result = $conexion->query('SELECT * FROM '.$tabla_calendario.' WHERE id_evento = '.$id_evento.'');
    $json = array();

    while($row=$result->fetch_assoc())
    {
        $id_cita = $row["id_cita"];
        $cita = queryOne('SELECT * FROM '. $tabla_citas . ' WHERE id_cita = '. $id_cita . '');
        $paciente = queryOne('SELECT * FROM '. $tabla_pacientes . ' WHERE id_paciente = "'. $cita["id_paciente"] .'"');
        $calltracker = queryOne('SELECT * FROM '. $tabla_call_tracker . ' WHERE id_patient = "'. $paciente["id_paciente"] .'" ORDER BY id_call DESC LIMIT 1');

        $codeagle = $paciente['id_paciente_eagle'];
        if(strpos($codeagle, "M") !== true){
            $codeagle = str_replace('M','',$codeagle);
        }else{
            $codeagle = $paciente['id_paciente_eagle'];
        }

        $tipopat = $calltracker["tipo_paciente"];
        if(empty($tipopat)){
            $tipopat = "Current Patient";
        }else{
            $tipopat = $calltracker["tipo_paciente"];
        }

        if(empty($calltracker["id_referal"])){
            $referral = "Provided by EagleSoft";
        }else{
            $ref = queryOne('SELECT * FROM '. $tabla_call_referal . ' WHERE id_referal = '. $calltracker["id_referal"] .'');
            $referral = $ref["referal_name"];
        }
        
        if(empty($cita["id_reason"])){
            $rea = queryOne('SELECT type_description FROM '.$tabla_appt_types.' WHERE type_id = '.$row['id_app_type'].';');
            $reason = $rea["type_description"];
        }else{
            $reason = $cita["id_reason"];
        }

        if(empty($cita["id_user"])){
            $user = "";
        }else{
            $user = $cita["id_user"];
        }

        $icon_state = "";
        if ($row["evento_icon"] == "check") {
            $icon_state = "Confirmed Appointment";
        }
        else if ($row["evento_icon"] == "asterisk") {
            $icon_state = "Schedule Appointment";
        }
        else if ($row["evento_icon"] == "paper-plane") {
            $icon_state = "Message Sent";
        }
        else if ($row["evento_icon"] == "ban") {
            $icon_state = "Cancel Appoinment";
        }
        else if ($row["evento_icon"] == "trash") {
            $icon_state = "Delete Appoinment";
        }

        $provider = "";
        if ($cita["cita_provider"] == "Call Center") {
            $provider = "Call on RingByName";
        }
        else if ($cita["cita_provider"] == "FBTopDental") {
            $provider = "Top Dental FB from (".$cita["cita_chat"].") chat";
        }
        else if ($cita["cita_provider"] == "FBDebbie") {
            $provider = "Dr Debbie FB from (".$cita["cita_chat"].") chat";
        }
        else if ($cita["cita_provider"] == "IGTopDental") {
            $provider = "Top Dental IG from (".$cita["cita_chat"].") DM";
        }
        else if ($cita["cita_provider"] == "IGDebbie") {
            $provider = "Dr Debbie IG from (".$cita["cita_chat"].") DM";
        }
        else if ($cita["cita_provider"] == "Google Business") {
            $provider = "Google Business";
        }

        $fecha_cita = date("m/d/Y",strtotime($paciente["paciente_fechanac"]));

        $paciente_name = ucfirst(quitar_tildes($paciente["paciente_nombres"]));
        $paciente_last = ucfirst(quitar_tildes($paciente["paciente_apellidos"]));

        if ($cita["cita_campaign"] == NULL || $cita["cita_campaign"] == "" || $cita["cita_campaign"] == "none") {
            $campaign = "--";
        }else{
            $campaign = $cita["cita_campaign"];
        }

        $json[]=array(
            'reason'   => $reason,
            'eagle' => $codeagle,
            'type' => $calltracker["tipo_paciente"],
            'duration'   => $cita["cita_duracion"],
            'patient'   => $paciente_name.' '.$paciente_last,
            'language'   => $paciente["paciente_idioma"],
            'genre' => $paciente["paciente_genero"],
            'birthday'   => $fecha_cita,
            'insurance'   => $paciente["paciente_tiene_seguro"],
            'notes'   => $cita["cita_notas"],
            'referral'   => $referral,
            'contact'   => formatTelefono($paciente["paciente_contacto"]),
            'user' => $user,
            'icono' => $icon_state,
            'provider' => $provider,
            'campaign' => $campaign,
            'dateAdd' => date("m/d/Y h:i A",strtotime($cita["cita_scheduled"])),
        );
    }

    $jsonstring = json_encode($json);
    echo $jsonstring;

?>
