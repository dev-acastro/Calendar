<?php

    require '../include/database.php';


    if(!empty($_POST)){
        $post = $_POST;
        $inputs = $_POST;
        $clinica = $inputs['clinica'];
       foreach($post as $k => $d) {
           $post = json_decode($k);
       }
    }



   // $conexion=mysqli_connect('localhost','root','','calendar')or die('no se pudo conectar a la base de datos');
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

    function getAppointments($locations, $startDate, $clinica)
    {
        global $OrganizationID;
        global $token;

        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/appointments?filter=location.id=='.$locations[$clinica] . ',start>=' . $startDate);

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

    function insertInDatabase($table, $columns, $id) {
        global $conexion;

        $c = implode(",", array_keys($columns));
        $d = implode(", ", array_values($columns));


        $sql = "INSERT INTO $table ($c) VALUES (?,?,?,?,?)";
        $conn = $conexion->prepare($sql);
        $conn->bind_param("s,s,s,s,s", $d[0], $d[1], $d[2], $d[3], $d[4],);
        $conn->execute();
        $result = $conn->get_result();
        return $conexion->error;
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

        global $conexion;

        $result = [];
        foreach ($dataApi as $data) {

            $location = $data->operatoryData->name;
            $locationId = retrieveLocationId($location);




            $resultDB =findInDatabase("appointment", $data->id, "api_appointment_id");

            if($resultDB->num_rows == 0) {
             $resultPT = findInDatabase("patient", $data->patientData->chartNumber, "id_paciente");

                 if($resultPT->num_rows == 0) {

                     $fN= $data->patientData->firstName;
                     $lN= $data->patientData->lastName;
                     $pH= $data->patientData->phones[0]->number;
                     $gD= $data->patientData->gender;
                     $dob= $data->patientData->dateOfBirth;
                     $id = $data->patientData->chartNumber;
                     $pI= $data->patientData->id;

                     $sql = "INSERT INTO patient(id_paciente, first_name, last_name, home_phone, sex, birth_date, patient_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                     $conn = $conexion->prepare($sql);
                     $conn->bind_param("sssssss", $id, $fN, $lN, $pH, $gD, $dob, $pI);
                     $result = $conn->execute();

                     if ($result) {
                         $patient = findInDatabase("patient", $id, "id_paciente");
                         $obj = $patient->fetch_assoc();
                         $idPatient = $obj['id'];

                        $start_time = $data->start;
                        $end_time = $data->end;
                        $location_id = $locationId;
                        $patient_id = $obj['patient_id'];
                        $apiId = $data->id;

                         $sql = "INSERT INTO appointment(start_time, end_time, patient_id, location_id, api_appointment_id) VALUES (?, ?, ?, ?,?)";
                         $conn = $conexion->prepare($sql);
                         $conn->bind_param("sssss",  $start_time, $end_time, $idPatient, $location_id, $apiId );
                         $conn->execute();

                     }

                 }else {
                     $id = $data->patientData->chartNumber;
                     $patient = findInDatabase("patient", $id, "id_paciente");
                     $obj = $patient->fetch_assoc();
                     $idPatient = $obj['id'];

                     $start_time = $data->start;
                     $end_time = $data->end;
                     $location_id = $locationId;
                     $patient_id = $idPatient;
                     $apiId = $data->id;



                     $sql = "INSERT INTO appointment(start_time, end_time, patient_id, location_id, api_appointment_id) VALUES (?, ?, ?, ?,?)";
                     $conn = $conexion->prepare($sql);
                     $conn->bind_param("sssss",  $start_time, $end_time, $patient_id, $location_id, $apiId );
                     $resultappt = $conn->execute();


                 }

            }

        }

    }



    $resultAuthorization = getToken($client_id, $client_secret);
    $token = $resultAuthorization->access_token;


    if (isset($inputs['action']) && $inputs['action'] == 'getAppointments') {


        global $token;
        global $clinica;

        $startDate = gmdate("Y-m-01\TH:i:s\Z");
        $result = getAppointments($locations, $startDate, $clinica);
        $result = json_decode($result);
        $appoinment = $result->data;
        foreach ($appoinment as $data) {
            $data->editable = true;
            $patient = getPatientById($data->patient->id);
            $data->patientData = json_decode($patient)->data;

            $operatory = getDataByParam($data->operatory->url);
            $data->operatoryData = json_decode($operatory)->data;
            $data->resourceId = $data->operatoryData->shortName;

            $clinica = getDataByParam($data->location->url);
            $data->locationData = json_decode($clinica)->data;
            $data->operatoryData->location = $data->locationData->name;
        }


        $result = syncApiAppointment($appoinment);



       // echo json_encode($appoinment);


    }

    if (isset($post->action) && $post->action== 'updateAppointment') {
    global $token;
    global $clinica;

    $appointmentId = $post->id;

    $currentAppoinment = getDataById("appointments", $appointmentId);
    $currentAppoinment = json_decode($currentAppoinment);

    $post->provider = $currentAppoinment->data->provider;
    $post->operatory = $currentAppoinment->data->operatory;
    $post->patient = $currentAppoinment->data->patient;
    $post->status = $currentAppoinment->data->status;

    $result = updateAppointment($post, $appointmentId);


    }




