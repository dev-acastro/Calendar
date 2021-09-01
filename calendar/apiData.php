<?php


    require '../include/database.php';

    if(!empty($_POST)){
        $post = $_POST;
    }

   //Constantes a Cambiar 
    $token = [];
    $client_id = "kdqoHWRZ1YB5M99QVrtc9p4v2kxSct89";
    $client_secret = "IqHYGkvrsN7eFfRe";
    $OrganizationID = "5e7b7774c9e1470c0d716320";
    $locations = [
        "manassas" => "7000000000114",
        "fairfax" => "7000000000115",
        "woodbridge" => "",
    ];

    function getToken($client_id, $client_secret)
    {
        $curl = curl_init('https://prod.hs1api.com/oauth/client_credential/accesstoken?grant_type=client_credentials');
        curl_setopt($curl, CURLOPT_POSTFIELDS, "client_id=" . $client_id . "&client_secret=" . $client_secret );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));

        $output = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($output);

        return $result;

    }

    function getAppointments($locations, $startDate, $endDate, $clinica, $lastSync)
    {
        global $OrganizationID;
        global $token;


        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/appointments?filter=location.id=='.$locations[$clinica] . ',start>=' . $startDate . ",start<".$endDate.",lastModified>=" .$lastSync);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return $output;

    }

    function getPatientById($id)
    {
        global $OrganizationID;
        global $token;

        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/patients/'. $id);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return $output;

    }

    function getDataByParam($url) {
        global $OrganizationID;
        global $token;

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return $output;
    }

    function getDataById($ep, $id) {
        global $OrganizationID;
        global $token;

        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/'.$ep."/".$id);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return $output;
    }

    function updateAppointment($data, $id){
        global $OrganizationID;
        global $token;

        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/appointments/'. $id);
        $body = json_encode($data);



        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);


        return $output;
    }

    function createAppointment($data, $id){
        global $OrganizationID;
        global $token;

        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/appointments/'. $id);
        $body = json_encode($data);



        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);


        return $output;
    }

    function deleteAppointment($id){
        global $OrganizationID;
        global $token;

        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/appointments/'. $id);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. $token,
            'Organization-ID: '. $OrganizationID,
        ));

        $output = curl_exec($curl);

        curl_close($curl);

        return $output;
    }

    function findInDatabase($table, $id, $column) {
        global $conexion;

        $sql = "SELECT * from $table WHERE $column = ?";
        $conn = $conexion->prepare($sql);
        $conn->bind_param("s", $id);
        $conn->execute();
        $result = $conn->get_result();

        return $result;
    }

    function findInDatabaseMax($table, $id, $column) {
        global $conexion;

        $sql = "SELECT max(date_created) from $table WHERE $column = ?";
        $conn = $conexion->prepare($sql);
        $conn->bind_param("s", $id);
        $conn->execute();
        $result = $conn->get_result();

        return $result;
    }

    function insertInDatabase($table, $data) {
        global $conexion;

        $c = implode(",", array_keys($data));
        $d = implode(", ", array_values($data));

        $template ="";
        $values = "";
        $result = [];


        foreach ($data as $columns) {
            if (!empty($template)) {
                $template .= ",?";            
            } else {
                $template .="?";
            }
            $values .="s";
        }

        $sql = "INSERT INTO $table ($c) VALUES ($template)";
        $conn = $conexion->prepare($sql);
        $conn->bind_param($values, ...array_values($data));
        
        $result = $conn->get_result();

        if ($conn->execute()) {
            return true;
        } else {
           return false;
        }
    }

    function updateInDatabase($table, $data, $id) {
        $values ="";
        global $conexion;

        foreach ($data as $key => $value) {
            $values = empty($values)? "," : "". $key ."=" . $value;
        }

        $sql = "UPDATE $table SET $values WHERE id = $id ";
        $result = $conexion->query($sql);
        if ($result) {
            return true;
        }


    }

    function retrieveLocationId($location) {

        $result = "";

        switch ($location) {
            case "FFX1":
                $result = "7";
                break;
            case "FFX2":
                $result = "8";
                break;
            case "FFX3":
                $result = "9";
                break;
            case "FFX4":
                $result = "10";
                break;
            case "OP-1":
                $result = "2";
                break;
            case "OP-2":
                $result = "3";
                break;
            case "OP-3":
                $result = "4";
                break;
            case "OP-4":
                $result = "5";
                break;
            case "WG1":
                $result = "11";
                break;
            case "WG2":
                $result = "12";
                break;
            case "WG3":
                $result = "13";
                break;
            case "WG4":
                $result = "14";
                break;

        }
        return $result;

    }

    function syncApiAppointment($dataApi) {

        $resSync =[];
        $resSync['synced'] = false;

        foreach ($dataApi as $data) {

            $resultDB =findInDatabase("appointment", $data->id, "api_appointment_id");

            if($resultDB->num_rows == 0) {

                $operatory = getDataByParam($data->operatory->url);
                $data->operatoryData = json_decode($operatory)->data;
                //$data->resourceId = $data->operatoryData->shortName;

                $clinica = getDataByParam($data->location->url);
                $data->locationData = json_decode($clinica)->data;
                $data->operatoryData->location = $data->locationData->name;

                $location = $data->operatoryData->name;
                $locationId = retrieveLocationId($location);
                $patient_id = $data->patient->id;

                echo"Cita no esta enDatabase";
                //* ALERT -- ALERT   *//
                //Check if ChartNumber is the Id we're gonna use for Prod
             $resultPT = findInDatabase("patient", $patient_id, "patient_id");
                 if($resultPT->num_rows == 0) {

                     $patient = getPatientById($data->patient->id);
                     $data->patientData = json_decode($patient)->data;
                     $chartNumber = $data->patientData->chartNumber;

                     //Operatory Data Retrieve From Operatory Data on Api Response


                     echo"Usuario no esta enDatabase";
                    //id_paciente corresponde a id conformado por ubicacion y numero ex.: MS2837, FX8734, etc
                    //patient_id corresponde al id tomado de la api
                    $PatientData = [
                        "id_paciente" => $data->patientData->chartNumber,
                        "patient_id" => $patient_id,
                        "first_name" => $data->patientData->firstName,
                        "last_name" => $data->patientData->lastName ?? "",
                        "home_phone" => $data->patientData->phones[0]->number ?? "",
                        "sex" => $data->patientData->gender ?? "",
                        "birth_date" => $data->patientData->dateOfBirth ?? "",
                    ];
                     $result = insertInDatabase('patient', $PatientData);

                 }

                //$patient = findInDatabase("patient", $patient_id, "patient_id");
                //$obj = $patient->fetch_assoc();
                //$idPatient = $obj['patient_id'];

                $appointmentData = [
                    "start_time" => $data->start,
                    "end_time" => $data->end,
                    "patient_id" => $patient_id,
                    "location_id" => $locationId,
                    "appointment_type_id" => "1",
                    "api_appointment_id" => $data->id,
                    "description" => $data->note ?? ""
                ];
                $result = insertInDatabase("appointment", $appointmentData);

                if ($result) {
                    $resSync['synced'] = true;
                }
            } else {
                $id = $data->id;

                $appointmentData = [
                    "start_time" => $data->start,
                    "end_time" => $data->end,
                    "patient_id" => $patient_id,
                    "location_id" => $locationId,
                    "appointment_type_id" => "1",
                    "api_appointment_id" => $data->id,
                    "description" => $data->note ?? ""
                ];
                updateInDatabase("appointments", $appointmentData, $id);

                if ($result) {
                    $resSync['synced'] = true;
                }
            }
        }
        print_r(json_encode($resSync));
    }

    //FINALIZAN TODAS LAS FUNCIONES. SIGUEN LAS ACCIONES Y REDIRECCIONES

    $resultAuthorization = getToken($client_id, $client_secret);
    $token = $resultAuthorization->access_token;


    if (isset($post['action']) && $post['action'] == 'getAppointments') {

        global $token;

        $clinica = $post['clinica'];

        if (!empty($post['dateArrow'])) {
            $startDate = $post['dateArrow'];
        } else {
            $startDate = gmdate("Y-m-d H:i:s");
        }

        $endDate = gmdate("Y-m-d H:i:s", strtotime($startDate."+ 1 day"));

        $data = [
            'location' => strtolower($clinica),
        ];
        xdebug_break();
        $lastSync = findInDatabaseMax('sync_times', strtolower($clinica), "created" );
        insertInDatabase("sync_times", $data);
        $result = getAppointments($locations, $startDate, $endDate,  $clinica, $lastSync);

        $result = json_decode($result);

        $appoinment = $result->data;

        $result = syncApiAppointment($appoinment);

       // echo json_encode($appoinment);


    }

    if (isset($post['action']) && $post['action']== 'updateAppointment') {
    global $token;
    global $clinica;

    $appointmentId = $post->id;

    $currentAppoinment = getDataById("appointments", $appointmentId);
    $currentAppoinment = json_decode($currentAppoinment);

    $post->provider = $currentAppoinment->data->provider;
    $post->operatory = $currentAppoinment->data->operatory;
    $post->patient = $currentAppoinment->data->patient;
    $post->status = $currentAppoinment->data->status;

    print_r($post);
    die();

    $result = updateAppointment($post, $appointmentId);
    }

    if (isset($post['action']) && $post['action'] == 'createAppointment') {


        global $token;


        $clinica = $post['clinica'];
        $startDate = gmdate("Y-m-d\TH:i:s\Z");
        $result = getAppointments($locations, $startDate, $clinica, true);
        $result = json_decode($result);

        $appoinment = $result->data;



        syncApiAppointment($appoinment);



        // echo json_encode($appoinment);


    }

    if (isset($post['action']) && $post['action'] == 'deleteAppointment') {

        global $token;

        $clinica = $post['clinica'];
        $startDate = gmdate("Y-m-01\TH:i:s\Z");
        $result = getAppointments($locations, $startDate, $clinica);
        $result = json_decode($result);

        $appoinment = $result->data;



        $result = syncApiAppointment($appoinment);



        // echo json_encode($appoinment);


    }




