<?php
require("../include/database.php");
include("../include/funciones.php");



#Sync with Eagle
if(isset($_POST['idClinica'])){
    /* $arreglo=getSchedulerClients($_POST['fecha'],$_POST['idClinica']);
    echo json_encode($arreglo); */
    $clinica = escapar($_POST['idClinica']);
    $date_post = date('Y-m-d',strtotime(escapar($_POST['fecha'])));
    $json = array();
    if($clinica == 1){ $condicion_clinica = '7,8,9,10';}
    else if($clinica == 2){ $condicion_clinica = '1,2,3,5,16';}
    else if($clinica == 3){ $condicion_clinica = '11,12,13,14';}

    $sql ='SELECT start_time,end_time,appointment_notes,appointment_id,patient.patient_id,location_id,confirmation_status,appointment_type_id,patient.first_name as pat_name,patient.last_name as pat_lastname ,
        appt_types.type_color_hex AS color FROM appointment INNER JOIN patient ON patient.patient_id = appointment.patient_id LEFT JOIN appt_types ON appt_types.type_id = appointment.appointment_type_id  WHERE DATE(start_time) = "'.$date_post.'" AND location_id IN ('.$condicion_clinica.')';



    $result = $conexion->query($sql);


    print_r($result);
    die();

    while ($reg=$result->fetch_assoc())
    {
        $patient_id = $reg["patient_id"];
        if (strpos($patient_id, 'M') === FALSE && strpos($patient_id, 'F') === FALSE && strpos($patient_id, 'W') === FALSE) {
            $id_pat_to_insert = trim('M'.$patient_id);
        }else{
            $id_pat_to_insert = trim($patient_id);
        }

        $pat_name = $reg["pat_name"];
        $pat_lastname = $reg["pat_lastname"];
        $appointment_id = $reg["appointment_id"];
        $date_appt = date("Y-m-d",strtotime($reg["start_time"]));
        $start_time = date("H:i:s",strtotime($reg["start_time"]));
        $end_time = date("H:i:s",strtotime($reg["end_time"]));
        $confirmation_status = $reg["confirmation_status"];
        $location_id = $reg["location_id"];
        $appt_type_id = $reg["appointment_type_id"];
        $appointment_notes = $reg["appointment_notes"];
        $appt_color = $reg["color"];

        if($location_id == 1){$id_clinica = 2; $idRand="OP 1";}
        else if($location_id == 2){$id_clinica = 2; $idRand="OP 2";}
        else if($location_id == 3){$id_clinica = 2; $idRand="OP 3";}
        else if($location_id == 5){$id_clinica = 2; $idRand="OP 4";}
        else if($location_id == 7){$id_clinica = 1; $idRand="FFX1";}
        else if($location_id == 8){$id_clinica = 1; $idRand="FFX2";}
        else if($location_id == 9){$id_clinica = 1; $idRand="FFX3";}
        else if($location_id == 10){$id_clinica = 1; $idRand="FFX4";}
        else if($location_id == 11){$id_clinica = 3; $idRand="WBG1";}
        else if($location_id == 12){$id_clinica = 3; $idRand="WBG2";}
        else if($location_id == 13){$id_clinica = 3; $idRand="WBG3";}
        else if($location_id == 14){$id_clinica = 3; $idRand="WBG4";}
        else if($location_id == 16){$id_clinica = 2; $idRand="OP 5";}

        if($appt_color == "#000000"){
            $appt_textcolor = "white";
        }else{
            $appt_textcolor = "black";
        }

        if($appt_type_id == 0){
            $appt_color = "#969696";
            $appt_textcolor = "black";
        }

        if($confirmation_status == 0){$icon = "question"; $color_icon = "danger";}
        else if($confirmation_status == 1){$icon = "check"; $color_icon = "success";}
        else if($confirmation_status == 2){$icon = "paper-plane"; $color_icon = "primary";}
        else if($confirmation_status == 3){$icon = "phone"; $color_icon = "dark";}

        $json[]=array(
            /* 'id'   => $appointment_id, */
            'resourceId'   => $idRand,
            'title'   => quitar_tildes($pat_name)." ".quitar_tildes($pat_lastname),
            'start'   => $date_appt.'T'.$start_time,
            'end'   => $date_appt.'T'.$end_time,
            'color' => $appt_color,
            'textColor' => $appt_textcolor,
            'description' => $appointment_notes/* ,
                'color'   => $appt_color,
                'icon' => $icon,
                'coloricon' => $color_icon */
        );
    }

    $jsonstring = json_encode($json);
    echo $jsonstring;
}

?>