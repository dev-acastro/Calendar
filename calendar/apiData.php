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

    function dateToClinic( string $date) : string
    {
        $notFormattedDateFromApi = new DateTime($date);
        $formattedDateFromApi = $notFormattedDateFromApi->format('Y-m-d H:i:s');

        $dateFormatted = new DateTime($formattedDateFromApi, new DateTimeZone('zulu'));
        $dateFormatted->setTimezone('America/New_York');

        return $dateFormatted->format('Y-m-d H:i:s');

    }

    function dateToApi( string $date) : string
    {
        $notFormattedDateFromApi = new DateTime($date);
        $formattedDateFromApi = $notFormattedDateFromApi->format('Y-m-d H:i:s');

        $dateFormatted = new DateTime($formattedDateFromApi, new DateTimeZone('America/New_York'));
        $dateFormatted->setTimezone('Zulu');

        return $dateFormatted->format('Y-m-d\T H:i:s.v\Z');

    }

    function getAppointments($locations, $startDate, $endDate, $clinica, $lastSync)
    {
        global $OrganizationID;
        global $token;


        $url = 'https://prod.hs1api.com/ascend-gateway/api/v1/appointments?filter=location.id=='.$locations[$clinica] . ',start>=' . $startDate . ",start<".$endDate;

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

    function getPatientByChartNumber($chartNumber)
    {
        global $OrganizationID;
        global $token;

        $curl = curl_init('https://prod.hs1api.com/ascend-gateway/api/v1/patients?filter=chartNumber =='.$chartNumber);

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

    function p($d) {
        print_r($d);
        die();
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

        p($output);
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

    function findInDatabaseMax($location) {
        global $conexion;

        $sql = "SELECT max(created) from sync_times WHERE location = '" .$location . "'" ;
        $result = $conexion->query($sql);
        $row = $result -> fetch_array(MYSQLI_NUM);
        return $row[0];
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

            $resultDB =findInDatabase("appointment", $data->id, "appointment_id");

            $operatory = getDataByParam($data->operatory->url);
            $data->operatoryData = json_decode($operatory)->data;
            //$data->resourceId = $data->operatoryData->shortName;

            $clinica = getDataByParam($data->location->url);
            $data->locationData = json_decode($clinica)->data;
            $data->operatoryData->location = $data->locationData->name;

            $location = $data->operatoryData->name;
            $locationId = retrieveLocationId($location);
            $patient_id = $data->patient->id;
            $appointment_id = $data->id;


            if($resultDB->num_rows == 0) {
                //Cita no esta en Sistema

                //* ALERT -- ALERT   *//
                //Check if ChartNumber is the Id we're gonna use for Prod
             $resultPT = findInDatabase("patient", $patient_id, "patient_id");
                 if($resultPT->num_rows == 0) {
                     //Paciente no esta en sistema

                     $patient = getPatientById($data->patient->id);
                     $data->patientData = json_decode($patient)->data;

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

                $appointmentData = [
                    "id_paciente" => $data->patientData->chartNumber,
                    "start_time" => dateToClinic($data->start),
                    "end_time" => dateToClinic($data->end),
                    "patient_id" => $patient_id,

                    "location_id" => $locationId,
                    "appointment_type_id" => "1",
                    "appointment_id" => $data->id,
                    "appointment_notes" => $data->note ?? "",
                    "description" => $data->patientData->firstName . " " . $data->patientData->lastName
                ];
                $result = insertInDatabase("appointment", $appointmentData);

                if ($result) {
                    $resSync['synced'] = true;
                }
            } else {

                $appointmentData = [
                    "start_time" => dateToClinic($data->start),
                    "end_time" => dateToClinic($data->end),
                    "location_id" => $locationId,
                    "appointment_type_id" => "1",
                    "appointment_id" => $data->id,
                    "appointment_notes" => $data->note ?? "",
                ];
                $result =  updateInDatabase("appointments", $appointmentData, $id);

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
            $startDate = date("Y-m-d");
        }

        $endDate = date("Y-m-d", strtotime('+1 day', strtotime($startDate)));

        $data = [
            'location' => strtolower($clinica),
        ];

        $lastSync = findInDatabaseMax(strtolower($clinica));
        insertInDatabase("sync_times", $data);
        $result = getAppointments($locations, $startDate, $endDate,  $clinica, $lastSync);

        $result = json_decode($result);


        $appoinment = $result->data;

        $result = syncApiAppointment($appoinment);

       // echo json_encode($appoinment);


    }

    if (isset($post['action']) && $post['action'] == 'updateAppointment') {
    global $token;
    global $clinica;

    $appointmentId = $post['id'];
    $data = [];


    $currentAppoinment = getDataById("appointments", $appointmentId);
    $currentAppoinment = json_decode($currentAppoinment);



    $data['start'] = date('Y-m-d\TH:i:s.000\Z' ,strtotime($post['start']));
    $data['provider'] = $currentAppoinment->data->provider;
    $data['operatory'] = $currentAppoinment->data->operatory;
    $data['patient'] = $currentAppoinment->data->patient;
    $data['status'] = $currentAppoinment->data->status;

    $data['patient']->id = (int) $data['patient']->id;
    $data['provider']->id = (int) $data['provider']->id;
    $data['operatory']->id = (int) $data['operatory']->id;

    $result = updateAppointment($data, $appointmentId);
    print_r($result);
    }

    if (isset($post['action']) && $post['action'] == 'createAppointment') {


        global $token;

        $patientData = [
          'firstName' => 'Alez',
          'lastName' =>'Camilot',
          'contactMethod' => 'call Me',
          'languageType' => 'Spanish',
          "patientStatus" => "NEW",
          "dateOfBirth" => "",
          "preferredLocation" => [
              "id" => 7000000000115,
            "type" => "LocationV1",
            "url" => "https://prod.hs1api.com/ascend-gateway/api/v1/locations/7000000000115"
          ],
          "address1" => "CA 9845",
          "city" => "Sacramento",
          "state" => "CA",
          "postalCode" => "94203 ",
            'chartNumber' => 'FX2345'
        ];

        if (getPatientbyChartNumber($patientData['chartNumber'])) {
            $result = createAppointment($data);
        } else {
            $resultNewPatient = createPatient($patientData);

        }



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




